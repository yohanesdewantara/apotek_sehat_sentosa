<?php

namespace App\Http\Controllers;
use App\Models\Penjualan;
<<<<<<< HEAD
=======
use App\Models\DetailPenjualan;
use App\Models\Admin;
use App\Models\Obat;
>>>>>>> 16007cd (commit pembelian)
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
<<<<<<< HEAD
    //
    public function index()
    {
        $data = Penjualan::with('detailPembelian.detailObat.obat')->get();
        return view('penjualan.penjualan', compact('data'));
=======
    public function index()
    {
        // Ambil semua penjualan beserta admin dan detail_penjualan
        $penjualans = Penjualan::with(['admin', 'detailPenjualan'])->get();

        // Mengirimkan variabel penjualans ke view
        return view('penjualan.penjualan', compact('penjualans'));
    }


    public function create()
{
    // Mendapatkan ID penjualan terakhir dan menambahkannya 1
    $lastPenjualan = Penjualan::orderBy('id_penjualan', 'desc')->first();
    $newPenjualanId = $lastPenjualan ? $lastPenjualan->id_penjualan + 1 : 1;

    // Mendapatkan ID Obat terakhir dan menambahkannya 1
    $lastObat = Obat::orderBy('id_obat', 'desc')->first();
    $newObatId = $lastObat ? $lastObat->id_obat + 1 : 1;

    // Mengambil data admin dan obat untuk inputan manual
    $admins = Admin::all();
    $obats = Obat::all();

    // Mengirim variabel $newPenjualanId, $newObatId, $admins, dan $obats ke view
    return view('penjualan.createpenjualan', compact('newPenjualanId', 'newObatId', 'admins', 'obats'));
}



    public function store(Request $request)
    {
        // Untuk menyimpan data penjualan baru ke database
        // sementara kosong dulu
        return redirect()->route('penjualan.index')->with('success', 'Data penjualan berhasil disimpan.');
    }

    public function show($id)
{
    // Ambil data penjualan beserta detail dan adminnya
    $penjualan = Penjualan::with(['admin', 'detailPenjualan.detailObat.obat'])->findOrFail($id);

    // Pass data penjualan ke view
    return view('penjualan.detailpenjualan', compact('penjualan'));
}

    public function edit($id)
    {
        // Untuk tampilkan form edit penjualan
        return view('penjualan_edit'); // Buat view baru kalau mau edit
    }

    public function update(Request $request, $id)
    {
        // Untuk update data penjualan di database
        return redirect()->route('penjualan.index')->with('success', 'Data penjualan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Untuk hapus data penjualan dari database
        return redirect()->route('penjualan.index')->with('success', 'Data penjualan berhasil dihapus.');
>>>>>>> 16007cd (commit pembelian)
    }
}
