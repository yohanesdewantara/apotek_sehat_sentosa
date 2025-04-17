<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PembelianController;

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
    Route::get('/datauseradmin', [PageController::class, 'datauseradmin']);
    // Route::get('/pembelian', [PageController::class, 'pembelian']);
    Route::get('/penjualan', [PageController::class, 'penjualan']);
    Route::get('/kelolaobat', [PageController::class, 'kelolaobat']);
    Route::get('/stokopname', [PageController::class, 'stokopname']);
    Route::get('/laporan', [PageController::class, 'laporan']);
    Route::resource('pembelian', PembelianController::class);
    Route::get('/logout', [PageController::class, 'logout'])->name('logout');





});
