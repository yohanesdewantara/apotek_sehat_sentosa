<?php


use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\StokopnameController;
use App\Http\Controllers\DashboardController;


// Redirect ke /login saat akses root URL
Route::get('/', function () {
    return redirect()->route('login');
});

// LOGIN / LOGOUT //
Route::middleware('guest')->group(function () {
    Route::get('/login', [PageController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.proses'); // Mengarahkan ke AuthController
});

// HALAMAN YANG BUTUH LOGIN //
Route::middleware('admin.auth')->group(function () {
    Route::get('/home', [DashboardController::class, 'index'])->name('home');

    // Rute untuk halaman data admin
    Route::get('/datauseradmin', [AdminController::class, 'index'])->name('admin.index'); // Tampilkan daftar admin

    // Rute untuk halaman tambah admin baru
    Route::get('/admin/create', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/admin/store', [AdminController::class, 'store'])->name('admin.store'); // Simpan admin baru


    Route::get('/admin/edit/{id}', [AdminController::class, 'edit'])->name('admin.edit'); // Halaman edit admin
    Route::put('/admin/update/{id}', [AdminController::class, 'update'])->name('admin.update'); // Update admin

    Route::delete('/admin/{id_admin}', [AdminController::class, 'destroy'])->name('admin.destroy'); // Hapus admin



    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    // route halaman pembelian

    Route::get('/pembelian/detail/{id_detailbeli}', [PembelianController::class, 'showDetail'])->name('pembelian.detail');


// Update Pembelian
// Route::put('/pembelian/{pembelian}', [PembelianController::class, 'update'])->name('pembelian.update');

    // Resource pembelian
    Route::resource('pembelian', PembelianController::class);
    Route::resource('penjualan', PenjualanController::class);


    Route::get('/penjualan', [PageController::class, 'penjualan']);

    // Tambahkan resource untuk Penjualan
    Route::resource('penjualan', PenjualanController::class);


    // Atau kalau mau detail manual:
    Route::get('/penjualan/detail/{id}', [PenjualanController::class, 'showDetail'])->name('penjualan.detail');

    Route::get('penjualan/{id}/detail', [PenjualanController::class, 'show'])->name('penjualan.show');


    //bagian obat baru
    Route::get('/kelolaobat', [ObatController::class, 'kelolaobat']);
    Route::resource('obat', ObatController::class);
    Route::get('/obat/{id}/detail', [ObatController::class, 'show'])->name('obat.show');
    Route::delete('/obat/{id}', [ObatController::class, 'destroy'])->name('obat.destroy');
    Route::get('/obat/info/{id_obat}', [ObatController::class, 'getObatInfo'])->name('obat.info');

    // Laporan routes
Route::get('/laporan', [App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
Route::get('/laporan/stok_fifo', [App\Http\Controllers\LaporanController::class, 'stokFIFO'])->name('laporan.stok_fifo');
Route::get('/laporan/laba_rugi', [App\Http\Controllers\LaporanController::class, 'labaRugi'])->name('laporan.laba-rugi');


    Route::get('/check-stok/{id_obat}/{jumlah}', [App\Http\Controllers\PenjualanController::class, 'checkStok']);

    // Rute StokOpname - gunakan resource controller
    Route::resource('stokopname', StokopnameController::class);


Route::get('/obat/diskon/{id}', [ObatController::class, 'showDiskon'])->name('obat.diskon');
Route::post('/obat/diskon/{id}', [ObatController::class, 'simpanDiskon'])->name('obat.simpanDiskon');


    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
