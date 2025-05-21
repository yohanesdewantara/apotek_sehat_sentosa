@extends('layouts.main')
@section('title', 'Tambah Obat')

@section('artikel')
<form action="{{ route('obat.store') }}" method="POST">
    @csrf

    <!-- ID Obat -->
    <div class="form-group mb-3">
        <label for="id_obat">ID Obat</label>
        <input type="text" id="id_obat" class="form-control" value="{{ $nextId }}" readonly>
        <!-- Removed name attribute since we're using auto-increment in database -->
    </div>

    <!-- Nama Obat -->
    <div class="form-group mb-3">
        <label for="nama_obat">Nama Obat</label>
        <input type="text" name="nama_obat" id="nama_obat" class="form-control" required>
    </div>

    <!-- Jenis Obat -->
    <div class="form-group mb-3">
    <label for="jenis_obat">Jenis Obat</label>
    <select name="jenis_obat" id="jenis_obat" class="form-control" required>
        <option value="">-- Pilih Jenis Obat --</option>
        @foreach($jenisObatOptions as $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
        @endforeach
    </select>
</div>

    <!-- Rak Obat -->
    <div class="form-group mb-3">
        <label for="id_rak">Nama Rak</label>
        <select name="id_rak" id="id_rak" class="form-control" required>
            <option value="">-- Pilih Rak --</option>
            @foreach($raks as $rak)
                <option value="{{ $rak->id_rak }}">{{ $rak->nama_rak }}</option>
            @endforeach
        </select>
    </div>

    <!-- Stok Total -->
    <div class="form-group mb-3">
        <label for="stok_total">Stok Total</label>
        <input type="number" id="stok_total" class="form-control" readonly>
        <!-- Removed name attribute since we calculate this in controller -->
    </div>

    <h5 class="mt-4">Detail Obat</h5>

    <div id="detail-obat-wrapper">
        <div class="row mb-2 detail-obat-item">
            <!-- Stok -->
            <div class="col-md-1">
                <label>Stok</label>
                <input type="number" name="stok[]" class="form-control stok-input" min="0" required>
            </div>

            <!-- Harga Beli -->
            <div class="col-md-2">
                <label>Harga Beli</label>
                <input type="number" name="harga_beli[]" class="form-control harga-beli-input" min="0" step="0.01" required>
            </div>

            <!-- Harga Jual -->
            <div class="col-md-2">
                <label>Harga Jual</label>
                <input type="number" name="harga_jual[]" class="form-control" min="0" step="0.01" required>
            </div>

            <!-- Tanggal Kadaluarsa -->
            <div class="col-md-3">
                <label>Tanggal Kadaluarsa</label>
                <input type="date" name="tgl_kadaluarsa[]" class="form-control" required>
            </div>

            <!-- Tombol Tambah -->
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-success add-detail">+</button>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary mt-3">Simpan Obat</button>
    <a href="{{ route('obat.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</form>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.getElementById('detail-obat-wrapper');
    const idObat = document.getElementById('id_obat').value;
    let detailIdCounter = 1;

    function updateStokTotal() {
        let total = 0;
        document.querySelectorAll('.stok-input').forEach(input => {
            total += parseInt(input.value) || 0;
        });
        document.getElementById('stok_total').value = total;
    }

    // Auto-calculate suggested selling price (20% markup from purchase price)
    document.body.addEventListener('input', function(e) {
        if (e.target.classList.contains('harga-beli-input')) {
            const row = e.target.closest('.detail-obat-item');
            const hargaJualInput = row.querySelector('input[name="harga_jual[]"]');

            // Only auto-fill if harga jual is empty or was auto-calculated before
            if (!hargaJualInput.value || hargaJualInput._autoCalculated) {
                const hargaBeli = parseFloat(e.target.value) || 0;
                const markup = hargaBeli * 0.2; // 20% markup
                const suggestedPrice = Math.ceil((hargaBeli + markup) / 100) * 100; // Round up to nearest 100

                hargaJualInput.value = suggestedPrice;
                hargaJualInput._autoCalculated = true;
            }
        }
    });

    // Trigger stok update saat load dan saat input stok berubah
    updateStokTotal();
    wrapper.addEventListener('input', e => {
        if (e.target.classList.contains('stok-input')) {
            updateStokTotal();
        }
    });

    wrapper.addEventListener('click', function (e) {
        // Tambah detail
        if (e.target.classList.contains('add-detail')) {
            e.preventDefault();
            const newItem = e.target.closest('.detail-obat-item').cloneNode(true);

            // Reset input values
            newItem.querySelectorAll('input').forEach(input => {
                if (input.name !== 'diskon[]') {
                    input.value = '';
                } else {
                    input.value = '0'; // Keep diskon default at 0
                }

                // Reset auto-calculated flag
                if (input.name === 'harga_jual[]') {
                    input._autoCalculated = false;
                }
            });

            // Ubah tombol menjadi "-"
            const btn = newItem.querySelector('button');
            btn.classList.remove('btn-success', 'add-detail');
            btn.classList.add('btn-danger', 'remove-detail');
            btn.innerText = '-';

            wrapper.appendChild(newItem);
            updateStokTotal();
        }

        // Hapus detail
        if (e.target.classList.contains('remove-detail')) {
            e.preventDefault();
            e.target.closest('.detail-obat-item').remove();
            updateStokTotal();
        }
    });
});
</script>
@endsection
