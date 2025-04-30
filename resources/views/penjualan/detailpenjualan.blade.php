@extends('layouts.main')
@section('title', 'Detail Penjualan')

@section('artikel')
<div class="card">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">Detail Penjualan</h5>
    </div>
    <div class="card-body">

        <div class="mb-3">
            <label for="id_penjualan">ID Penjualan</label>
            <input type="text" id="id_penjualan" class="form-control"
                value="{{ $penjualan->id_penjualan }}" readonly>
        </div>

        <div class="mb-3">
            <label for="tgl_penjualan">Tanggal Penjualan</label>
            <input type="date" id="tgl_penjualan" class="form-control"
                value="{{ \Carbon\Carbon::parse($penjualan->tgl_penjualan)->format('Y-m-d') }}" readonly>
        </div>

        <div class="mb-3">
            <label for="nama_admin">Nama Admin</label>
            <input type="text" id="nama_admin" class="form-control"
                value="{{ $penjualan->admin->nama_admin ?? 'Admin Tidak Diketahui' }}" readonly>
        </div>

        <table class="table table-bordered">
            <thead class="thead-light">
                <tr class="text-center">
                    <th>No</th>
                    <th>ID Obat</th>
                    <th>Nama Obat</th>
                    <th>Harga Jual</th>
                    <th>Jumlah Terjual</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                    $grandTotal = 0;
                @endphp
                @foreach($penjualan->detailPenjualan as $detail)
                    @php
                        $obat = $detail->detailObat->obat ?? null;
                        $idObat = $obat->id_obat ?? '-';
                        $namaObat = $obat->nama_obat ?? '-';
                        $hargaJual = $detail->harga_jual ?? 0;
                        $jumlah = $detail->jumlah_terjual ?? 0;
                        $subtotal = $jumlah * $hargaJual;
                        $grandTotal += $subtotal;
                    @endphp
                    <tr class="text-center">
                        <td>{{ $no++ }}</td>
                        <td>{{ $idObat }}</td>
                        <td>{{ $namaObat }}</td>
                        <td>Rp {{ number_format($hargaJual, 0, ',', '.') }}</td>
                        <td>{{ $jumlah }}</td>
                        <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach

                {{-- Baris total di bawah tabel --}}
                <tr style="background-color: #f1f1f1; font-weight: bold; text-align: center;">
                    <td colspan="5" style="vertical-align: middle;">TOTAL PENJUALAN</td>
                    <td>Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <a href="{{ route('penjualan.index') }}" class="btn btn-secondary mt-3">
            <i class="bi bi-arrow-left-circle"></i> Kembali
        </a>

    </div>
</div>
@endsection
