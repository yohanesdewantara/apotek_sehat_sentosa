<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Obat;
use App\Models\DetailObat;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {

        $totalObat = Obat::count();
        $totalStok = Obat::sum('stok_total');

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $penjualanBulanIni = Penjualan::whereMonth('tgl_penjualan', $currentMonth)
            ->whereYear('tgl_penjualan', $currentYear)
            ->sum('total');

        $thirtyDaysLater = Carbon::now()->addDays(30)->toDateString();
        $today = Carbon::now()->toDateString();
        $nearExpiry = DetailObat::where('stok', '>', 0)
            ->whereBetween('tgl_kadaluarsa', [$today, $thirtyDaysLater])
            ->orderBy('tgl_kadaluarsa')
            ->with('obat')
            ->take(10)
            ->get();

        $kadaluarsaCount = DetailObat::where('stok', '>', 0)
            ->where('tgl_kadaluarsa', '<=', $thirtyDaysLater)
            ->where('tgl_kadaluarsa', '>=', $today)
            ->count();

        $lowStockItems = Obat::where('stok_total', '<', 10)
            ->orderBy('stok_total')
            ->take(10)
            ->get();

        $salesChartData = $this->getSalesChartData();

        $jenisObatDistribution = $this->getJenisObatDistribution();

        $detailObats = DetailObat::with('obat')->get();

        return view('home', compact(
            'totalObat',
            'totalStok',
            'penjualanBulanIni',
            'kadaluarsaCount',
            'nearExpiry',
            'lowStockItems',
            'salesChartData',
            'jenisObatDistribution',
            'detailObats'
        ));
    }

    private function getSalesChartData()
    {
        $labels = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->translatedFormat('M Y');
            $labels[] = $monthName;


            $monthlySales = Penjualan::whereMonth('tgl_penjualan', $month->month)
                ->whereYear('tgl_penjualan', $month->year)
                ->sum('total');

            $data[] = $monthlySales;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getJenisObatDistribution()
    {
        $distribution = Obat::select('jenis_obat', DB::raw('count(*) as total'))
            ->groupBy('jenis_obat')
            ->orderByDesc('total')
            ->pluck('total', 'jenis_obat')
            ->toArray();

        if (count($distribution) > 5) {
            $topCategories = array_slice($distribution, 0, 5, true);
            $others = array_sum(array_slice($distribution, 5, null, true));
            $topCategories['Lainnya'] = $others;
            return $topCategories;
        }

        return $distribution;
    }
}
