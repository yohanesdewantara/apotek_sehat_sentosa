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
        return view('login');
    }

    public function login(Request $request)
{
    $admin = Admin::where('email', $request->email)->first();

    if ($admin && Hash::check($request->password, $admin->password)) {
        Session::put('id_admin', $admin->id_admin);
        Session::put('nama_admin', $admin->nama_admin);
        return redirect('/home');
    } else {
        return redirect('/login')->with('error', 'Email atau Password salah');
    }
}

    public function logout()
    {
        Session::flush();
        return redirect('/login');
    }
}
