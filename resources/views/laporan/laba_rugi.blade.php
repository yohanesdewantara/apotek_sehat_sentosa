@extends('layouts.main')

@section('title', 'Laporan Laba Rugi')

@section('artikel')
    <div class="container-fluid">
        <!-- Header Apotek -->
        <div class="text-center mb-3 print-only">
            <h2 class="mb-1">APOTEK SEHAT SENTOSA</h2>
            <p class="mb-0">Jl. Prof Yohanes No. 123, Yogyakarta</p>
            <p>Telp: 0831 3869 4411 | Email: apoteksehatsentosa@gmail.com</p>
            <hr class="my-2">
        </div>

        <div class="mb-3 d-flex justify-content-between align-items-center no-print">
            <a href="{{ route('laporan.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <div>
                <button class="btn btn-sm btn-success" onclick="printLaporanLabaRugi()">
                    <i class="bi bi-printer"></i> Cetak
                </button>
                <button class="btn btn-sm btn-primary" id="exportExcel">
                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                </button>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="card mb-4 no-print">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-funnel-fill me-2"></i>Filter Laporan
            </div>
            <div class="card-body">
                <form action="{{ route('laporan.laba-rugi') }}" method="get" class="row g-3">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
                    </div>
                    <div class="col-md-4">
                        <label for="period" class="form-label">Periode</label>
                        <select class="form-select" id="period" name="period">
                            <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>Harian</option>
                            <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                            <option value="yearly" {{ $period == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-2"></i>Tampilkan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Main Report Card -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white print-header">
                <div class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-graph-up-arrow me-2"></i>Laporan Laba Rugi</span>
                    <span>Periode: {{ $carbon_start->format('d M Y') }} - {{ $carbon_end->format('d M Y') }}</span>
                </div>
            </div>
            <div class="card-body">
                <!-- Summary Cards -->
                <div class="row mb-4 print-summary">
                    <div class="col-md-4">
                          <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="card-title text-danger">Total Pembelian</h6>
                                <h3 class="mb-0">Rp {{ number_format($summary['total_modal'], 0, ',', '.') }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="card-title text-primary">Total Penjualan</h6>
                                <h3 class="mb-0">Rp {{ number_format($summary['total_pendapatan'], 0, ',', '.') }}</h3>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="card-title text-success">Total Keuntungan</h6>
                                <h3 class="mb-0">Rp {{ number_format($summary['total_keuntungan'], 0, ',', '.') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profit Chart -->
                <!-- <div class="mb-4 no-print">
                    <canvas id="profitChart" height="250"></canvas>
                </div> -->

                <!-- Period Breakdown Table -->
                <div class="table-responsive print-table">
                    <h5 class="print-only text-center">Laporan Laba Rugi</h5>
                    <p class="print-only text-center">Periode: {{ $carbon_start->format('d M Y') }} - {{ $carbon_end->format('d M Y') }}</p>
                    <table class="table table-bordered table-striped" id="labaRugiTable">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Periode</th>

                                <th>Total Modal</th>
                                <th>Total Penjualan</th>
                                <th>Keuntungan</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @forelse($profits as $item)
                                @php
                                    $percentage = $item->total_penjualan > 0 ?
                                        round(($item->keuntungan / $item->total_penjualan) * 100, 2) : 0;

                                    // Format periode display sesuai period type
                                    $periodeDisplay = $item->periode;
                                    if ($period == 'monthly') {
                                        $parts = explode('-', $item->periode);
                                        if (count($parts) == 2) {
                                            $month = intval($parts[1]);
                                            $year = intval($parts[0]);
                                            $date = Carbon\Carbon::createFromDate($year, $month, 1);
                                            $periodeDisplay = $date->locale('id')->isoFormat('MMMM YYYY');
                                        }
                                    } elseif ($period == 'daily') {
                                        $periodeDisplay = \Carbon\Carbon::parse($item->periode)->format('d M Y');
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $periodeDisplay }}</td>

                                    <td>Rp {{ number_format($item->total_modal, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->total_penjualan, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->keuntungan, 0, ',', '.') }}</td>
                                    <td>{{ $percentage }}%</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data untuk periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="bg-light">
                                <td colspan="2"><strong>Total</strong></td>

                                <td><strong>Rp {{ number_format($summary['total_modal'], 0, ',', '.') }}</strong></td>
                                 <td><strong>Rp {{ number_format($summary['total_pendapatan'], 0, ',', '.') }}</strong></td>
                                <td><strong>Rp {{ number_format($summary['total_keuntungan'], 0, ',', '.') }}</strong></td>
                                <td>
                                    <strong>
                                        {{ $summary['total_pendapatan'] > 0 ?
        round(($summary['total_keuntungan'] / $summary['total_pendapatan']) * 100, 2) : 0 }}%
                                    </strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Detail Penjualan Card -->
        <div class="card mb-4 print-detail-table">
            <div class="card-header bg-info text-white">
                <i class="bi bi-receipt me-2"></i>Detail Penjualan
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <h5 class="print-only text-center mt-4">Detail Penjualan</h5>
                    <table class="table table-sm table-striped table-hover" id="detailPenjualanTable">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Nama Obat</th>
                                <th>Qty</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th>Keuntungan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($salesDetails as $detail)
                                <tr>
                                    <td>{{ $detail->id_penjualan }}</td>
                                    <td>{{ \Carbon\Carbon::parse($detail->tgl_penjualan)->format('d/m/Y') }}</td>
                                    <td>{{ $detail->nama_obat }}</td>
                                    <td>{{ $detail->jumlah_terjual }}</td>
                                    <td>Rp {{ number_format($detail->harga_beli, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($detail->harga_jual, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($detail->profit, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada detail penjualan untuk periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="bg-light fw-bold">
                                <td colspan="4" class="text-end"><strong>Total</strong></td>
                                <td>
                                    @php
                                        $totalJual = 0;
                                        $totalBeli = 0;
                                        $totalProfit = 0;

                                        foreach ($salesDetails as $detail) {
                                            $totalJual += $detail->harga_jual * $detail->jumlah_terjual;
                                            $totalBeli += $detail->harga_beli * $detail->jumlah_terjual;
                                            $totalProfit += $detail->profit;
                                        }
                                    @endphp
                                    <strong>Rp {{ number_format($totalBeli, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    <strong>Rp {{ number_format($totalJual, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    <strong>Rp {{ number_format($totalProfit, 0, ',', '.') }}</strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <!-- Tanda tangan/Footer yang muncul di cetak -->
        <div class="row mt-5 print-only">
            <div class="col-4"></div>
            <div class="col-8 text-center">
                <p class="mb-5">{{ date('d F Y') }}</p>
                <p class="mt-5"><strong>Admin Apotek</strong></p>
                <p class="mt-4">_________________________</p>
            </div>
        </div>
    </div>

    <!-- Print-only table styling -->
    <style>
        /* Sembunyikan elemen untuk tampilan non-cetak */
        .print-only {
            display: none;
        }

        @media print {
            /* Reset the page */
            @page {
                size: A4;
                margin: 1.5cm;
            }

            /* Tampilkan elemen print-only */
            .print-only {
                display: block !important;
            }

            /* Hide everything by default */
            body * {
                visibility: hidden;
            }

            /* Override untuk menampilkan hanya yang kita inginkan */
            .container-fluid,
            .print-only,
            .print-only *,
            .print-table,
            .print-table *,
            .print-detail-table,
            .print-detail-table *,
            .print-summary,
            .print-summary * {
                visibility: visible !important;
            }

            /* Menyembunyikan elemen yang tidak perlu dicetak */
            .no-print,
            .card-header:not(.print-header),
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate {
                display: none !important;
            }

            /* Layout cetak yang lebih rapi */
            body {
                margin: 0;
                padding: 0;
                line-height: 1.4;
                font-family: "Arial", "Helvetica", sans-serif;
                color: #000;
                background: #fff;
            }

            /* Mengatur container untuk layout cetak */
            .container-fluid {
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            /* Menyesuaikan tabel untuk cetak */
            table {
                width: 100% !important;
                margin-bottom: 20px !important;
                border-collapse: collapse !important;
                page-break-inside: auto !important;
            }

            /* Pengaturan untuk baris tabel */
            tr {
                page-break-inside: avoid !important;
                page-break-after: auto !important;
            }

            /* Pengaturan untuk sel tabel */
            th, td {
                padding: 8px !important;
                border: 1px solid #ddd !important;
                font-size: 12pt !important;
            }

            /* Header tabel */
            thead {
                display: table-header-group !important;
            }

            /* Footer tabel */
            tfoot {
                display: table-footer-group !important;
            }

            /* Memperbaiki lebar kolom untuk tabel laba rugi */
            #labaRugiTable th:nth-child(1),
            #labaRugiTable td:nth-child(1) {
                width: 5% !important;
            }

            #labaRugiTable th:nth-child(2),
            #labaRugiTable td:nth-child(2) {
                width: 20% !important;
            }

            #labaRugiTable th:nth-child(3),
            #labaRugiTable td:nth-child(3),
            #labaRugiTable th:nth-child(4),
            #labaRugiTable td:nth-child(4),
            #labaRugiTable th:nth-child(5),
            #labaRugiTable td:nth-child(5) {
                width: 20% !important;
            }

            #labaRugiTable th:nth-child(6),
            #labaRugiTable td:nth-child(6) {
                width: 15% !important;
            }

            /* Pengaturan untuk ringkasan total */
            .print-summary {
                margin-bottom: 20px !important;
                page-break-inside: avoid !important;
                display: flex !important;
                flex-direction: row !important;
                justify-content: space-between !important;
            }

            .print-summary .col-md-4 {
                width: 32% !important;
                float: left !important;
            }

            .print-summary .card {
                border: 1px solid #ddd !important;
                margin-bottom: 15px !important;
            }

            .print-summary .card-body {
                padding: 15px !important;
            }

            /* Memastikan tabel detail tidak terpotong halaman */
            .print-detail-table {
                page-break-before: always !important;
            }

            /* Fix untuk card styling saat cetak */
            .card {
                border: none !important;
                box-shadow: none !important;
                margin-bottom: 20px !important;
            }

            .card-header {
                background-color: #f8f9fa !important;
                color: #000 !important;
                border-bottom: 2px solid #ddd !important;
                padding: 10px !important;
                font-weight: bold !important;
            }

            .card-body {
                padding: 0 !important;
            }

            /* Menyesuaikan warna teks untuk semua elemen */
            * {
                color: black !important;
                background-color: transparent !important;
            }

            /* Memastikan tampilan header yang rapi */
            .text-center {
                text-align: center !important;
            }

            h2, h5 {
                margin-top: 0 !important;
                margin-bottom: 10px !important;
            }

            hr {
                border: 1px solid #000 !important;
                margin: 10px 0 !important;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize DataTables
            new DataTable('#labaRugiTable', {
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
                }
            });

            new DataTable('#detailPenjualanTable', {
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
                },
                pageLength: 5,
                order: [[1, 'desc']]
            });

            // Export to Excel functionality
            document.getElementById('exportExcel').addEventListener('click', function () {
                const table2excel = new Table2Excel();
                table2excel.export(document.getElementById('labaRugiTable'), 'Laporan_Laba_Rugi_' + new Date().toISOString().slice(0, 10));
            });

            // Prepare chart data
            const profitData = {
                labels: [
                    @foreach($profits as $item)
                                @if($period == 'monthly')
                                    @php
                                        $parts = explode('-', $item->periode);
                                        if (count($parts) == 2) {
                                            $month = intval($parts[1]);
                                            $year = intval($parts[0]);
                                            $date = Carbon\Carbon::createFromDate($year, $month, 1);
                                            echo "'" . $date->locale('id')->isoFormat('MMM YYYY') . "',";
                                        } else {
                                            echo "'" . $item->periode . "',";
                                        }
                                    @endphp
                                @elseif($period == 'daily')
                                    '{{ \Carbon\Carbon::parse($item->periode)->format('d M') }}',
                                @else
                            '{{ $item->periode }}',
                        @endif
                    @endforeach
                ],
                datasets: [
                    {
                        label: 'Pendapatan',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgb(54, 162, 235)',
                        data: [
                            @foreach($profits as $item)
                                {{ $item->total_penjualan }},
                            @endforeach
                        ],
                    },
                    {
                        label: 'Modal',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgb(255, 99, 132)',
                        data: [
                            @foreach($profits as $item)
                                {{ $item->total_modal }},
                            @endforeach
                        ],
                    },
                    {
                        label: 'Keuntungan',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgb(75, 192, 192)',
                        data: [
                            @foreach($profits as $item)
                                {{ $item->keuntungan }},
                            @endforeach
                        ],
                    }
                ]
            };

            // Create chart
            const ctx = document.getElementById('profitChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: profitData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Grafik Laba Rugi'
                        },
                        tooltip: {
                            callbacks: {
                                 label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', {
                                            style: 'currency',
                                            currency: 'IDR',
                                            minimumFractionDigits: 0,
                                            maximumFractionDigits: 0
                                        }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                 callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        });

        // Custom print function
        function printLaporanLabaRugi() {
            window.print();
        }
    </script>
@endsection
