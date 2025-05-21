@extends('layouts.main')

@section('title', 'Laporan')

@section('artikel')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="mb-4 text-success">Pilih Jenis Laporan</h4>
            </div>
        </div>

        <div class="row">
            <!-- Laporan Stok FIFO -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title text-success">
                            <i class="bi bi-box-seam me-2"></i> Laporan Stok FIFO
                        </h5>
                        <p class="card-text">Laporan ini berisi stok barang yang dimiliki apotik beserta urutan tanggal
                            kadaluwarsa setiap produknya. </p>
                        <hr>
                        <a href="{{ route('laporan.stok_fifo') }}" class="btn btn-success">
                            <i class="bi bi-file-earmark-spreadsheet me-2"></i>Lihat Laporan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Laporan Laba Rugi -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title text-success">
                            <i class="bi bi-graph-up-arrow me-2"></i> Laporan Laba Rugi
                        </h5>
                        <p class="card-text">Laporan ini diperuntukan untuk mengetahui keuntungan toko dalam harian,
                            bulanan, tahunan.</p>
                        <hr>
                        <a href="{{ route('laporan.laba-rugi') }}" class="btn btn-success">
                            <i class="bi bi-currency-dollar me-2"></i>Lihat Laporan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <strong>Catatan:</strong> Semua laporan dapat diekspor ke format PDF atau Excel untuk keperluan
                    dokumentasi.
                </div>
            </div>
        </div>
    </div>
@endsection
