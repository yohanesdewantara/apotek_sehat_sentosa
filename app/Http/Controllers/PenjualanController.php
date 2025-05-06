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
            $stok_obat_fifo = \App\Models\DetailObat::where('id_obat', $id_obat)
                ->where('stok', '>', 0) // Pastikan stok masih tersedia
                ->orderBy('tgl_kadaluarsa', 'asc') // FIFO (First In, First Out)
                ->get();

            foreach ($stok_obat_fifo as $detail) {
                if ($stok_tersisa <= 0)
                    break;

                $ambil = min($detail->stok, $stok_tersisa);
                DetailPenjualan::create([
                    'id_penjualan' => $penjualan->id_penjualan,
                    'id_detailobat' => $detail->id_detailobat,
                    'tgl_penjualan' => $request->tgl_penjualan,
                    'jumlah_terjual' => $ambil,
                    'harga_jual' => $harga_jual,
                    'harga_beli' => $detail->harga_beli,
                ]);

                // Update stok detail_obat
                $detail->stok -= $ambil;
                $detail->save();

                $total_penjualan += $ambil * $harga_jual; // Hitung total penjualan
                $stok_tersisa -= $ambil;
            }

            // Jika stok tidak cukup, beri pesan kesalahan
            if ($stok_tersisa > 0) {
                return back()->withErrors(['stok' => 'Stok tidak cukup untuk obat ID: ' . $id_obat]);
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

        $penjualan = Penjualan::findOrFail($id);
        $penjualan->id_admin = $request->id_admin;
        $penjualan->tgl_penjualan = $request->tgl_penjualan;
        $penjualan->save();

        $detail_ids = $request->input('id_detailjual');
        $jumlahs = $request->input('jumlah_terjual');
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
        $penjualan->delete();

        return redirect()->route('penjualan.index')->with('success', 'Data penjualan berhasil dihapus.');
    }
}
