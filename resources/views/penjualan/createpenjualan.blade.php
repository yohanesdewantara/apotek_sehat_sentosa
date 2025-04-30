@extends('layouts.main')
@section('title', 'Tambah Penjualan')

@section('artikel')
<form action="{{ route('penjualan.store') }}" method="POST">
    @csrf

    <div class="form-group mb-3">
        <label for="id_penjualan">ID Penjualan</label>
        <input type="text" name="id_penjualan" id="id_penjualan" class="form-control" value="{{ $newPenjualanId }}" readonly>
    </div>

    <div class="form-group mb-3">
        <label for="tgl_penjualan">Tanggal Penjualan</label>
        <input type="date" name="tgl_penjualan" id="tgl_penjualan" class="form-control" required>
    </div>

    <div class="form-group mb-3">
        <label for="id_admin">Nama Admin</label>
        <select name="id_admin" id="id_admin" class="form-control" required>
            @foreach($admins as $admin)
                <option value="{{ $admin->id_admin }}">{{ $admin->nama_admin }}</option>
            @endforeach
        </select>
    </div>

    <h5 class="mt-4">Detail Obat Terjual</h5>

    <div id="detail-obat-wrapper">
        <div class="row mb-2 detail-obat-item">
            <div class="col-md-3">
                <label>Nama Obat</label>
                <input type="text" name="nama_obat[]" class="form-control nama-obat" required>
            </div>
            <div class="col-md-2">
                <label>Harga Jual</label>
                <input type="number" name="harga_jual[]" class="form-control harga-jual" required>
            </div>
            <div class="col-md-2">
                <label>Jumlah Terjual</label>
                <input type="number" name="jumlah_terjual[]" class="form-control jumlah-terjual" required>
            </div>
            <div class="col-md-2">
                <label>Total</label>
                <input type="number" name="total[]" class="form-control total" readonly>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-success add-detail">+</button>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary mt-3">Simpan Penjualan</button>
    <a href="{{ route('penjualan.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const wrapper = document.getElementById('detail-obat-wrapper');

        wrapper.addEventListener('click', function (e) {
            if (e.target.classList.contains('add-detail')) {
                e.preventDefault();
                const newItem = e.target.closest('.detail-obat-item').cloneNode(true);

                // Kosongkan inputan dan ubah tombol
                newItem.querySelectorAll('input').forEach(input => input.value = '');
                newItem.querySelector('.add-detail').classList.remove('btn-success', 'add-detail');
                newItem.querySelector('button').classList.add('btn-danger', 'remove-detail');
                newItem.querySelector('button').innerHTML = '-';

                wrapper.appendChild(newItem);
            }

            if (e.target.classList.contains('remove-detail')) {
                e.preventDefault();
                e.target.closest('.detail-obat-item').remove();
            }
        });

        // Hitung total otomatis
        wrapper.addEventListener('input', function (e) {
            if (e.target.classList.contains('harga-jual') || e.target.classList.contains('jumlah-terjual')) {
                const row = e.target.closest('.detail-obat-item');
                const harga = parseFloat(row.querySelector('.harga-jual').value) || 0;
                const jumlah = parseFloat(row.querySelector('.jumlah-terjual').value) || 0;
                const total = harga * jumlah;
                row.querySelector('.total').value = total;
            }
        });
    });
</script>
@endsection
