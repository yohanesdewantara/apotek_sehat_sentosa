<?php

namespace App\Http\Controllers;
use App\Models\Obat;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class ObatController extends Controller
{


    public function index(Request $request)
{
    // Mulai query untuk mengambil data obat
    $query = Obat::query();

    // Filter berdasarkan nama obat jika ada
    if ($request->has('nama_obat') && $request->nama_obat != '') {
        $query->where('nama_obat', 'like', '%' . $request->nama_obat . '%');
    }

    // Filter berdasarkan kategori obat jika ada
    if ($request->has('kategori') && $request->kategori != '') {
        $query->where('kategori', $request->kategori);
    }

    // Mengambil data obat dengan eager loading untuk rakObat
    $obats = $query->with('rakObat')->get();

    // Kembalikan view dengan data obat
    return view('obat.kelolaobat', compact('obats'));
}



    public function kelolaobat(Request $request)
{
    // Ambil data obat berdasarkan filter pencarian jika ada
    $query = Obat::query();

    // Filter berdasarkan nama obat jika ada
    if ($request->has('nama_obat')) {
        $query->where('nama_obat', 'like', '%' . $request->nama_obat . '%');
    }

    // Filter berdasarkan kategori obat jika ada
    if ($request->has('kategori')) {
        $query->where('kategori', $request->kategori);
    }

    // Ambil hasilnya
    $obats = $query->get();

    // Kembalikan view dengan data obat
    return view('obat.kelolaobat', compact('obats'));

}




    public function create()
    {
        // TODO: Tampilkan form tambah obat
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: Simpan data obat baru
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    // Mengambil data obat beserta detail dan rakObat-nya
    $obat = Obat::with('rakObat', 'detailObat')->findOrFail($id);

    // Menampilkan halaman detail obat dengan data yang sudah diambil
    return view('obat.detailobat', compact('obat'));
}



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // TODO: Tampilkan form edit obat
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // TODO: Update data obat
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // TODO: Hapus data obat
    }
}
