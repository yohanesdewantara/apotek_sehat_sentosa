<?php

namespace App\Http\Controllers;
use App\Models\Penjualan;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    //
    public function index()
    {
        $data = Penjualan::with('detailPembelian.detailObat.obat')->get();
        return view('penjualan.penjualan', compact('data'));
    }
}
