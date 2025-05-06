@extends('layouts.main')
@section('title', 'Edit Penjualan')

@section('artikel')
<form action="{{ route('penjualan.update', $penjualan->id_penjualan) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group mb-3">
        <label for="id_penjualan">ID Penjualan</label>
        <input type="text" class="form-control" value="{{ $penjualan->id_penjualan }}" readonly>
    </div>

    <div class="form-group mb-3">
        <label for="tgl_penjualan">Tanggal Penjualan</label>
        <input type="date" name="tgl_penjualan" class="form-control" value="{{ $penjualan->tgl_penjualan }}" readonly>
    </div>

    <div class="form-group mb-3">
        <label for="id_admin">Nama Admin</label>
        <select name="id_admin" class="form-control" required>
            @foreach($admins as $admin)
                <option value="{{ $admin->id_admin }}" {{ $penjualan->id_admin == $admin->id_admin ? 'selected' : '' }}>
                    {{ $admin->nama_admin }}
                </option>
            @endforeach
        </select>
    </div>

    <h5 class="mt-4">Detail Obat Dijual</h5>
    <div id="detail-obat-wrapper">
        @foreach($penjualan->detailPenjualan as $detail)
            <input type="hidden" name="id_detailjual[]" value="{{ $detail->id_detailjual }}">

            <div class="row mb-2 detail-obat-item">
                <div class="col-md-3">
                    <label>Nama Obat</label>
                    <input type="text" class="form-control"
                        value="{{ $detail->detailObat->obat->nama_obat ?? 'Obat tidak ditemukan' }}"readonly>
                </div>

                <div class="col-md-2">
                    <label>Harga Jual</label>
                    <input type="number" name="harga_jual[]" class="form-control harga-jual"
                        value="{{ $detail->harga_jual }}" readonly>
                </div>

                <div class="col-md-2">
                    <label>Jumlah Terjual</label>
                    <input type="number" name="jumlah_terjual[]" class="form-control jumlah-terjual"
                        value="{{ $detail->jumlah_terjual }}" required>
                </div>

                <div class="col-md-2">
                    <label>Total</label>
                    <input type="number" class="form-control total-harga"
                        value="{{ $detail->harga_jual * $detail->jumlah_terjual }}" readonly>
                </div>
            </div>
        @endforeach
    </div>

    <button type="submit" class="btn btn-primary mt-3">Update Penjualan</button>
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

        wrapper.querySelectorAll('.detail-obat-item').forEach(item => {
            updateTotal(item);

            item.querySelector('.jumlah-terjual').addEventListener('input', function () {
                updateTotal(item);
            });

            item.querySelector('.harga-jual').addEventListener('input', function () {
                updateTotal(item);
            });
        });
    });
</script>
@endsection
