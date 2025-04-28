@extends('layouts.main')
@section('title', 'Tambah Pembelian')

@section('artikel')
<form action="{{ route('pembelian.store') }}" method="POST">
    @csrf

    <div class="form-group mb-3">
        <label for="tgl_pembelian">Tanggal Pembelian</label>
        <input type="date" name="tgl_pembelian" id="tgl_pembelian" class="form-control" required>
    </div>

    <div class="form-group mb-3">
        <label for="id_admin">Nama Admin</label>
        <select name="id_admin" id="id_admin" class="form-control" required>
            @foreach($admins as $admin)
                <option value="{{ $admin->id_admin }}">{{ $admin->nama_admin }}</option>
            @endforeach
        </select>
    </div>

    <h5 class="mt-4">Detail Obat Dibeli</h5>

    <div id="detail-obat-wrapper">
        <div class="row mb-2 detail-obat-item">
            <div class="col-md-5">
                <label>Obat</label>
                <select name="obat_id[]" class="form-control" required>
                    @foreach($obats as $obat)
                        <option value="{{ $obat->id_obat }}">{{ $obat->nama_obat }} (Exp: {{ $obat->tgl_kadaluarsa }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>Jumlah</label>
                <input type="number" name="jumlah_beli[]" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label>Harga Beli</label>
                <input type="number" name="harga_beli[]" class="form-control" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-success add-detail">+</button>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary mt-3">Simpan Pembelian</button>
    <a href="{{ route('pembelian.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const wrapper = document.getElementById('detail-obat-wrapper');

        wrapper.addEventListener('click', function (e) {
            if (e.target.classList.contains('add-detail')) {
                e.preventDefault();
                const newItem = e.target.closest('.detail-obat-item').cloneNode(true);

                // Kosongkan inputan
                newItem.querySelectorAll('input').forEach(input => input.value = '');

                // Ganti tombol tambah jadi hapus di clone
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
    });
</script>
@endsection
