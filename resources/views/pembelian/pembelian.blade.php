@extends('layouts.main')

@section('title', 'Pembelian')

@section('artikel')
<div class="d-flex justify-content-between mb-3">
    <div>
        <form action="{{ route('pembelian.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" id="filterInput" class="form-control d-inline-block" style="width: 200px;" placeholder="🔍 Filter Nama Obat..." value="{{ request('search') }}">
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
            <th>Tanggal Pembelian</th>
            <th>Nama Obat</th>
            <th>Jumlah Beli (Qty)</th>
            <th>Harga Beli</th>
            <th>Harga Jual</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
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
        @endforeach
    </tbody>
</table>

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
