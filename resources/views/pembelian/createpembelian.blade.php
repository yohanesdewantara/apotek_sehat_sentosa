@extends('layouts.main')
@section('title', 'Tambah Pembelian')

@section('artikel')
<form action="{{ route('pembelian.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="id_obat">ID Obat</label>
            <input type="text" name="id_obat" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="tgl_pembelian">Tanggal Pembelian</label>
            <input type="date" name="tgl_pembelian" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="tgl_kadaluwarsa">Tanggal Kadaluwarsa</label>
            <input type="date" name="tgl_kadaluwarsa" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="qty">Jumlah (Qty)</label>
            <input type="number" name="qty" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="harga_beli">Harga Beli</label>
            <input type="number" name="harga_beli" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="harga_jual">Harga Jual</label>
            <input type="number" name="harga_jual" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="total">Total Harga</label>
            <input type="number" name="total" class="form-control" value="0" readonly>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Simpan</button>
        <a href="{{ route('pembelian.index') }}" class="btn btn-secondary mt-3">Kembali</a>
    </form>
@endsection
