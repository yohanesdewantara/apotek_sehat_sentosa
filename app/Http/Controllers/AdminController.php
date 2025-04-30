<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // menampilkan daftar admin
    public function index(Request $request)
    {
        $search = $request->input('search');
        // mengambil data admin berdasarkan pencarian
        $admin = Admin::query()
            ->when($search, function ($query, $search) {

                return $query->where('nama_admin', 'like', '%' . $search . '%')
                             ->orWhere('email', 'like', '%' . $search . '%');
            })
            ->get();


        return view('datauseradmin.datauseradmin', compact('admin'));
    }

    // tambah  baru
    public function create()
    {
        return view('datauseradmin.createadmin');
    }

    // menyimpan admin baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_admin' => 'required|string|max:255',
            'email' => 'required|email|unique:admin,email',
            'password' => 'required|min:8|confirmed',
        ]);

        Admin::create([
            'nama_admin' => $request->nama_admin,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.index')->with('success', 'Admin baru berhasil dibuat.');
    }

    // menampilkan edit admin
    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        return view('datauseradmin.editadmin', compact('admin'));
    }

    // menyimpan perubahan admin
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_admin' => 'required|string|max:255',
            'email' => 'required|email|unique:admin,email,' . $id . ',id_admin',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $admin = Admin::findOrFail($id);
        $admin->nama_admin = $request->nama_admin;
        $admin->email = $request->email;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return redirect()->route('admin.index')->with('success', 'Data admin berhasil diperbarui.');
    }

    // menghapus admin
    public function destroy($id_admin)
    {
        $admin = Admin::findOrFail($id_admin);
        $admin->delete();

        return redirect()->route('admin.index')->with('success', 'Data admin berhasil dihapus.');
    }
}
