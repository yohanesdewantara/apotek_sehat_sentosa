@extends('layouts.main')
<<<<<<< HEAD
@section('title', 'penjualan')
=======

@section('title', 'Penjualan')

>>>>>>> 16007cd (commit pembelian)
@section('artikel')
<div class="d-flex justify-content-between mb-3">
    <div>
        <form action="{{ route('penjualan.index') }}" method="GET" class="d-flex">
<<<<<<< HEAD
            <input type="text" name="search" id="filterInput" class="form-control d-inline-block" style="width: 200px;" placeholder="🔍 Filter Nama Obat..." value="{{ request('search') }}">
=======
            <input type="text" name="admin_name" class="form-control d-inline-block" style="width: 200px;" placeholder="🔍 Filter Nama Admin..." value="{{ request('admin_name') }}">
            <input type="date" name="date_from" class="form-control d-inline-block" style="width: 150px; margin-left: 10px;" placeholder="🔍 Dari Tanggal" value="{{ request('date_from') }}">
            <input type="date" name="date_to" class="form-control d-inline-block" style="width: 150px; margin-left: 10px;" placeholder="🔍 Sampai Tanggal" value="{{ request('date_to') }}">
>>>>>>> 16007cd (commit pembelian)
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
<<<<<<< HEAD
            <th>Nama Admin</th>
            <th>Tanggal Penjualan</th>
            <th>Nama Obat</th>
            <th>Jumlah Terjual</th>
            <th>Harga Jual</th>
            <th>Harga Beli</th>
=======
            <th>ID Penjualan</th>
            <th>Tanggal Penjualan</th>
            <th>Nama Admin</th>
            <th>Total</th>
>>>>>>> 16007cd (commit pembelian)
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
<<<<<<< HEAD
        @foreach($data as $pembelian)
            @foreach($pembelian->detailPembelian as $detail)
                <tr>
                    <td>{{ $no }}</td>
                    <td>{{ \Carbon\Carbon::parse($pembelian->tgl_pembelian)->format('d/m/Y') }}</td>
                    <td>{{ $detail->detailObat->obat->nama_obat ?? 'Nama Obat Tidak Ditemukan' }}</td>
                    <td>{{ $detail->jumlah_beli }}</td>
                    <td>Rp {{ number_format($detail->harga_beli, 0, ',', '.') }}</td>
                    <td>
                        @if($detail->detailObat && $detail->detailObat->obat)
                            Rp {{ number_format($detail->detailObat->obat->harga_jual, 0, ',', '.') }}
                        @else
                            Harga Jual Tidak Ditemukan
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('pembelian.detail', $detail->id_detailbeli) }}" class="btn btn-info btn-sm">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                        <!-- Tombol Edit -->
                        <a href="{{ route('pembelian.edit', $pembelian->id_pembelian) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>

                        <!-- Tombol Hapus -->
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
=======
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
>>>>>>> 16007cd (commit pembelian)
        @endforeach
    </tbody>
</table>

<<<<<<< HEAD
<!-- Filter Nama Obat Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('filterInput').addEventListener('keyup', function() {
        let input = this.value.toLowerCase();
        let rows = document.querySelectorAll('#pembelianTable tbody tr');

        rows.forEach(function(row) {
            let namaObat = row.cells[2].textContent.toLowerCase(); // Kolom Nama Obat
            if (namaObat.includes(input)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>
@endsection

=======
@endsection
>>>>>>> 16007cd (commit pembelian)
