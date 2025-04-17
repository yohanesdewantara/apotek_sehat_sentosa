@extends('layouts.main')
@section('title', 'kelolaobat')

@section('artikel')
    <div class="d-flex justify-content-between mb-3">
        <div>
            <input type="text" class="form-control d-inline-block" style="width: 200px;" placeholder="🔍 Cari obat...">
        </div>
        <div>
            <a href="#" class="btn btn-success"><i class="bi bi-plus-circle"></i> Tambah Obat</a>
        </div>
    </div>

    <table class="table table-bordered text-center">
        <thead class="thead-light">
            <tr>
                <th>No</th>
                <th>Nama Obat</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php
                $dataObat = [
                    ['nama' => 'Paracetamol', 'kategori' => 'Analgesik', 'stok' => 120, 'harga' => 1500],
                    ['nama' => 'Amoxicillin', 'kategori' => 'Antibiotik', 'stok' => 80, 'harga' => 2500],
                    ['nama' => 'Vitamin C', 'kategori' => 'Suplemen', 'stok' => 100, 'harga' => 2000],
                    ['nama' => 'Promag', 'kategori' => 'Antasida', 'stok' => 50, 'harga' => 1800],
                    ['nama' => 'Antalgin', 'kategori' => 'Analgesik', 'stok' => 60, 'harga' => 1700],
                ];
            @endphp

            @foreach ($dataObat as $index => $obat)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $obat['nama'] }}</td>
                    <td>{{ $obat['kategori'] }}</td>
                    <td>{{ $obat['stok'] }}</td>
                    <td>Rp {{ number_format($obat['harga'], 0, ',', '.') }}</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i> Edit</a>
                        <a href="#" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Hapus</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection


