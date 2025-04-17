@extends('layouts.main')
@section('title', 'dashboard')

@section('artikel')
    <div class="text-center mb-4">
        <h1 class="display-4 text-success font-weight-bold">Dashboard</h1>
        <p class="lead text-muted">Statistik penting untuk pengelolaan Apotek Sehat Sentosa</p>
    </div>

    <div class="row">
        <!-- Obat Paling Laris -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <i class="bi bi-graph-up-arrow text-success" style="font-size: 2.5rem;"></i>
                    <h5 class="mt-3">Obat Paling Laris</h5>
                    <p class="text-muted">Contoh : Paracetamol, Amoxicillin, Vitamin C</p>
                    {{-- Nanti bisa diganti dengan data dari controller --}}
                </div>
            </div>
        </div>

        <!-- Obat Hampir Kadaluwarsa -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 2.5rem;"></i>
                    <h5 class="mt-3">Obat Hampir Kadaluwarsa</h5>
                    <p class="text-muted">Contoh : Antalgin - 20/04/2025, Promag - 25/04/2025</p>
                    {{-- Nanti bisa diganti dengan data dari controller --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Info tambahan -->
    <div class="alert alert-success mt-4 shadow-sm" role="alert">
        <i class="bi bi-info-circle-fill mr-2"></i>
        Dashboard ini menampilkan statistik penjualan dan pengingat obat kadaluwarsa. Data akan diperbarui secara berkala.
    </div>
@endsection
