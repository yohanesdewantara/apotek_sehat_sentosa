<?php

namespace App\Http\Controllers;

use App\Models\DetailPembelian;
use App\Models\Pembelian;
use App\Models\DetailPenjualan;
use App\Models\DetailObat;
use App\Models\Admin;
use App\Models\Obat;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PembelianController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter dari URL
        $date_from = $request->get('date_from'); // Tanggal Awal
        $date_to = $request->get('date_to'); // Tanggal Akhir
        $admin_name = $request->get('admin_name'); // Nama Admin

        $data = Pembelian::with('admin') // Mengambil data pembelian dengan relasi admin
            ->when($admin_name, function ($query, $admin_name) {
                return $query->whereHas('admin', function ($q) use ($admin_name) {
                    $q->where('nama_admin', 'like', '%' . $admin_name . '%');
                });
            })
            ->when($date_from && $date_to, function ($query) use ($date_from, $date_to) {
                return $query->whereBetween('tgl_pembelian', [$date_from, $date_to]);
            })
            ->when($date_from && !$date_to, function ($query) use ($date_from) {
                return $query->where('tgl_pembelian', '>=', $date_from);
            })
            ->when(!$date_from && $date_to, function ($query) use ($date_to) {
                return $query->where('tgl_pembelian', '<=', $date_to);
            })
            ->get();

        return view('pembelian.pembelian', compact('data'));
    }


    public function create()
    {
        // Ambil data terakhir
        $lastPembelian = Pembelian::orderBy('id_pembelian', 'desc')->first();

        // Tentukan ID selanjutnya
        $nextId = $lastPembelian ? $lastPembelian->id_pembelian + 1 : 1;

        $admins = Admin::all();
        $obats = Obat::all();

        return view('pembelian.createpembelian', compact('nextId', 'admins', 'obats'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'tgl_pembelian' => 'required|date',
            'id_admin' => 'required|exists:admin,id_admin',

            'details.*.tgl_kadaluarsa' => 'required|date|after_or_equal:today',
            'jumlah_beli' => 'required|array',
            'harga_beli' => 'required|array',
        ]);


        DB::beginTransaction();

        try {
            // Buat pembelian
            $pembelian = Pembelian::create([
                'id_pembelian' => $request->id_pembelian,
                'id_admin' => $request->id_admin,
                'tgl_pembelian' => $request->tgl_pembelian,
                'total' => 0,
            ]);

            $totalHarga = 0;

            foreach ($request->obat_id as $index => $obat_id) {
                if ($obat_id === 'new') {
                    // Buat obat baru
                    $obat = Obat::create([
                        'id_rak' => 1,
                        'nama_obat' => $request->nama_obat_baru[$index],
                        'jenis_obat' => $request->jenis_obat_baru[$index],
                        'keterangan_obat' => $request->keterangan_obat_baru[$index] ?? '',
                        'stok_total' => $request->jumlah_beli[$index],
                        'harga_beli' => $request->harga_beli[$index],
                        'harga_jual' => $request->harga_jual[$index],
                    ]);
                } else {
                    // Update obat lama
                    $obat = Obat::findOrFail($obat_id);
                    $obat->stok_total += $request->jumlah_beli[$index];
                    $obat->save();
                }

                // Detail obat
                $tglKadaluarsa = $request->tgl_kadaluarsa[$index];

                $detailObat = DetailObat::create([
                    'id_obat' => $obat->id_obat,
                    'stok' => $request->jumlah_beli[$index],
                    'tgl_kadaluarsa' => $tglKadaluarsa,
                ]);

                // Detail pembelian
                DetailPembelian::create([
                    'id_pembelian' => $pembelian->id_pembelian,
                    'id_detailobat' => $detailObat->id_detailobat,
                    'jumlah_beli' => $request->jumlah_beli[$index],
                    'harga_beli' => $request->harga_beli[$index],
                    'harga_jual' => $request->harga_jual[$index],
                    'tgl_pembelian' => $request->tgl_pembelian,
                    'tgl_kadaluarsa' => $tglKadaluarsa,
                ]);

                $totalHarga += $request->jumlah_beli[$index] * $request->harga_beli[$index];
            }

            // Update total harga
            $pembelian->update(['total' => $totalHarga]);

            DB::commit();
            return redirect()->route('pembelian.index')->with('success', 'Data pembelian berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan pembelian: ' . $e->getMessage());
        }
    }




    public function showDetail($id_detailbeli)
    {
        $pembelian = Pembelian::with('admin', 'detailPembelian.detailObat.obat')->findOrFail($id_detailbeli);

        return view('pembelian.detailpembelian', [
            'pembelian' => $pembelian,
            'details' => $pembelian->detailPembelian,  // Kirim data detail pembelian
            'id_detailbeli' => $id_detailbeli,  // Kirim juga id_detailbeli ke view
        ]);
    }



    public function edit($id)
    {
        // $pembelian = Pembelian::with('detailPembelian.detailObat.obat')->findOrFail($id);
        $pembelian = Pembelian::with(['admin', 'detailPembelian.detailObat.obat'])->findOrFail($id);
        $admins = Admin::all();
        $obats = Obat::all();

        return view('pembelian.editpembelian', compact('pembelian', 'admins', 'obats'));
    }
    public function update(Request $request, $id)
    {
        $pembelian = Pembelian::findOrFail($id); // Cari pembelian berdasarkan ID

        // Validasi input
        $request->validate([
            'tgl_pembelian' => 'required|date',
            'id_admin' => 'required|exists:admin,id_admin',
            'id_detailbeli' => 'required|array',
            'jumlah_beli' => 'required|array',
            'tgl_kadaluarsa' => 'required|array',
            'tgl_kadaluarsa.*' => 'required|date',
        ]);

        // Update pembelian
        $pembelian->tgl_pembelian = $request->tgl_pembelian;
        $pembelian->id_admin = $request->id_admin;
        $pembelian->save();

        // Update detail pembelian
        $totalHarga = 0;
        if (
            count($request->id_detailbeli) === count($request->jumlah_beli) &&
            count($request->id_detailbeli) === count($request->tgl_kadaluarsa)
        ) {
            foreach ($request->id_detailbeli as $key => $id_detail) {
                $detail = DetailPembelian::find($id_detail);
                if ($detail) {
                    $detail->jumlah_beli = $request->jumlah_beli[$key];
                    $detail->tgl_kadaluarsa = $request->tgl_kadaluarsa[$key];
                    $detail->save();

                    // Hitung total harga pembelian
                    $totalHarga += $detail->jumlah_beli * $detail->harga_beli;
                }
            }
        } else {
            return back()->withErrors(['msg' => 'Jumlah data detail pembelian tidak konsisten.']);
        }

        // Update total pembelian
        $pembelian->update(['total' => $totalHarga]);

        return redirect()->route('pembelian.index')->with('success', 'Data pembelian berhasil diperbarui.');
    }


    public function destroy($id)
{
    $pembelian = Pembelian::with('detailPembelian.detailObat')->findOrFail($id);

    foreach ($pembelian->detailPembelian as $detailPembelian) {
        $detailObat = $detailPembelian->detailObat;

        // Hapus detail pembelian
        $detailPembelian->delete();

        // Hapus detail obat jika tidak terlibat penjualan
        if ($detailObat) {
            // Cek apakah detail_obat ini terhubung ke penjualan
            $adaPenjualan = \App\Models\DetailPenjualan::where('id_detailobat', $detailObat->id_detailobat)->exists();

            if ($adaPenjualan) {
                return back()->with('error', 'Data tidak bisa dihapus karena sudah terjual.');
            }

            $obat = $detailObat->obat;
            if ($obat) {
                $obat->stok_total -= $detailPembelian->jumlah_beli;
                $obat->save();
            }

            $detailObat->delete(); // ← hanya dijalankan jika tidak ada penjualan
        }
    }

    // Hapus pembelian
    $pembelian->delete();

    return redirect()->route('pembelian.index')->with('success', 'Data pembelian berhasil dihapus.');
}


}
