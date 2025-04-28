@extends('layouts.main')
@section('title', 'Tambah Pembelian')

@section('artikel')
    <form action="{{ route('pembelian.store') }}" method="POST">
        @csrf

        {{-- Pilih Admin --}}
        <div class="form-group">
            <label for="id_admin">Nama Admin</label>
            <select name="id_admin" id="id_admin" class="form-control" required>
                @foreach($admins as $admin)
                    <option value="{{ $admin->id_admin }}">{{ $admin->nama_admin }}</option>
                @endforeach
            </select>
        </div>

        {{-- Tanggal Pembelian --}}
        <div class="form-group">
            <label for="tgl_pembelian">Tanggal Pembelian</label>
            <input type="date" name="tgl_pembelian" id="tgl_pembelian" class="form-control" required>
        </div>

        {{-- Nama Obat --}}
        <div class="form-group">
            <label for="nama_obat">Nama Obat</label>
            <input type="text" name="nama_obat" id="nama_obat" class="form-control" required>
        </div>

        {{-- Jenis Obat --}}
        <div class="form-group">
            <label for="tgl_pembelian">Jenis Obat</label>
            <input type="text" name="jenis_obat" id="jenis_obat" class="form-control" required>
        </div>

        {{-- Keterangan Obat --}}
        <div class="form-group">
            <label for="tgl_pembelian">Keterangan Obat</label>
            <input type="text" name="keterangan_obat" id="keterangan_obat" class="form-control" required>
        </div>

        {{-- Jumlah (Qty) --}}
        <div class="form-group">
            <label for="jumlah_beli">Jumlah Beli (Qty)</label>
            <input type="number" name="jumlah_beli" id="jumlah_beli" class="form-control" required>
        </div>

        {{-- Harga Beli --}}
        <div class="form-group">
            <label for="harga_beli">Harga Beli</label>
            <input type="number" name="harga_beli" id="harga_beli" class="form-control" required>
        </div>

        {{-- Harga Jual --}}
        <div class="form-group">
            <label for="harga_jual">Harga Jual</label>
            <input type="number" name="harga_jual" id="harga_jual" class="form-control" required>
        </div>

        {{-- Tanggal Kadaluarsa --}}
        <div class="form-group">
            <label for="tgl_kadaluarsa">Tanggal Kadaluwarsa</label>
            <input type="date" name="tgl_kadaluarsa" id="tgl_kadaluarsa" class="form-control" required>
        </div>

        {{-- Total --}}
        <div class="form-group">
            <label for="total">Total</label>
            <input type="number" name="total" id="total" class="form-control" required readonly>
        </div>

        {{-- Tombol --}}
        <button type="submit" class="btn btn-primary mt-3">Simpan</button>
        <a href="{{ route('pembelian.index') }}" class="btn btn-secondary mt-3">Kembali</a>
    </form>

    {{-- Script otomatis menghitung total harga --}}
    <script>
        const qtyInput = document.getElementById('jumlah_beli');
        const hargaBeliInput = document.getElementById('harga_beli');
        const totalHargaInput = document.getElementById('total');

        function hitungTotal() {
            const qty = parseInt(qtyInput.value) || 0;
            const hargaBeli = parseInt(hargaBeliInput.value) || 0;
            totalHargaInput.value = qty * hargaBeli;
        }

        qtyInput.addEventListener('input', hitungTotal);
        hargaBeliInput.addEventListener('input', hitungTotal);
    </script>
@endsection
