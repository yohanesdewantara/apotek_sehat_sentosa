@extends('layouts.main')
@section('title', 'Detail Stok Opname')

@section('artikel')
<div class="card">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">Detail Stok Opname</h5>
    </div>
    <div class="card-body">

        @php
            $detailObat = $stokopname->detailObat ?? null;
            $obat = $detailObat->obat ?? null;
            $detailStok = $stokopname->detailStokOpname->first();
        @endphp

        <!-- ID Detail StokOpname -->
        <div class="mb-3">
            <label>ID Detail StokOpname</label>
            <input type="text" class="form-control" value="{{ $detailStok->id_detailopname ?? '-' }}" readonly>
        </div>

        <!-- Nama Obat -->
        <div class="mb-3">
            <label>Nama Obat</label>
            <input type="text" class="form-control" value="{{ $obat->nama_obat ?? '-' }}" readonly>
        </div>

        <!-- Nama Admin -->
        <div class="mb-3">
            <label>Nama Admin</label>
            <input type="text" class="form-control" value="{{ $stokopname->admin->nama_admin ?? '-' }}" readonly>
        </div>

        <!-- Tanggal Masuk -->
        <div class="mb-3">
            <label>Tanggal Masuk</label>
            <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($stokopname->tanggal)->format('d-m-Y') }}" readonly>
        </div>

        <!-- Stok Kadaluarsa -->
        <div class="mb-3">
            <label>Stok Kadaluarsa</label>
            <input type="text" class="form-control" value="{{ $detailStok->stok_kadaluarsa ?? '-' }}" readonly>
        </div>

        <!-- Keterangan -->
        <div class="mb-3">
            <label>Keterangan</label>
            <input type="text" class="form-control" value="{{ $detailStok->keterangan ?? '-' }}" readonly>
        </div>

        <!-- ID Detail Obat -->
        <div class="mb-3">
            <label>ID Detail Obat</label>
            <input type="text" class="form-control" value="{{ $detailObat->id_detailobat ?? '-' }}" readonly>
        </div>

        <!-- Jenis Obat -->
        <div class="mb-3">
            <label>Jenis Obat</label>
            <input type="text" class="form-control" value="{{ $obat->jenis_obat ?? '-' }}" readonly>
        </div>

        <!-- ID Opname -->
    <div class="mb-3">
        <label>ID Opname</label>
        <input type="text" class="form-control" value="{{ $stokopname->id_opname ?? '-' }}" readonly>
    </div>

        <!-- Kembali Button -->
        <a href="{{ route('stokopname.index') }}" class="btn btn-secondary mt-3">
            <i class="bi bi-arrow-left-circle"></i> Kembali
        </a>

    </div>
</div>
@endsection
