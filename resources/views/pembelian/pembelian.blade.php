
@extends('layouts.main')

@section('title', 'Pembelian')

@section('artikel')
<div class="d-flex justify-content-between mb-3">
    <div>
        <form action="{{ route('pembelian.index') }}" method="GET" class="d-flex">
            <input type="text" name="admin_name" class="form-control d-inline-block" style="width: 200px;" placeholder="🔍 Filter Nama Admin..." value="{{ request('admin_name') }}">
            <input type="date" name="date_from" class="form-control d-inline-block" style="width: 150px; margin-left: 10px;" placeholder="🔍 Dari Tanggal" value="{{ request('date_from') }}">
            <input type="date" name="date_to" class="form-control d-inline-block" style="width: 150px; margin-left: 10px;" placeholder="🔍 Sampai Tanggal" value="{{ request('date_to') }}">
            <button type="submit" class="btn btn-secondary" style="margin-left: 10px;">Cari</button>
        </form>
    </div>
    <div>
        <a href="{{ route('pembelian.create') }}" class="btn btn-success mb-3">
            <i class="bi bi-plus-circle"></i> Tambah
        </a>
    </div>
</div>

<table class="table table-bordered text-center" id="pembelianTable">
    <thead class="thead-light">
        <tr>
            <th>No</th>
            <th>ID Pembelian</th>
            <th>Tanggal Pembelian</th>
            <th>Nama Admin</th>
            <th>Total Pembelian</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @foreach($data as $pembelian)
            <tr>
                <td>{{ $no }}</td>
                <td>{{ $pembelian->id_pembelian }}</td>
                <td>{{ \Carbon\Carbon::parse($pembelian->tgl_pembelian)->format('d/m/Y') }}</td>
                <td>{{ $pembelian->admin->nama_admin ?? 'Admin Tidak Ditemukan' }}</td>

                <td>Rp {{ number_format($pembelian->total, 0, ',', '.') }}</td>

                <td>
                    <a href="{{ route('pembelian.detail', $pembelian->id_pembelian) }}" class="btn btn-info btn-sm">
                        <i class="bi bi-eye"></i> Detail
                    </a>

                    <a href="{{ route('pembelian.edit', $pembelian->id_pembelian) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>

                    <form action="{{ route('pembelian.destroy', $pembelian->id_pembelian) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" class="btn btn-danger btn-sm">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @php $no++; @endphp
        @endforeach
    </tbody>
</table>

@endsection
