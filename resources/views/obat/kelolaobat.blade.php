@extends('layouts.main')
@section('title', 'Kelola Obat')

@section('artikel')
    <div class="d-flex justify-content-between mb-3">
        <div>
            <!-- Filter Form -->
            <form action="{{ route('obat.index') }}" method="GET" class="d-flex">
                <input type="text" name="nama_obat" class="form-control d-inline-block" style="width: 200px;" placeholder="🔍 Filter Nama Obat..." value="{{ request('nama_obat') }}">
                <button type="submit" class="btn btn-secondary" style="margin-left: 10px;">Cari</button>
            </form>
        </div>
        <div>
            <!-- Tambah Obat Button -->
            <a href="{{ route('obat.create') }}" class="btn btn-success"><i class="bi bi-plus-circle"></i> Tambah Obat</a>
        </div>
    </div>

    <!-- Table -->
    <table class="table table-bordered text-center">
        <thead class="thead-light">
            <tr>
                <th>No</th>
                <th>ID Obat</th> <!-- Kolom ID Obat -->
                <th>Nama Obat</th>
                <th>Stok Total</th>
                <th>Jenis Obat</th>
                <th>Nama Rak</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($obats as $index => $obat)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $obat->id_obat }}</td>
                    <td>{{ $obat->nama_obat }}</td>
                    <td>{{ $obat->stok_total }}</td>
                    <td>{{ $obat->jenis_obat }}</td>
                    <td>{{ $obat->rakObat->nama_rak ?? 'Tidak Tersedia' }}</td> <!-- Menampilkan Nama Rak -->
                    <td>
                        <!-- Detail Button -->
                        <a href="{{ route('obat.show', $obat->id_obat) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                        <!-- Edit Button -->
                        <a href="{{ route('obat.edit', $obat->id_obat) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                        <!-- Delete Form -->
                        <form action="{{ route('obat.destroy', $obat->id_obat) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Hapus obat ini?')" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
