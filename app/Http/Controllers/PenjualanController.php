<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Admin;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    public function index()
    {
        $penjualans = Penjualan::with(['admin', 'detailPenjualan'])->get();
        return view('penjualan.penjualan', compact('penjualans'));
    }

    public function create()
    {
        $admins = Admin::all();
        $last = Penjualan::orderBy('id_penjualan', 'desc')->first();
        $nextId = $last ? $last->id_penjualan + 1 : 1;
        $detailObats = \App\Models\DetailObat::with('obat')->get();
        $obats = \App\Models\Obat::all();

        return view('penjualan.createpenjualan', compact('admins', 'nextId', 'detailObats', 'obats'));
    }

   public function getObatInfo($id_obat)
{
    // Ambil ID obat yang sudah diinput di stok opname (expired)
    $expiredStokOpnameIds = \App\Models\DetailStokOpname::join('detail_obat', 'detail_stokopname.id_detailobat', '=', 'detail_obat.id_detailobat')
        ->where('detail_obat.id_obat', $id_obat)
        ->pluck('detail_obat.id_detailobat')
        ->toArray();

    $obat = \App\Models\Obat::with(['detailObat' => function($query) use ($expiredStokOpnameIds) {
        $query->where('stok', '>', 0)
              ->whereNotIn('id_detailobat', $expiredStokOpnameIds)  // Exclude expired items
              ->orderBy('tgl_kadaluarsa', 'asc'); // FIFO order
    }])->findOrFail($id_obat);

    // Get the first available batch (FIFO)
    $firstBatch = $obat->detailObat->first();

    $regularPrice = $obat->harga_jual;
    $discountPercent = 0;
    $discountedPrice = $regularPrice;

    // If there's a batch with discount, apply it
    if ($firstBatch && $firstBatch->disc > 0) {
        $discountPercent = $firstBatch->disc;
        $discountedPrice = $regularPrice * (1 - ($discountPercent / 100));
    }

    // Calculate actual available stock (exclude expired items in stok opname)
    $availableStok = \App\Models\DetailObat::where('id_obat', $id_obat)
        ->where('stok', '>', 0)
        ->whereNotIn('id_detailobat', $expiredStokOpnameIds)
        ->sum('stok');

    return response()->json([
        'nama_obat' => $obat->nama_obat,
        'regular_price' => $regularPrice,
        'discounted_price' => $discountedPrice,
        'discount_percent' => $discountPercent,
        'has_discount' => ($discountPercent > 0),
        'stok_total' => $availableStok,
        'first_batch_id' => $firstBatch ? $firstBatch->id_detailobat : null,
        'is_available' => ($firstBatch !== null)
    ]);
}
   public function store(Request $request)
{
    $request->validate([
        'tgl_penjualan' => 'required|date',
        'id_admin' => 'required|exists:admin,id_admin',
        'id_obat' => 'required|array',
        'id_obat.*' => 'exists:obat,id_obat',
        'harga_jual' => 'required|array',
        'jumlah_terjual' => 'required|array',
    ]);

    // Ambil ID obat yang sudah diinput di stok opname (expired)
    $expiredStokOpnameIds = \App\Models\DetailStokOpname::join('detail_obat', 'detail_stokopname.id_detailobat', '=', 'detail_obat.id_detailobat')
        ->pluck('detail_obat.id_detailobat')
        ->toArray();

    // Validasi stok terlebih dahulu sebelum memproses penjualan
    for ($i = 0; $i < count($request->id_obat); $i++) {
        $id_obat = $request->id_obat[$i];
        $jumlah_terjual = $request->jumlah_terjual[$i];

        // Dapatkan data obat untuk menampilkan nama
        $obat = \App\Models\Obat::find($id_obat);
        $nama_obat = $obat ? $obat->nama_obat : 'Unknown';

        // Hitung total stok yang tersedia di detail_obat (exclude yang sudah di stok opname)
        $stok_tersedia = \App\Models\DetailObat::where('id_obat', $id_obat)
            ->where('stok', '>', 0)
            ->whereNotIn('id_detailobat', $expiredStokOpnameIds)
            ->sum('stok');

        if ($stok_tersedia < $jumlah_terjual) {
            return back()->with('error', 'Stok tidak cukup untuk obat: ' . $nama_obat . ' (ID: ' . $id_obat . ', tersedia: ' . $stok_tersedia . ', diminta: ' . $jumlah_terjual . ')')->withInput();
        }
    }

    $penjualan = Penjualan::create([
        'tgl_penjualan' => $request->tgl_penjualan,
        'id_admin' => $request->id_admin,
        'total' => 0, // Nilai total di-update setelah transaksi disimpan
    ]);
    $total_penjualan = 0;

    // Loop untuk setiap obat yang dijual
    for ($i = 0; $i < count($request->id_obat); $i++) {
        $id_obat = $request->id_obat[$i];
        $jumlah_terjual = $request->jumlah_terjual[$i];
        $harga_jual = $request->harga_jual[$i];

        $stok_tersisa = $jumlah_terjual;

        // Ambil semua detail_obat sesuai id_obat, urutkan berdasarkan tanggal kadaluarsa (FIFO)
        // Dan exclude yang sudah di stok opname
        $stok_obat_fifo = \App\Models\DetailObat::where('id_obat', $id_obat)
            ->where('stok', '>', 0) // Pastikan stok masih tersedia
            ->whereNotIn('id_detailobat', $expiredStokOpnameIds) // Exclude expired items
            ->orderBy('tgl_kadaluarsa', 'asc') // FIFO (First In, First Out)
            ->get();

        foreach ($stok_obat_fifo as $detail) {
            if ($stok_tersisa <= 0)
                break;

            $ambil = min($detail->stok, $stok_tersisa);

            // Get the related Obat for this detail record
            $obat = \App\Models\Obat::find($id_obat);

            // Calculate actual price considering discount if any
            $actual_price = $harga_jual;
            if ($detail->disc > 0) {
                // Apply the discount from the detail record
                $actual_price = $obat->harga_jual * (1 - ($detail->disc / 100));
            }

            DetailPenjualan::create([
                'id_penjualan' => $penjualan->id_penjualan,
                'id_detailobat' => $detail->id_detailobat,
                'tgl_penjualan' => $request->tgl_penjualan,
                'jumlah_terjual' => $ambil,
                'harga_jual' => $actual_price, // Use discounted price if available
                'harga_beli' => $detail->harga_beli,
            ]);

            // Update stok detail_obat
            $detail->stok -= $ambil;
            $detail->save();

            $total_penjualan += $ambil * $actual_price; // Calculate with actual price
            $stok_tersisa -= $ambil;
        }

        // Tambahkan pengurangan stok_total di tabel obat
        $obat = \App\Models\Obat::find($id_obat);
        $obat->stok_total -= $jumlah_terjual;
        $obat->save();
    }

    // Update total penjualan
    $penjualan->total = $total_penjualan;
    $penjualan->save();

    return redirect()->route('penjualan.index')->with('success', 'Penjualan berhasil disimpan.');
}
    public function show($id)
    {
        $penjualan = Penjualan::with(['admin', 'detailPenjualan'])->findOrFail($id);
        return view('penjualan.detailpenjualan', compact('penjualan'));
    }

    public function edit($id)
    {
        $penjualan = Penjualan::with(['admin', 'detailPenjualan.detailObat.obat'])->findOrFail($id);
        $admins = Admin::all();

        return view('penjualan.editpenjualan', compact('penjualan', 'admins'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tgl_penjualan' => 'required|date',
            'id_admin' => 'required|exists:admin,id_admin',
            'jumlah_terjual' => 'required|array',
            'harga_jual' => 'required|array',
            'id_detailjual' => 'required|array',
        ]);

        // Ambil data penjualan yang akan diupdate
        $penjualan = Penjualan::findOrFail($id);

        // Validasi stok untuk perubahan jumlah (jika ada penambahan)
        $detail_ids = $request->input('id_detailjual');
        $jumlahs = $request->input('jumlah_terjual');

        foreach ($detail_ids as $index => $detail_id) {
            $detail = DetailPenjualan::find($detail_id);
            if ($detail) {
                $jumlah_lama = $detail->jumlah_terjual;
                $jumlah_baru = $jumlahs[$index];

                // Jika jumlah baru lebih besar dari jumlah lama, perlu cek stok
                if ($jumlah_baru > $jumlah_lama) {
                    $selisih = $jumlah_baru - $jumlah_lama;
                    $obat = $detail->detailObat->obat;

                    // Cek stok total
                    if ($obat->stok_total < $selisih) {
                        return back()->with('error', 'Stok tidak cukup untuk obat: ' . $obat->nama_obat . ' (ID: ' . $obat->id_obat . ', tersedia: ' . $obat->stok_total . ', tambahan yang diminta: ' . $selisih . ')')->withInput();
                    }

                    // Cek stok detail
                    $stok_tersedia = \App\Models\DetailObat::where('id_obat', $obat->id_obat)
                        ->where('stok', '>', 0)
                        ->sum('stok');

                    if ($stok_tersedia < $selisih) {
                        return back()->with('error', 'Stok fisik tidak cukup untuk obat: ' . $obat->nama_obat . ' (ID: ' . $obat->id_obat . ', tersedia: ' . $stok_tersedia . ', tambahan yang diminta: ' . $selisih . ')')->withInput();
                    }
                }
            }
        }

        // Jika semua validasi berhasil, update data penjualan
        $penjualan->id_admin = $request->id_admin;
        $penjualan->tgl_penjualan = $request->tgl_penjualan;
        $penjualan->save();

        $hargas = $request->input('harga_jual');

        $total_penjualan = 0;

        foreach ($detail_ids as $index => $detail_id) {
            $detail = DetailPenjualan::find($detail_id);
            if ($detail) {
                // 1. Kembalikan stok total ke tabel obat
                $obat = $detail->detailObat->obat;
                $obat->stok_total += $detail->jumlah_terjual;
                $obat->save();

                // 2. Kembalikan stok ke detail_obat
                $detailObat = $detail->detailObat;
                $detailObat->stok += $detail->jumlah_terjual;
                $detailObat->save();

                // 3. Update ke jumlah baru
                $detail->jumlah_terjual = $jumlahs[$index];
                $detail->harga_jual = $hargas[$index];
                $detail->save();

                // 4. Kurangi lagi stok sesuai jumlah baru
                $obat->stok_total -= $detail->jumlah_terjual;
                $obat->save();

                $detailObat->stok -= $detail->jumlah_terjual;
                $detailObat->save();

                $total_penjualan += $detail->jumlah_terjual * $detail->harga_jual;
            }
        }

        // Simpan total yang baru dihitung
        $penjualan->total = $total_penjualan;
        $penjualan->save();

        return redirect()->route('penjualan.index')->with('success', 'Data penjualan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $penjualan = Penjualan::findOrFail($id);

        // Mengembalikan stok sebelum menghapus penjualan
        $detailPenjualans = DetailPenjualan::where('id_penjualan', $id)->get();

        foreach ($detailPenjualans as $detail) {
            // Kembalikan stok pada detail_obat
            $detailObat = $detail->detailObat;
            if ($detailObat) {
                $detailObat->stok += $detail->jumlah_terjual;
                $detailObat->save();

                // Kembalikan stok_total pada obat
                $obat = $detailObat->obat;
                if ($obat) {
                    $obat->stok_total += $detail->jumlah_terjual;
                    $obat->save();
                }
            }
        }

        // Hapus penjualan
        $penjualan->delete();

        return redirect()->route('penjualan.index')->with('success', 'Data penjualan berhasil dihapus.');
    }

    // Tambahkan method berikut di PenjualanController
public function checkStok($id_obat, $jumlah)
{
    $obat = \App\Models\Obat::find($id_obat);

    if (!$obat) {
        return response()->json([
            'available' => false,
            'message' => 'Obat tidak ditemukan',
            'available_stock' => 0
        ]);
    }

    // Ambil ID obat yang sudah diinput di stok opname (expired)
    $expiredStokOpnameIds = \App\Models\DetailStokOpname::join('detail_obat', 'detail_stokopname.id_detailobat', '=', 'detail_obat.id_detailobat')
        ->where('detail_obat.id_obat', $id_obat)
        ->pluck('detail_obat.id_detailobat')
        ->toArray();

    // Hitung total stok yang tersedia di detail_obat (exclude yang sudah di stok opname)
    $stok_tersedia = \App\Models\DetailObat::where('id_obat', $id_obat)
        ->where('stok', '>', 0)
        ->whereNotIn('id_detailobat', $expiredStokOpnameIds)
        ->sum('stok');

    if ($stok_tersedia < $jumlah) {
        return response()->json([
            'available' => false,
            'message' => 'Stok fisik tidak cukup untuk ' . $obat->nama_obat . ' (ID: ' . $id_obat . ')',
            'available_stock' => $stok_tersedia
        ]);
    }

    return response()->json([
        'available' => true,
        'message' => 'Stok tersedia',
        'available_stock' => $stok_tersedia
    ]);
}
}
