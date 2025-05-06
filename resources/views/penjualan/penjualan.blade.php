@extends('layouts.main')

@section('title', 'Penjualan')

@section('artikel')
<div class="d-flex justify-content-between mb-3">
    <div>
        <form action="{{ route('penjualan.index') }}" method="GET" class="d-flex">
            <input type="text" name="admin_name" class="form-control d-inline-block" style="width: 200px;" placeholder="🔍 Filter Nama Admin..." value="{{ request('admin_name') }}">
            <input type="date" name="date_from" class="form-control d-inline-block" style="width: 150px; margin-left: 10px;" placeholder="🔍 Dari Tanggal" value="{{ request('date_from') }}">
            <input type="date" name="date_to" class="form-control d-inline-block" style="width: 150px; margin-left: 10px;" placeholder="🔍 Sampai Tanggal" value="{{ request('date_to') }}">
            <button type="submit" class="btn btn-secondary" style="margin-left: 10px;">Cari</button>
        </form>
    </div>
    <div>
        <a href="{{ route('penjualan.create') }}" class="btn btn-success mb-3">
            <i class="bi bi-plus-circle"></i> Tambah
        </a>
    </div>
</div>
<table class="table table-bordered text-center" id="penjualanTable">
    <thead class="thead-light">
        <tr>
            <th>No</th>
            <th>ID Penjualan</th>
            <th>Tanggal Penjualan</th>
            <th>Nama Admin</th>
            <th>Total</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @foreach($penjualans as $penjualan)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $penjualan->id_penjualan }}</td>
                <td>{{ \Carbon\Carbon::parse($penjualan->tgl_penjualan)->format('d/m/Y') }}</td>
                <td>{{ $penjualan->admin->nama_admin ?? 'Admin Tidak Ditemukan' }}</td>
                <td>Rp {{ number_format($penjualan->total, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('penjualan.show', $penjualan->id_penjualan) }}" class="btn btn-info btn-sm">
                        <i class="bi bi-eye"></i> Detail
                    </a>

                    <a href="{{ route('penjualan.edit', $penjualan->id_penjualan) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>

                    <form action="{{ route('penjualan.destroy', $penjualan->id_penjualan) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" class="btn btn-danger btn-sm">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection
