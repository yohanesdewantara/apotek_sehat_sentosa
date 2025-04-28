<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin; // Tambahkan di paling atas

class PageController extends Controller
{
    public function login()
    {
        return view("login");
    }

    public function home()
    {
        return view("home");
    }

    public function datauseradmin()
{
    $admin = Admin::all(); // Ambil semua data dari tabel admin
    return view("datauseradmin", compact('admin')); // Kirim ke view
}

    public function pembelian()
    {
        return view("pembelian");
    }

    public function penjualan()
    {
        return view('penjualan.penjualan');
    }

    public function kelolaobat()
    {
        return view("kelolaobat");
    }

    public function stokopname()
    {
        return view("stokopname");
    }

    public function laporan()
    {
        return view("laporan");
    }

    public function logout()
    {

        return redirect('/');
    }


}
