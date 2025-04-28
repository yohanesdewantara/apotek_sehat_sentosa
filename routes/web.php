<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\AdminController; // Tambahkan controller AdminController

// Redirect ke /login saat akses root URL
Route::get('/', function () {
    return redirect()->route('login');
});

// === LOGIN / LOGOUT === //
Route::middleware('guest')->group(function () {
    Route::get('/login', [PageController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.proses'); // Mengarahkan ke AuthController
});

// === HALAMAN YANG BUTUH LOGIN === //
Route::middleware('admin.auth')->group(function () {
    Route::get('/home', [PageController::class, 'home'])->name('home');

    // Rute untuk halaman data admin
    Route::get('/datauseradmin', [AdminController::class, 'index'])->name('admin.index'); // Tampilkan daftar admin

    // Rute untuk halaman tambah admin baru
    Route::get('/admin/create', [AdminController::class, 'create'])->name('admin.create'); // Halaman form tambah admin
    Route::post('/admin/store', [AdminController::class, 'store'])->name('admin.store'); // Simpan admin baru

    // === Tambahan untuk Edit dan Update Admin ===
    Route::get('/admin/edit/{id}', [AdminController::class, 'edit'])->name('admin.edit'); // Halaman edit admin
    Route::put('/admin/update/{id}', [AdminController::class, 'update'])->name('admin.update'); // Update admin

    // === Tambahan untuk Hapus Admin ===
    Route::delete('/admin/{id_admin}', [AdminController::class, 'destroy'])->name('admin.destroy'); // Hapus admin


// Route::get('/pembelian/detail/{id_detailobat}', [PembelianController::class, 'show'])->name('pembelian.detail');


Route::get('/pembelian/detail/{id_detailbeli}', [PembelianController::class, 'showDetail'])->name('pembelian.detail');


// Resource pembelian
Route::resource('pembelian', PembelianController::class);



    // Route untuk halaman lain yang ada
    Route::get('/penjualan', [PageController::class, 'penjualan']);
    Route::get('/kelolaobat', [PageController::class, 'kelolaobat']);
    Route::get('/stokopname', [PageController::class, 'stokopname']);
    Route::get('/laporan', [PageController::class, 'laporan']);
    // Route::resource('pembelian', PembelianController::class);

    // Rute untuk logout
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
