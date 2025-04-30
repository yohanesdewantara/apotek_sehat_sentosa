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
    $admin = Admin::all();
    return view("datauseradmin", compact('admin'));
}

    public function pembelian()
    {
        return view("pembelian");
    }

    public function penjualan()
    {
        return view("penjualan");
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
