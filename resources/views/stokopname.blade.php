@extends('layouts.main')
@section('title', 'stokopname')

@section('artikel')
    <div class="d-flex justify-content-between mb-3">
        <div>
            <input type="text" class="form-control d-inline-block" style="width: 200px;" placeholder="🔍 Cari stok...">
        </div>
        <div>
            <a href="#" class="btn btn-success"><i class="bi bi-journal-check"></i> Proses Stok Opname</a>
        </div>
    </div>

    <table class="table table-bordered text-center">
        <thead class="thead-light">
            <tr>
                <th>No</th>
                <th>Nama Obat</th>
                <th>Stok Sistem</th>
                <th>Stok Fisik</th>
                <th>Selisih</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $dataStok = [
                    ['nama' => 'Paracetamol', 'sistem' => 120, 'fisik' => 118, 'keterangan' => 'Kurang 2'],
                    ['nama' => 'Amoxicillin', 'sistem' => 80, 'fisik' => 80, 'keterangan' => 'Sesuai'],
                    ['nama' => 'Vitamin C', 'sistem' => 100, 'fisik' => 95, 'keterangan' => 'Kurang 5'],
                    ['nama' => 'Promag', 'sistem' => 50, 'fisik' => 50, 'keterangan' => 'Sesuai'],
                    ['nama' => 'Antalgin', 'sistem' => 60, 'fisik' => 58, 'keterangan' => 'Kurang 2'],
                ];
            @endphp

            @foreach ($dataStok as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item['nama'] }}</td>
                    <td>{{ $item['sistem'] }}</td>
                    <td>{{ $item['fisik'] }}</td>
                    <td>{{ $item['fisik'] - $item['sistem'] }}</td>
                    <td>{{ $item['keterangan'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

