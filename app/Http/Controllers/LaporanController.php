<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Obat;
use App\Models\DetailObat;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Pembelian;
use App\Models\DetailPembelian;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.indexlaporan');
    }

    public function stokFIFO()
    {
        // Query for regular items
        $fifoStok = DB::table('detail_obat')
            ->join('obat', 'detail_obat.id_obat', '=', 'obat.id_obat')
            ->leftJoin('rak_obat', 'obat.id_rak', '=', 'rak_obat.id_rak')
            ->leftJoin('detail_stokopname', 'detail_obat.id_detailobat', '=', 'detail_stokopname.id_detailobat')
            ->select(
                'obat.id_obat',
                'obat.nama_obat',
                'obat.stok_total',
                'obat.jenis_obat',
                'detail_obat.id_detailobat',
                'detail_obat.tgl_kadaluarsa',
                'detail_obat.stok',
                'detail_obat.harga_beli',
                'obat.harga_jual',
                'rak_obat.nama_rak',
                'detail_stokopname.stok_kadaluarsa',
                'detail_stokopname.id_detailopname'
            )
            ->where('detail_obat.stok', '>', 0)
            ->orWhereNotNull('detail_stokopname.id_detailopname')
            ->orderBy('obat.nama_obat', 'asc')
            ->orderBy('detail_obat.tgl_kadaluarsa', 'asc')
            ->get();

        // Group by obat
        $groupedStok = $fifoStok->groupBy('id_obat');

        return view('laporan.stok_fifo', compact('groupedStok'));
    }

    public function labaRugi(Request $request)
    {
        // Default to current month if no date range selected
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $period = $request->input('period', 'monthly'); // daily, monthly, yearly

        // Format untuk display
        $carbon_start = Carbon::parse($startDate);
        $carbon_end = Carbon::parse($endDate);

        // Debug: Log the date range and period
        \Log::info("Date Range: $startDate to $endDate, Period: $period");

        // Determine the SQL for grouping based on the period
        switch ($period) {
            case 'daily':
                $groupByColumn = DB::raw("DATE(detail_penjualan.tgl_penjualan)");
                $selectPeriod = DB::raw("DATE(detail_penjualan.tgl_penjualan) as periode");
                break;
            case 'monthly':
                // Use DAY(1) to ensure unique monthly periods are generated
                $groupByColumn = DB::raw("DATE_FORMAT(detail_penjualan.tgl_penjualan, '%Y-%m')");
                $selectPeriod = DB::raw("DATE_FORMAT(detail_penjualan.tgl_penjualan, '%Y-%m') as periode");
                break;
            case 'yearly':
                $groupByColumn = DB::raw("YEAR(detail_penjualan.tgl_penjualan)");
                $selectPeriod = DB::raw("YEAR(detail_penjualan.tgl_penjualan) as periode");
                break;
            default:
                $groupByColumn = DB::raw("DATE_FORMAT(detail_penjualan.tgl_penjualan, '%Y-%m')");
                $selectPeriod = DB::raw("DATE_FORMAT(detail_penjualan.tgl_penjualan, '%Y-%m') as periode");
        }

        // Get profit/loss data with explicit DB::raw for clearer SQL generation
        $profits = DB::table('detail_penjualan')
            ->join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
            ->join('detail_obat', 'detail_penjualan.id_detailobat', '=', 'detail_obat.id_detailobat')
            ->join('obat', 'detail_obat.id_obat', '=', 'obat.id_obat')
            ->select([
                $selectPeriod,
                DB::raw("SUM(detail_penjualan.jumlah_terjual * detail_penjualan.harga_jual) as total_penjualan"),
                DB::raw("SUM(detail_penjualan.jumlah_terjual * detail_penjualan.harga_beli) as total_modal"),
                DB::raw("SUM(detail_penjualan.jumlah_terjual * (detail_penjualan.harga_jual - detail_penjualan.harga_beli)) as keuntungan")
            ])
            ->whereBetween('detail_penjualan.tgl_penjualan', [$startDate, $endDate])
            ->groupBy($groupByColumn)
            ->orderBy('periode')
            ->get();

        // Debug: Log the resulting periods
        $debugPeriods = $profits->pluck('periode')->toArray();
        \Log::info("Generated periods:", $debugPeriods);

        // Get sales details
        $salesDetails = DB::table('detail_penjualan')
            ->join('penjualan', 'detail_penjualan.id_penjualan', '=', 'penjualan.id_penjualan')
            ->join('detail_obat', 'detail_penjualan.id_detailobat', '=', 'detail_obat.id_detailobat')
            ->join('obat', 'detail_obat.id_obat', '=', 'obat.id_obat')
            ->select(
                'penjualan.id_penjualan',
                'detail_penjualan.tgl_penjualan',
                'obat.nama_obat',
                'detail_penjualan.jumlah_terjual',
                'detail_penjualan.harga_jual',
                'detail_penjualan.harga_beli',
                DB::raw('(detail_penjualan.harga_jual - detail_penjualan.harga_beli) * detail_penjualan.jumlah_terjual as profit')
            )
            ->whereBetween('detail_penjualan.tgl_penjualan', [$startDate, $endDate])
            ->orderBy('detail_penjualan.tgl_penjualan', 'desc')
            ->get();

        // Calculate summary
        $summary = [
            'total_pendapatan' => $profits->sum('total_penjualan'),
            'total_modal' => $profits->sum('total_modal'),
            'total_keuntungan' => $profits->sum('keuntungan'),
        ];

        return view('laporan.laba_rugi', compact(
            'profits',
            'salesDetails',
            'summary',
            'startDate',
            'endDate',
            'period',
            'carbon_start',
            'carbon_end'
        ));
    }
}
