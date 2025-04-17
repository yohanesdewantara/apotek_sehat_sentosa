<?php
namespace App\Http\Controllers;

use App\Models\Pembelian;
use Illuminate\Http\Request;

class PembelianController extends Controller
{
    public function index()
    {
        $data = Pembelian::with('detailPembelian.detailObat.obat')->get();
        return view('pembelian.pembelian', compact('data'));
    }

    public function create()
    {
        return view('pembelian.createpembelian');
    }

    public function store(Request $request)
    {
        Pembelian::create($request->all());
        return redirect('/pembelian')->with('success', 'Data pembelian berhasil ditambahkan');
    }

    public function edit($id)
{
    $pembelian = Pembelian::with('detailPembelian.detailObat.obat')->findOrFail($id);
    return view('pembelian.editpembelian', compact('pembelian'));
}



    public function update(Request $request, $id)
    {
        $pembelian = Pembelian::findOrFail($id);

        // Validasi inputan jika perlu
        $request->validate([
            'jumlah' => 'required|numeric',
            'harga' => 'required|numeric',
        ]);

        $pembelian->update([
            'jumlah' => $request->jumlah,
            'harga' => $request->harga,
        ]);


        return redirect('/pembelian')->with('success', 'Data berhasil diperbarui');
    }

    public function destroy($id)
    {
        Pembelian::destroy($id);
        return redirect('/pembelian')->with('success', 'Data berhasil dihapus');
    }
}
