@extends('layouts.main')
@section('title', 'Detail Pembelian')

@section('artikel')
<div class="card">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">Detail Pembelian</h5>
    </div>
    <div class="card-body">

        <div class="mb-3">
            <label for="id_pembelian">ID Pembelian</label>
            <input type="text" name="id_pembelian" id="id_pembelian" class="form-control"
                value="{{ $pembelian->id_pembelian }}" readonly>
        </div>

        <div class="mb-3">
            <label for="nama_admin">Nama Admin</label>
            <input type="text" name="nama_admin" id="nama_admin" class="form-control"
                value="{{ $pembelian->admin->nama_admin ?? 'Admin Tidak Diketahui' }}" readonly>
        </div>

        <div class="mb-3">
            <label for="tgl_pembelian">Tanggal Pembelian</label>
            <input type="date" name="tgl_pembelian" id="tgl_pembelian" class="form-control"
                value="{{ \Carbon\Carbon::parse($pembelian->tgl_pembelian)->format('Y-m-d') }}" readonly>
        </div>

        <table class="table table-bordered">
            <thead class="thead-light">
                <tr class="text-center">
                    <th>No</th>
                    <th>Nama Obat</th>
                    <th>Jumlah Beli</th>
                    <th>Harga Beli</th>
                    <th>Tanggal Kadaluarsa</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                    $grandTotal = 0;
                @endphp
                @foreach($details as $detail)
                    @php
                        $total = $detail->jumlah_beli * $detail->harga_beli;
                        $grandTotal += $total;
                    @endphp
                    <tr class="text-center">
                        <td>{{ $no++ }}</td>
                        <td>{{ $detail->detailObat->obat->nama_obat ?? '-' }}</td>
                        <td>{{ $detail->jumlah_beli }}</td>
                        <td>Rp {{ number_format($detail->harga_beli, 0, ',', '.') }}</td>
                        <td>{{ \Carbon\Carbon::parse($detail->tgl_kadaluarsa)->format('d/m/Y') }}</td>
                        <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="text-center fw-bold bg-light">
                    <td colspan="5">Total Pembelian</td>
                    <td>Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <a href="{{ route('pembelian.index') }}" class="btn btn-secondary mt-3">
            <i class="bi bi-arrow-left-circle"></i> Kembali
        </a>

    </div>
</div>
@endsection
