@extends('layouts.main')
@section('title', 'Tambah Penjualan')
@section('artikel')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <form action="{{ route('penjualan.store') }}" method="POST">
        @csrf

        <div class="form-group mb-3">
            <label for="id_penjualan">ID Penjualan</label>
            <input type="text" name="id_penjualan" id="id_penjualan" class="form-control" value="{{ $nextId }}" readonly>
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

        <h5 class="mt-4">Detail Obat Dijual</h5>

        <div id="detail-obat-wrapper">
            <div class="row mb-2 detail-obat-item">
                <div class="col-md-3">
                    <label for="nama-obat" class="form-label">Nama Obat</label>
                    <input list="obat-list" class="form-control nama-obat" placeholder="Ketik nama obat" required>
                    <input type="hidden" name="id_obat[]" class="id-obat">
                </div>

                <datalist id="obat-list">
                    @foreach($obats as $obat)
                        <option value="{{ $obat->nama_obat }}"></option>
                    @endforeach
                </datalist>

                <div class="col-md-2">
                    <label for="harga-jual" class="form-label">Harga Jual</label>
                    <input type="number" name="harga_jual[]" class="form-control harga-jual" required>
                </div>

                <div class="col-md-2">
                    <label>Jumlah Terjual</label>
                    <input type="number" name="jumlah_terjual[]" class="form-control jumlah-terjual" required>
                </div>
                <div class="col-md-2">
                    <label>Total</label>
                    <input type="number" class="form-control total-harga" readonly>
                </div>
                <div class="col-md-2 d-flex align-items-end">
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

            function updateTotal(item) {
                const harga = item.querySelector('.harga-jual').value;
                const jumlah = item.querySelector('.jumlah-terjual').value;
                const total = item.querySelector('.total-harga');

                total.value = (parseFloat(harga) || 0) * (parseInt(jumlah) || 0);
            }

            wrapper.addEventListener('input', function (e) {
                if (e.target.classList.contains('harga-jual') || e.target.classList.contains('jumlah-terjual')) {
                    const item = e.target.closest('.detail-obat-item');
                    updateTotal(item);
                }
            });

            wrapper.addEventListener('click', function (e) {
                if (e.target.classList.contains('add-detail')) {
                    e.preventDefault();
                    const newItem = e.target.closest('.detail-obat-item').cloneNode(true);

                    newItem.querySelectorAll('input').forEach(input => input.value = '');

                    const button = newItem.querySelector('button');
                    button.classList.remove('btn-success', 'add-detail');
                    button.classList.add('btn-danger', 'remove-detail');
                    button.innerText = '-';

                    wrapper.appendChild(newItem);
                }

                if (e.target.classList.contains('remove-detail')) {
                    e.preventDefault();
                    e.target.closest('.detail-obat-item').remove();
                }
            });
        });
    </script>

<script>
    const obatData = @json($obats);

    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('nama-obat')) {
            const input = e.target;
            const value = input.value.trim().toLowerCase();
            const wrapper = input.closest('.detail-obat-item');

            const found = obatData.find(obat => obat.nama_obat.toLowerCase() === value);
            if (found) {
                wrapper.querySelector('.harga-jual').value = found.harga_jual;
                wrapper.querySelector('.id-obat').value = found.id_obat;
            } else {
                wrapper.querySelector('.harga-jual').value = '';
                wrapper.querySelector('.id-obat').value = '';
            }
        }
    });
</script>

@endsection
