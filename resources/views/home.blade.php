@extends('layouts.main')
@section('title', 'Dashboard Apotek Sehat Sentosa')

@section('artikel')
<!-- Custom CSS -->
<style>
    .dashboard-card {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.11);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 20px rgba(0, 0, 0, 0.41);
    }

    .card-header {
        background: linear-gradient(45deg,rgb(5, 185, 14),rgb(6, 138, 30));
        color: white;
        font-weight: 600;
        border-bottom: none;
    }

    .border-left-primary {
        border-left: 4px solid #4e73df !important;
    }

    .border-left-success {
        border-left: 4px solidrgb(0, 88, 51) !important;
    }

    .border-left-info {
        border-left: 4px solid #11cdef !important;
    }

    .border-left-warning {
        border-left: 4px solid #fb6340 !important;
    }

    .stat-icon {
        height: 48px;
        width: 48px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
    }

    .icon-primary {
        background: linear-gradient(310deg, #4e73df, #3a416f);
    }

    .icon-success {
        background: linear-gradient(310deg, #36b37e, #00875a);
    }

    .icon-info {
        background: linear-gradient(310deg, #11cdef, #0097e6);
    }

    .icon-warning {
        background: linear-gradient(310deg, #fb6340, #e03e1d);
    }

    .table thead {
        background-color: #f8f9fa;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(78, 115, 223, 0.05);
    }

    .stats-number {
        font-size: 1.75rem;
        font-weight: 700;
    }

    .stats-text {
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .chart-area {
        height: 350px;
    }

    .chart-pie {
        height: 300px;
    }

    .badge {
        padding: 0.5rem 0.8rem;
        border-radius: 6px;
        font-weight: 500;
    }

    .btn {
        border-radius: 5px;
        font-weight: 500;
        padding: 0.5rem 1rem;
        transition: all 0.2s;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
    }

    .btn-primary {
        background: linear-gradient(310deg, #4e73df, #3a416f);
        border: none;
    }

    .btn-info {
        background: linear-gradient(310deg, #11cdef, #0097e6);
        border: none;
    }

    .bg-gradient-primary {
        background: linear-gradient(310deg, #4e73df, #3a416f);
    }

    .bg-gradient-success {
        background: linear-gradient(310deg, #2dce89, #26a96c);
    }

    .bg-gradient-info {
        background: linear-gradient(310deg, #11cdef, #0097e6);
    }

    .bg-gradient-warning {
        background: linear-gradient(310deg, #fb6340, #e03e1d);
    }

    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.8;
        }
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .action-btn:hover {
        transform: translateY(-3px);
    }

    /* Quick Action Buttons */
    .quick-actions {
        margin-bottom: 1.5rem;
    }

    .quick-action-btn {
        border-radius: 10px;
        padding: 1rem 1.5rem;
        transition: all 0.3s;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        font-weight: 600;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .quick-action-btn i {
        font-size: 1.5rem;
        margin-right: 0.75rem;
    }

    .quick-action-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .btn-penjualan {
        background: linear-gradient(135deg, #36b37e, #00875a);
        color: white;
    }

    .btn-pembelian {
        background: linear-gradient(135deg, #4e73df, #3a416f);
        color: white;
    }

    .btn-obat {
        background: linear-gradient(135deg, #11cdef, #0097e6);
        color: white;
    }
</style>

<div class="container-fluid px-4 py-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Apotek</h1>
        <div class="d-flex">
            <button class="btn btn-primary me-2 shadow-sm d-flex align-items-center">
                <i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::now()->format('d M Y') }}
            </button>
        </div>
    </div>

    <!-- Quick Action Buttons -->


    <!-- Dashboard Cards -->
    <div class="row">
        <!-- Total Obat Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card border-0 h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="stat-icon icon-primary">
                                <i class="bi bi-capsule"></i>
                            </div>
                        </div>
                        <div class="col">
                            <p class="stats-text text-primary mb-0">Total Jenis Obat</p>
                            <h5 class="stats-number text-dark mb-0">{{ $totalObat }}</h5>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-gradient-primary" role="progressbar" style="width: {{ min(100, ($totalObat/100)*100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Stock Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card border-0 h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="stat-icon icon-success">
                                <i class="bi bi-box"></i>
                            </div>
                        </div>
                        <div class="col">
                            <p class="stats-text text-success mb-0">Total Stok Obat</p>
                            <h5 class="stats-number text-dark mb-0">{{ $totalStok }}</h5>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-gradient-success" role="progressbar" style="width: {{ min(100, ($totalStok/1000)*100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Penjualan Bulan Ini Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card border-0 h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="stat-icon icon-info">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                        </div>
                        <div class="col">
                            <p class="stats-text text-info mb-0">Penjualan Bulan Ini</p>
                            <h5 class="stats-number text-dark mb-0">Rp {{ number_format($penjualanBulanIni, 0, ',', '.') }}</h5>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-gradient-info" role="progressbar" style="width: 75%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Expired Soon Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card border-0 h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="stat-icon icon-warning {{ $kadaluarsaCount > 0 ? 'animate-pulse' : '' }}">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                        </div>
                        <div class="col">
                            <p class="stats-text text-warning mb-0">Hampir Kadaluarsa</p>
                            <h5 class="stats-number text-dark mb-0">{{ $kadaluarsaCount }}</h5>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-gradient-warning" role="progressbar" style="width: {{ min(100, ($kadaluarsaCount/10)*100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

     <div class="row quick-actions">
        <div class="col-md-4 mb-3">
            <a href="{{ route('penjualan.create') }}" class="btn quick-action-btn btn-penjualan w-100">
                <i class="bi bi-cart-plus"></i>
                <span>Buat Penjualan Baru</span>
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="{{ route('pembelian.create') }}" class="btn quick-action-btn btn-pembelian w-100">
                <i class="bi bi-bag-plus"></i>
                <span>Tambah Pembelian Baru</span>
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="{{ route('obat.index') }}" class="btn quick-action-btn btn-obat w-100">
                <i class="bi bi-capsule"></i>
                <span>Kelola Obat</span>
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Sales Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card dashboard-card shadow-sm mb-4">
                <div class="card-header py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-white">Grafik Penjualan (6 Bulan Terakhir)</h6>

                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card dashboard-card shadow-sm mb-4">
                <div class="card-header py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-white">Distribusi Jenis Obat</h6>

                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-2">
                        <canvas id="pieChart"></canvas>
                    </div>
                    <div class="mt-3">
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            @foreach($jenisObatDistribution as $jenis => $count)
                                <div class="badge bg-light text-dark d-flex align-items-center px-2 py-1">
                                    <span class="me-1" style="display: block; width: 10px; height: 10px; border-radius: 50%; background-color: #{{ substr(md5($jenis), 0, 6) }}"></span>
                                    {{ $jenis }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Low Stock Table -->
        <div class="col-xl-6 col-lg-6">
            <div class="card dashboard-card shadow-sm mb-4">
                <div class="card-header py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-white">
                            <i class="bi bi-arrow-down-circle me-2"></i>Stok Obat Menipis (< 10)
                        </h6>
                        <span class="badge bg-danger">{{ count($lowStockItems) }} Item</span>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($lowStockItems) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Nama Obat</th>
                                        <th class="text-center">Stok</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lowStockItems as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar me-2 bg-light rounded p-2">
                                                    <i class="bi bi-capsule text-primary"></i>
                                                </div>
                                                <div>
                                                    <p class="fw-bold mb-0">{{ $item->nama_obat }}</p>
                                                    <small class="text-muted">{{ $item->jenis_obat }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-danger">{{ $item->stok_total }}</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('obat.show', $item->id_obat) }}" class="btn btn-sm btn-info action-btn">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                            <p class="mb-0 mt-2">Semua stok dalam jumlah yang cukup!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Expires Soon Table -->
        <div class="col-xl-6 col-lg-6">
            <div class="card dashboard-card shadow-sm mb-4">
                <div class="card-header py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-white">
                            <i class="bi bi-clock-history me-2"></i>Obat Hampir Kadaluarsa (< 30 hari)
                        </h6>
                        <span class="badge bg-warning">{{ count($nearExpiry) }} Item</span>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($nearExpiry) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Nama Obat</th>
                                        <th>Tgl. Kadaluarsa</th>
                                        <th class="text-center">Sisa Hari</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($nearExpiry as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar me-2 bg-light rounded p-2">
                                                    <i class="bi bi-capsule text-warning"></i>
                                                </div>
                                                <span>{{ $item->obat->nama_obat }}</span>
                                            </div>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($item->tgl_kadaluarsa)->format('d M Y') }}</td>
                                        <td class="text-center">
                                            @php
                                                $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($item->tgl_kadaluarsa), false);
                                            @endphp
                                            <span class="badge {{ $daysLeft < 10 ? 'bg-danger' : 'bg-warning' }}">
                                                {{ $daysLeft }} hari
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('obat.diskon', $item->id_detailobat) }}" class="btn btn-sm btn-success action-btn">
                                                <i class="bi bi-tag"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                            <p class="mb-0 mt-2">Tidak ada obat yang hampir kadaluarsa!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Diskon Obat Table -->
    <div class="row">
        <div class="col-12">
            <div class="card dashboard-card shadow-sm mb-4">
                <div class="card-header py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-white">
                            <i class="bi bi-tag me-2"></i>Diskon Obat
                        </h6>
                        <a href="{{ route('obat.index') }}" class="btn btn-sm btn-outline-light">
                            Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Obat</th>
                                    <th>Tgl. Kadaluarsa</th>
                                    <th class="text-center">Stok</th>
                                    <th class="text-center">Diskon</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($detailObats->take(5) as $detail)
                                <tr>
                                    <td>{{ $detail->id_detailobat }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2 bg-light rounded p-2">
                                                <i class="bi bi-capsule text-primary"></i>
                                            </div>
                                            <span>{{ $detail->obat->nama_obat }}</span>
                                        </div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($detail->tgl_kadaluarsa)->format('d M Y') }}</td>
                                    <td class="text-center">{{ $detail->stok }}</td>
                                    <td class="text-center">
                                        @if($detail->disc > 0)
                                            <span class="badge bg-success">{{ $detail->disc }}%</span>
                                        @else
                                            <span class="badge bg-secondary">0%</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('obat.diskon', $detail->id_detailobat) }}" class="btn btn-sm btn-success action-btn">
                                            <i class="bi bi-tag"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Include Bootstrap 5 JS (untuk dropdown) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Custom Chart.js Defaults
        Chart.defaults.font.family = "'Poppins', 'Helvetica', 'Arial', sans-serif";
        Chart.defaults.color = '#718096';

        // Sales Chart
        var salesCtx = document.getElementById('salesChart');
        var salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($salesChartData['labels']) !!},
                datasets: [{
                    label: 'Penjualan',
                    data: {!! json_encode($salesChartData['data']) !!},
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    pointRadius: 4,
                    pointBackgroundColor: 'rgba(255, 255, 255, 1)',
                    pointBorderColor: 'rgba(78, 115, 223, 1)',
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointHoverBorderColor: 'rgba(255, 255, 255, 1)',
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#fff',
                        titleColor: '#1e293b',
                        bodyColor: '#334155',
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        padding: 12,
                        boxPadding: 6,
                        usePointStyle: true,
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            padding: 10,
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            padding: 10
                        }
                    }
                }
            }
        });

        // Pie Chart
        var pieCtx = document.getElementById('pieChart');
        var pieChart = new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_keys($jenisObatDistribution)) !!},
                datasets: [{
                    data: {!! json_encode(array_values($jenisObatDistribution)) !!},
                    backgroundColor: {!! json_encode(array_map(function($jenis) {
                        return '#' . substr(md5($jenis), 0, 6);
                    }, array_keys($jenisObatDistribution))) !!},
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 4
                }]
            },
            options: {
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#fff',
                        titleColor: '#1e293b',
                        bodyColor: '#334155',
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        padding: 12,
                        boxPadding: 6,
                        usePointStyle: true
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });

        // Auto refresh dashboard data every 60 seconds
        setInterval(function() {
            // Reload data in real-world would fetch from server via AJAX
            // Here we just simulate a refresh effect

            // Add a subtle flash effect to the cards
            const cards = document.querySelectorAll('.dashboard-card');
            cards.forEach(card => {
                card.style.transition = 'background-color 0.5s';
                card.style.backgroundColor = 'rgba(250, 250, 250, 0.6)';

                setTimeout(() => {
                    card.style.backgroundColor = '';
                }, 500);
            });

            // You could add AJAX refresh here if needed
        }, 60000);
    });
</script>
@endsection
