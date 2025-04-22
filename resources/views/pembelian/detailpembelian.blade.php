@extends('layouts.main')
@section('title', 'Detail Pembelian')

@section('artikel')
<div class="card">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">Detail Pembelian</h5>
    </div>
    <div class="card-body">
        <form>

            <div class="form-group mb-3">
                <label for="nama_obat">Nama Admin</label>
                <input type="text" name="nama_admin" id="nama_admin" class="form-control"
                    value="{{ $nama_admin }}" readonly>
            </div>

            <div class="form-group mb-3">
                <label for="tgl_pembelian">Tanggal Pembelian</label>
                <input type="date" name="tgl_pembelian" id="tgl_pembelian" class="form-control"
                    value="{{ \Carbon\Carbon::parse($detail->tgl_pembelian)->format('Y-m-d') }}" readonly>
            </div>

            <div class="form-group mb-3">
                <label for="nama_obat">Nama Obat</label>
                <input type="text" name="nama_obat" id="nama_obat" class="form-control"
                    value="{{ $nama_obat }}" readonly>
            </div>

            <div class="form-group mb-3">
                <label for="jenis_obat">Jenis Obat</label>
                <input type="text" name="jenis_obat" id="jenis_obat" class="form-control"
                    value="{{ $jenis_obat }}" readonly>
            </div>

            <div class="form-group mb-3">
                <label for="keterangan_obat">Keterangan Obat</label>
                <input type="text" name="keterangan_obat" id="keterangan_obat" class="form-control"
                    value="{{ $keterangan_obat }}" readonly>
            </div>

            <div class="form-group mb-3">
                <label for="qty">Jumlah Beli (Qty)</label>
                <input type="number" name="qty" id="qty" class="form-control"
                    value="{{ $detail->jumlah_beli }}" readonly>
            </div>

            <div class="form-group mb-3">
                <label for="harga_beli">Harga Beli</label>
                <input type="text" name="harga_beli" id="harga_beli" class="form-control"
                    value="Rp {{ number_format($detail->harga_beli, 0, ',', '.') }}" readonly>
            </div>

            <div class="form-group mb-3">
                <label for="harga_jual">Harga Jual</label>
                <input type="text" name="harga_jual" id="harga_jual" class="form-control"
                    value="Rp {{ number_format($harga_jual, 0, ',', '.') }}" readonly>
            </div>

            <div class="form-group mb-3">
                <label for="tgl_kadaluarsa">Tanggal Kadaluwarsa</label>
                <input type="date" name="tgl_kadaluarsa" id="tgl_kadaluarsa" class="form-control"
                    value="{{ \Carbon\Carbon::parse($tgl_kadaluarsa)->format('Y-m-d') }}" readonly>
            </div>

            <div class="form-group mb-3">
                <label for="total">Total</label>
                <input type="text" name="total" id="total" class="form-control"
                    value="Rp {{ number_format($total, 0, ',', '.') }}" readonly>
            </div>

            <a href="{{ route('pembelian.index') }}" class="btn btn-secondary mt-3">
                <i class="bi bi-arrow-left-circle"></i> Kembali
            </a>
        </form>
    </div>
</div>
@endsection
