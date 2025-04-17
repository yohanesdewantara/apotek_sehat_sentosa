<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return view("datauseradmin");
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
        // Pindahkan logout ke controller AuthController
        return redirect('/');
    }

//     public function createpembelian()
// {
//     return view('createpembelian');
// }
}
