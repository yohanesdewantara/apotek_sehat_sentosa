<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Admin;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login'); // Tampilkan halaman login
    }

    public function login(Request $request)
    {
        // Cek apakah admin dengan email ada di database
        $admin = Admin::where('email', $request->email)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            // Set session jika login berhasil
            Session::put('id_admin', $admin->id_admin);
            Session::put('nama_admin', $admin->nama_admin);
            return redirect('/home');
        }

        // Jika login gagal
        return back()->with('error', 'Email atau Password salah');
    }

    public function logout()
    {
        // Menghapus semua session ketika logout
        Session::flush();
        return redirect('/login');
    }
}
