@extends('layouts.main')
@section('title', 'Edit Pembelian')

@section('artikel')
    <div class="container">
        <h2>Edit Pembelian</h2>

        <form action="{{ route('pembelian.update', ['pembelian' => $pembelian->id_pembelian]) }}" method="POST">
            @csrf
            @method('PUT')


            <div class="form-group">
                <label for="nama_admin">Nama Admin</label>
                <input type="text" name="nama_admin" id="nama_admin" class="form-control"
                    value="{{ old('nama_admin', optional($pembelian->admin)->nama_admin) }}" required>
            </div>


            <div class="form-group">
                <label for="tgl_pembelian">Tanggal Pembelian</label>
                <input type="date" name="tgl_pembelian" id="tgl_pembelian" class="form-control"
                    value="{{ old('tgl_pembelian', $pembelian->tgl_pembelian) }}" required>
            </div>

            <div class="form-group">
                <label for="nama_obat">Nama Obat</label>
                <input type="text" name="nama_obat" id="nama_obat" class="form-control"
                    value="{{ old('nama_obat', optional($pembelian->detailPembelian->first()->detailObat->obat)->nama_obat) }}"
                    required>
            </div>

            <div class="form-group">
                <label for="jenis_obat">Jenis Obat</label>
                <input type="text" name="jenis_obat" id="jenis_obat" class="form-control"
                    value="{{ old('jenis_obat', optional($pembelian->detailPembelian->first()->detailObat->obat)->jenis_obat) }}"
                    required>
            </div>


            <div class="form-group">
                <label for="keterangan_obat">Keterangan Obat</label>
                <input type="text" name="keterangan_obat" id="keterangan_obat" class="form-control"
                    value="{{ old('keterangan_obat', optional($pembelian->detailPembelian->first()->detailObat->obat)->keterangan_obat) }}"
                    required>
            </div>


            <div class="form-group">
                <label for="jumlah_beli">Jumlah Beli</label>
                <input type="number" name="jumlah_beli" id="jumlah_beli" class="form-control"
                    value="{{ old('jumlah_beli', $pembelian->detailPembelian->first()->jumlah_beli) }}"
                    required>
            </div>

            <div class="form-group">
                <label for="harga_beli">Harga Beli</label>
                <input type="number" name="harga_beli" id="harga_beli" class="form-control"
                    value="{{ old('harga_beli', $pembelian->detailPembelian->first()->harga_beli) }}"
                    required>
            </div>

            <div class="form-group">
                <label for="harga_jual">Harga Jual</label>
                <input type="number" name="harga_jual" id="harga_jual" class="form-control"
                    value="{{ old('harga_jual', optional($pembelian->detailPembelian->first()->detailObat->obat)->harga_jual) }}"
                    required>
            </div>

            <div class="form-group">
                <label for="tgl_kadaluarsa">Tanggal Kadaluwarsa</label>
                <input type="date" name="tgl_kadaluarsa" id="tgl_kadaluarsa" class="form-control"
                    value="{{ old('tgl_kadaluarsa', $pembelian->detailPembelian->first()->tgl_kadaluarsa) }}"
                    required>
            </div>

            <div class="form-group mb-3">
                <label for="total">Total</label>
                <input type="text" name="total" id="total" class="form-control"
                    value="Rp {{ number_format($pembelian->total, 0, ',', '.') }}" readonly>
            </div>



            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('pembelian.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

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
