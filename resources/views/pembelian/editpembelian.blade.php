@extends('layouts.main')
@section('title', 'Edit Pembelian')

@section('artikel')
    <div class="container">
        <h2>Edit Pembelian</h2>

        <form action="{{ route('pembelian.update', ['pembelian' => $pembelian->id_pembelian]) }}" method="POST">



            @csrf
            @method('PUT')

            <!-- ID Pembelian -->
            <!-- <div class="form-group">
                    <label for="id">ID Pembelian</label>
                    <input type="text" class="form-control" id="id" name="id" value="{{ $pembelian->id }}" readonly>
                </div> -->
            <div class="form-group">
                <label for="id_pembelian">ID Pembelian</label>
                <input type="text" class="form-control" id="id_pembelian" name="id_pembelian"
                    value="{{ $pembelian->id_pembelian }}" readonly>
            </div>

            <!-- Obat ID (tidak bisa diubah) -->
            <!-- <div class="form-group">
                    <label for="obat_id">ID Obat</label>
                    <input type="text" class="form-control" id="obat_id" name="obat_id"
                        value="{{ optional($pembelian->detailPembelian->first()->detailObat ?? null)->obat_id }}" readonly>
                    <small class="form-text text-muted">ID Obat tidak dapat diubah saat edit</small>
                </div> -->
            <div class="form-group">
                <label for="id_obat">Nama Obat</label>
                <input type="text" class="form-control" id="nama_obat" name="id_obat"
                    value="{{ optional($pembelian->detailPembelian->first()->detailObat->obat ?? null)->id_obat }}"
                    readonly>
            </div>

            <div class="form-group">
                <label for="nama_obat">Nama Obat</label>
                <input type="text" class="form-control" id="nama_obat" name="nama_obat"
                    value="{{ optional($pembelian->detailPembelian->first()->detailObat->obat ?? null)->nama_obat }}"
                    readonly>
            </div>

            <!-- Jumlah -->
            <div class="form-group">
                <label for="jumlah">Jumlah</label>
                <input type="number" class="form-control" id="jumlah" name="jumlah"
                    value="{{ old('jumlah', $pembelian->jumlah) }}" required>
            </div>

            <!-- Harga -->
            <div class="form-group">
                <label for="harga">Harga</label>
                <input type="number" class="form-control" id="harga" name="harga"
                    value="{{ old('harga', $pembelian->harga) }}" required>
            </div>

            <!-- Tombol -->
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('pembelian.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection
