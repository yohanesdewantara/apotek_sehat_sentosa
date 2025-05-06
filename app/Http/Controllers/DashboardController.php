<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Tanggal hari ini
        $today = Carbon::today();
        $lastMonth = Carbon::today()->subMonth();
        $nextMonth = Carbon::today()->addMonth();

        // Total penjualan bulan ini
        $totalPenjualan = DB::table('penjualan')
            ->whereMonth('tgl_penjualan', $today->month)
            ->whereYear('tgl_penjualan', $today->year)
            ->sum('total');

        // Total penjualan bulan lalu untuk perbandingan
        $totalPenjualanBulanLalu = DB::table('penjualan')
            ->whereMonth('tgl_penjualan', $lastMonth->month)
            ->whereYear('tgl_penjualan', $lastMonth->year)
            ->sum('total');

        // Hitung persentase kenaikan/penurunan
        $persentasePenjualan = $totalPenjualanBulanLalu > 0
            ? round((($totalPenjualan - $totalPenjualanBulanLalu) / $totalPenjualanBulanLalu) * 100, 2)
            : 100;

        // Total obat
        $totalObat = DB::table('obat')->count();

        // Obat baru (ditambahkan dalam 30 hari terakhir)
        $obatBaru = DB::table('detail_obat')
            ->whereDate('tgl_kadaluarsa', '>', Carbon::now())
            ->distinct('id_obat')
            ->count('id_obat');

        // Total transaksi bulan ini
        $totalTransaksi = DB::table('penjualan')
            ->whereMonth('tgl_penjualan', $today->month)
            ->whereYear('tgl_penjualan', $today->year)
            ->count();

        // Transaksi hari ini
        $transaksiHariIni = DB::table('penjualan')
            ->whereDate('tgl_penjualan', $today)
            ->count();

        // Total obat yang akan kadaluarsa dalam 3 bulan
        $totalKadaluarsa = DB::table('detail_obat')
            ->whereDate('tgl_kadaluarsa', '>', Carbon::now())
            ->whereDate('tgl_kadaluarsa', '<=', Carbon::now()->addMonths(3))
            ->where('stok', '>', 0)
            ->count();

        // Obat yang akan kadaluarsa dalam 30 hari
        $kadaluarsaSegera = DB::table('detail_obat')
            ->whereDate('tgl_kadaluarsa', '>', Carbon::now())
            ->whereDate('tgl_kadaluarsa', '<=', Carbon::now()->addDays(30))
            ->where('stok', '>', 0)
            ->count();

        // Top 5 produk terlaris
        $topProducts = DB::table('detail_penjualan')
            ->join('detail_obat', 'detail_penjualan.id_detailobat', '=', 'detail_obat.id_detailobat')
            ->join('obat', 'detail_obat.id_obat', '=', 'obat.id_obat')
            ->select('obat.id_obat', 'obat.nama_obat', DB::raw('SUM(detail_penjualan.jumlah_terjual) as total_terjual'))
            ->groupBy('obat.id_obat', 'obat.nama_obat')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        // Daftar obat yang akan kadaluarsa
        $expiringProducts = DB::table('detail_obat')
            ->join('obat', 'detail_obat.id_obat', '=', 'obat.id_obat')
            ->select('obat.nama_obat', 'detail_obat.stok', 'detail_obat.tgl_kadaluarsa')
            ->whereDate('tgl_kadaluarsa', '>', Carbon::now())
            ->whereDate('tgl_kadaluarsa', '<=', Carbon::now()->addDays(60))
            ->where('stok', '>', 0)
            ->orderBy('tgl_kadaluarsa')
            ->limit(5)
            ->get();

        // Transaksi terbaru
        $recentTransactions = DB::table('penjualan')
            ->leftJoin('admin', 'penjualan.id_admin', '=', 'admin.id_admin')
            ->select('penjualan.id_penjualan', 'penjualan.tgl_penjualan', 'penjualan.total', 'admin.nama_admin')
            ->orderByDesc('penjualan.tgl_penjualan')
            ->limit(5)
            ->get();

        return view('home', compact(
            'totalPenjualan',
            'persentasePenjualan',
            'totalObat',
            'obatBaru',
            'totalTransaksi',
            'transaksiHariIni',
            'totalKadaluarsa',
            'kadaluarsaSegera',
            'topProducts',
            'expiringProducts',
            'recentTransactions'
        ));
    }
}
