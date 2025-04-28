@extends('layouts.main')
@section('title', 'Laporan')

@section('artikel')


<div class="d-flex align-items-center mb-4" style="gap: 10px;">
    <label for="jenisLaporan" class="form-label mb-0">Pilih Laporan :</label>
    <select id="jenisLaporan" class="form-select w-auto">
        <option value="labarugi">Laba Rugi</option>
        <option value="stok">Stok</option>
    </select>
</div>


<div class="d-flex justify-content-between align-items-center flex-wrap mb-4 gap-3">
    <div class="d-flex flex-wrap align-items-center gap-2">
        <input type="date" class="form-control" style="width: 200px;">
        <span class="mx-2">-</span>
        <input type="date" class="form-control" style="width: 200px;">
        <button class="btn btn-primary" style="margin-left: 20px;">Tampilkan</button>
    </div>
    <div>
        <input type="text" class="form-control" placeholder="🔍 Search" style="width: 200px;">
    </div>
</div>


<div id="laporanLabaRugi" class="mb-5">
    <h5 class="mb-3">Laporan Laba Rugi</h5>
    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Penjualan</th>
                <th>Pembelian</th>
                <th>Laba/Rugi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>2025-04-10</td>
                <td>Rp. 1.000.000</td>
                <td>Rp. 700.000</td>
                <td>Rp. 300.000</td>
            </tr>

        </tbody>
    </table>
</div>


<div id="laporanStok" style="display: none;" class="mb-5">
    <h5 class="mb-3">Laporan Stok</h5>
    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>Nama Obat</th>
                <th>Stok Awal</th>
                <th>Stok Masuk</th>
                <th>Stok Keluar</th>
                <th>Stok Terakhir</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Paracetamol</td>
                <td>100</td>
                <td>50</td>
                <td>30</td>
                <td>120</td>
            </tr>

        </tbody>
    </table>
</div>


<div class="mt-4">
    <button class="btn btn-secondary">
        <i class="bi bi-printer me-1"></i> Cetak Laporan
    </button>
</div>


<script>
    document.getElementById('jenisLaporan').addEventListener('change', function () {
        const jenis = this.value;
        document.getElementById('laporanLabaRugi').style.display = jenis === 'labarugi' ? 'block' : 'none';
        document.getElementById('laporanStok').style.display = jenis === 'stok' ? 'block' : 'none';
    });
</script>
@endsection
