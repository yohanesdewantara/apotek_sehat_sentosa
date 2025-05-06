@extends('layouts.main')
@section('title', 'Dashboard Apotek Sehat Sentosa')

@section('artikel')
    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold text-primary">Dashboard</h1>
                <p class="text-muted">Selamat datang di Sistem Manajemen Apotek Sehat Sentosa</p>
            </div>
            <div class="d-flex align-items-center">
                <div class="dropdown me-3">
                    <button class="btn btn-light border-0 shadow-sm dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-filter me-1"></i> Filter
                    </button>
                    <ul class="dropdown-menu shadow" aria-labelledby="filterDropdown">
                        <li><a class="dropdown-item" href="#">Hari Ini</a></li>
                        <li><a class="dropdown-item" href="#">Minggu Ini</a></li>
                        <li><a class="dropdown-item" href="#">Bulan Ini</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Kustom...</a></li>
                    </ul>
                </div>
                <button class="btn btn-primary">
                    <i class="bi bi-download me-1"></i> Ekspor
                </button>
            </div>
        </div>

        <!-- Statistik Utama -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 bg-gradient">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted fw-normal">Total Penjualan</h6>
                                <h3 class="fw-bold mb-1">Rp {{ number_format($totalPenjualan ?? 0, 0, ',', '.') }}</h3>
                                <div class="small text-success">
                                    <i class="bi bi-arrow-up me-1"></i>
                                    {{ $persentasePenjualan ?? 0 }}% dari bulan lalu
                                </div>
                            </div>
                            <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-graph-up-arrow text-primary fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted fw-normal">Total Produk</h6>
                                <h3 class="fw-bold mb-1">{{ $totalObat ?? 0 }}</h3>
                                <div class="small text-primary">
                                    <i class="bi bi-plus-circle me-1"></i>
                                    {{ $obatBaru ?? 0 }} produk baru
                                </div>
                            </div>
                            <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-capsule text-success fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted fw-normal">Transaksi</h6>
                                <h3 class="fw-bold mb-1">{{ $totalTransaksi ?? 0 }}</h3>
                                <div class="small text-info">
                                    <i class="bi bi-clock-history me-1"></i>
                                    {{ $transaksiHariIni ?? 0 }} transaksi hari ini
                                </div>
                            </div>
                            <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-cart-check text-info fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted fw-normal">Kadaluarsa</h6>
                                <h3 class="fw-bold mb-1">{{ $totalKadaluarsa ?? 0 }}</h3>
                                <div class="small text-danger">
                                    <i class="bi bi-exclamation-circle me-1"></i>
                                    {{ $kadaluarsaSegera ?? 0 }} dalam 30 hari
                                </div>
                            </div>
                            <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-calendar2-x text-warning fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik & Tabel -->
        <div class="row g-4 mb-4">
            <!-- Grafik Penjualan -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Tren Penjualan</h5>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary">Minggu</button>
                            <button type="button" class="btn btn-sm btn-primary">Bulan</button>
                            <button type="button" class="btn btn-sm btn-outline-primary">Tahun</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div style="height: 300px;" class="d-flex align-items-center justify-content-center">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Produk Terlaris -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="mb-0">Produk Terlaris</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Nama Obat</th>
                                        <th scope="col" class="text-end">Terjual</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topProducts ?? [] as $product)
                                    <tr>
                                        <td>{{ $product->nama_obat }}</td>
                                        <td class="text-end">{{ $product->total_terjual }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td>Paracetamol</td>
                                        <td class="text-end">457</td>
                                    </tr>
                                    <tr>
                                        <td>Amoxicillin</td>
                                        <td class="text-end">328</td>
                                    </tr>
                                    <tr>
                                        <td>Vitamin C</td>
                                        <td class="text-end">215</td>
                                    </tr>
                                    <tr>
                                        <td>Antasida</td>
                                        <td class="text-end">189</td>
                                    </tr>
                                    <tr>
                                        <td>CTM</td>
                                        <td class="text-end">154</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Baris Terakhir -->
        <div class="row g-4">
            <!-- Obat Hampir Kadaluarsa -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                            Obat Hampir Kadaluarsa
                        </h5>
                        <a href="#" class="btn btn-sm btn-link text-decoration-none">Lihat Semua</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Nama Obat</th>
                                        <th scope="col">Stok</th>
                                        <th scope="col">Kadaluarsa</th>
                                        <th scope="col" class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($expiringProducts ?? [] as $product)
                                    <tr>
                                        <td>{{ $product->nama_obat }}</td>
                                        <td>{{ $product->stok }}</td>
                                        <td>{{ \Carbon\Carbon::parse($product->tgl_kadaluarsa)->format('d M Y') }}</td>
                                        <td class="text-center">
                                            @php
                                                $days = \Carbon\Carbon::parse($product->tgl_kadaluarsa)->diffInDays(now());
                                                if($days <= 7) {
                                                    $badge = 'danger';
                                                } elseif($days <= 30) {
                                                    $badge = 'warning';
                                                } else {
                                                    $badge = 'success';
                                                }
                                            @endphp
                                            <span class="badge bg-{{ $badge }}">{{ $days }} hari</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td>Amoxicillin</td>
                                        <td>24</td>
                                        <td>20 Mei 2025</td>
                                        <td class="text-center"><span class="badge bg-danger">14 hari</span></td>
                                    </tr>
                                    <tr>
                                        <td>Promag</td>
                                        <td>36</td>
                                        <td>05 Jun 2025</td>
                                        <td class="text-center"><span class="badge bg-warning">30 hari</span></td>
                                    </tr>
                                    <tr>
                                        <td>Panadol</td>
                                        <td>18</td>
                                        <td>12 Jun 2025</td>
                                        <td class="text-center"><span class="badge bg-warning">37 hari</span></td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaksi Terbaru -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 d-flex align-items-center">
                            <i class="bi bi-receipt text-primary me-2"></i>
                            Transaksi Terbaru
                        </h5>
                        <a href="#" class="btn btn-sm btn-link text-decoration-none">Lihat Semua</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Tanggal</th>
                                        <th scope="col">Admin</th>
                                        <th scope="col" class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentTransactions ?? [] as $transaction)
                                    <tr>
                                        <td>#{{ $transaction->id_penjualan }}</td>
                                        <td>{{ \Carbon\Carbon::parse($transaction->tgl_penjualan)->format('d M Y') }}</td>
                                        <td>{{ $transaction->nama_admin }}</td>
                                        <td class="text-end">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td>#22</td>
                                        <td>05 Mei 2025</td>
                                        <td>Admin Utama</td>
                                        <td class="text-end">Rp 750.000</td>
                                    </tr>
                                    <tr>
                                        <td>#20</td>
                                        <td>02 Mei 2025</td>
                                        <td>Admin Kedua</td>
                                        <td class="text-end">Rp 3.000</td>
                                    </tr>
                                    <tr>
                                        <td>#19</td>
                                        <td>01 Mei 2025</td>
                                        <td>Admin Utama</td>
                                        <td class="text-end">Rp 3.000</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data untuk grafik penjualan
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const salesData = {
                labels: months,
                datasets: [{
                    label: 'Penjualan',
                    data: [65000000, 59000000, 80000000, 81000000, 56000000, 70000000, 90000000, 100000000, 95000000, 85000000, 110000000, 120000000],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Keuntungan',
                    data: [30000000, 25000000, 35000000, 40000000, 28000000, 32000000, 43000000, 50000000, 47000000, 40000000, 55000000, 60000000],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            };

            // Konfigurasi grafik
            const config = {
                type: 'line',
                data: salesData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + (value / 1000000) + ' jt';
                                }
                            }
                        }
                    },
                    plugins: {
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
                    }
                }
            };

            // Inisialisasi grafik
            const salesChart = new Chart(
                document.getElementById('salesChart'),
                config
            );
        });
    </script>
    @endpush
@endsection
