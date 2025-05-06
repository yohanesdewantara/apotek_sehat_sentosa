@extends('layouts.main')
@section('title', 'Tambah Pembelian')

@section('artikel')
    <form action="{{ route('pembelian.store') }}" method="POST">
        @csrf

        <div class="form-group mb-3">
            <label for="id_pembelian">ID Pembelian</label>
            <input type="text" name="id_pembelian" id="id_pembelian" class="form-control" value="{{ $nextId }}" readonly>
        </div>

        <!-- Tanggal dan Admin -->
        <div class="form-group mb-3">
            <label for="tgl_pembelian">Tanggal Pembelian</label>
            <input type="date" name="tgl_pembelian" id="tgl_pembelian" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="id_admin">Nama Admin</label>
            <select name="id_admin" id="id_admin" class="form-control" required>
                <option value="">-- Pilih Admin --</option>
                @foreach($admins as $admin)
                    <option value="{{ $admin->id_admin }}">{{ $admin->nama_admin }}</option>
                @endforeach
            </select>
        </div>

        <h5 class="mt-4">Detail Obat Dibeli</h5>

        <div id="detail-obat-wrapper">
            <div class="row mb-2 detail-obat-item">
                <div class="col-md-4">
                    <label>Obat</label>
                    <select name="obat_id[]" class="form-control obat-select" required>
                        <option value="">-- Pilih Obat --</option>
                        @foreach($obats as $obat)
                            <option value="{{ $obat->id_obat }}">{{ $obat->nama_obat }}</option>
                        @endforeach
                        <option value="new">[+ Tambah Obat Baru]</option>
                    </select>

                    <!-- Form Obat Baru (Hidden by default) -->
                    <div class="obat-baru-form mt-2" style="display:none;">
                        <input type="text" name="nama_obat_baru[]" class="form-control mt-1" placeholder="Nama Obat Baru">
                        <input type="text" name="jenis_obat_baru[]" class="form-control mt-1" placeholder="Jenis Obat">
                        <input type="text" name="keterangan_obat_baru[]" class="form-control mt-1"
                            placeholder="Keterangan Obat">
                    </div>
                </div>

                <div class="col-md-2">
                    <label>Jumlah</label>
                    <input type="number" name="jumlah_beli[]" class="form-control" required>
                </div>



                <div class="col-md-2">
                    <label>Harga Beli</label>
                    <input type="number" name="harga_beli[]" class="form-control" required>
                </div>

                <div class="col-md-2">
                    <label>Harga Jual</label>
                    <input type="number" name="harga_jual[]" class="form-control" required>
                </div>

                <div class="col-md-2">
                    <label>Tanggal Kadaluarsa</label>
                    <input type="date" name="tgl_kadaluarsa[]" class="form-control" required>
                </div>


                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-success add-detail">+</button>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Simpan Pembelian</button>
        <a href="{{ route('pembelian.index') }}" class="btn btn-secondary mt-3">Kembali</a>
    </form>

    <!-- Script untuk tambah/hapus detail obat -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const wrapper = document.getElementById('detail-obat-wrapper');

            wrapper.addEventListener('change', function (e) {
                if (e.target.classList.contains('obat-select')) {
                    const obatBaruForm = e.target.parentElement.querySelector('.obat-baru-form');
                    if (e.target.value === 'new') {
                        obatBaruForm.style.display = 'block';
                    } else {
                        obatBaruForm.style.display = 'none';
                    }
                }
            });

            wrapper.addEventListener('click', function (e) {
                if (e.target.classList.contains('add-detail')) {
                    e.preventDefault();
                    const newItem = e.target.closest('.detail-obat-item').cloneNode(true);

                    // Kosongkan inputan
                    newItem.querySelectorAll('input').forEach(input => input.value = '');
                    newItem.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

                    // Sembunyikan form obat baru
                    const obatBaruForm = newItem.querySelector('.obat-baru-form');
                    if (obatBaruForm) {
                        obatBaruForm.style.display = 'none';
                    }

                    // Ganti tombol tambah jadi hapus
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
