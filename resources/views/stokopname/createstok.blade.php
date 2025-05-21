@extends('layouts.main')
@section('title', 'Tambah Detail Stok Opname')

@push('styles')
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush

@section('artikel')
<form action="{{ route('stokopname.store') }}" method="POST">
    @csrf

    <!-- ID Opname -->
    <div class="mb-3">
        <label for="id_opname" class="form-label">ID Opname</label>
        <input type="text" id="id_opname" name="id_opname" class="form-control" value="{{ $newtIdOpname }}" readonly>
    </div>

    <!-- ID Detail StokOpname -->
    <div class="mb-3">
        <label for="id_detailopname" class="form-label">ID Detail Stok Opname</label>
        <input type="text" id="id_detailopname" name="id_detailopname" class="form-control" value="{{ $newIdDetailOpname }}" readonly>
    </div>

    <!-- Tanggal Masuk - Modern & Readonly -->
    <div class="mb-3">
        <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
        <div class="input-group">
            <input type="text" id="tanggal_masuk" name="tanggal_masuk" class="form-control"
            value="{{ \Carbon\Carbon::now('Asia/Jakarta')->format('d-m-Y') }}" readonly
                style="background-color: #f8f9fa; border-right: none; cursor: default;">
            <span class="input-group-text bg-white" style="border-left: none;">
                <i class="bi bi-calendar3"></i>
            </span>
            <!-- Hidden input to store the actual date value in Y-m-d format -->
            <input type="hidden" name="tanggal" value="{{ \Carbon\Carbon::now('Asia/Jakarta')->format('Y-m-d') }}">
        </div>
    </div>

    <!-- Pilih Nama Obat -->
    <div class="mb-3">
        <label for="nama_obat" class="form-label">Nama Obat</label>
        <select id="nama_obat" class="form-select" onchange="filterDetailObat()" required>
            <option value="" disabled selected>-- Pilih Nama Obat --</option>
            @foreach($obats as $obat)
                <option value="{{ $obat->id_obat }}">{{ $obat->nama_obat }}</option>
            @endforeach
        </select>
    </div>

    <!-- Pilih Detail Obat -->
    <div class="mb-3">
        <label for="id_detailobat" class="form-label">Detail Obat (Kadaluarsa)</label>
        <select name="id_detailobat" id="id_detailobat" class="form-select" required onchange="updateKadaluarsa()">
            <option value="" disabled selected>-- Pilih Detail Obat --</option>
            @foreach($detailObats as $detail)
                <option value="{{ $detail->id_detailobat }}"
                    data-obat="{{ $detail->id_obat }}"
                    data-tgl="{{ \Carbon\Carbon::parse($detail->tgl_kadaluarsa)->format('Y-m-d') }}"
                    data-stok="{{ $detail->stok }}"
                >
                    {{ $detail->id_detailobat }} - {{ \Carbon\Carbon::parse($detail->tgl_kadaluarsa)->format('d-m-Y') }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Tanggal Kadaluarsa -->
    <div class="mb-3">
        <label for="tanggal_kadaluarsa" class="form-label">Tanggal Kadaluarsa</label>
        <input type="date" id="tanggal_kadaluarsa" name="tanggal_kadaluarsa" class="form-control" readonly>
    </div>

    <!-- Stok System -->
    <div class="mb-3">
        <label for="stok_system" class="form-label">Stok System</label>
        <input type="number" id="stok_system" class="form-control" readonly>
        <small class="text-muted">Jumlah stok yang tercatat di sistem</small>
    </div>

    <!-- Stok Fisik -->
    <div class="mb-3">
        <label for="stok_fisik" class="form-label">Stok Fisik</label>
        <input type="number" id="stok_fisik" name="stok_fisik" class="form-control" min="0" required
               onchange="hitungSelisih()" placeholder="Masukkan jumlah stok fisik yang ditemukan...">
        <small class="text-muted">Jumlah stok fisik yang ditemukan saat pengecekan</small>
    </div>

    <!-- Stok Kadaluarsa (yang akan dikurangi dari stok sistem) -->
    <div class="mb-3">
        <label for="stok_kadaluarsa" class="form-label">Stok Kadaluarsa</label>
        <input type="number" id="stok_kadaluarsa" name="stok_kadaluarsa" class="form-control" min="0" required
               placeholder="Masukkan jumlah stok yang harus dikurangi...">
        <small class="text-muted">Jumlah stok yang akan dikurangi dari sistem (rusak/kadaluarsa)</small>
    </div>

    <!-- Selisih -->
    <div class="mb-3">
        <label for="selisih" class="form-label">Selisih (System - Fisik)</label>
        <input type="number" id="selisih" class="form-control" readonly>
        <small class="text-muted">Selisih antara stok sistem dan stok fisik</small>
    </div>

    <!-- Keterangan -->
    <div class="mb-3">
        <label for="keterangan" class="form-label">Keterangan</label>
        <input type="text" name="keterangan" id="keterangan" class="form-control" placeholder="Isi keterangan..." required>
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="{{ route('stokopname.index') }}" class="btn btn-secondary">Kembali</a>
</form>

<script>
    function filterDetailObat() {
        const idObat = document.getElementById('nama_obat').value;
        const detailSelect = document.getElementById('id_detailobat');
        const detailOptions = detailSelect.options;

        // Reset selection
        detailSelect.selectedIndex = 0;

        // Hide/show options based on selected obat
        let visibleOptions = 0;
        for (let i = 0; i < detailOptions.length; i++) {
            const opt = detailOptions[i];
            if (!opt.value) continue;

            if (opt.getAttribute('data-obat') === idObat) {
                opt.style.display = '';
                visibleOptions++;
            } else {
                opt.style.display = 'none';
            }
        }

        // Reset form fields
        document.getElementById('tanggal_kadaluarsa').value = '';
        document.getElementById('stok_system').value = '';
        document.getElementById('stok_fisik').value = '';
        document.getElementById('stok_kadaluarsa').value = '';
        document.getElementById('selisih').value = '';
    }

    function updateKadaluarsa() {
        const selected = document.getElementById('id_detailobat').selectedOptions[0];
        if (selected && selected.value) {
            const tgl = selected.getAttribute('data-tgl');
            const stok = selected.getAttribute('data-stok');

            // Set values for form fields
            document.getElementById('tanggal_kadaluarsa').value = tgl || '';
            document.getElementById('stok_system').value = stok !== null && stok !== '' ? stok : 0;

            // Set default stok_fisik to same as system stock
            const stokSystem = parseInt(stok) || 0;
            document.getElementById('stok_fisik').value = stokSystem;

            // Initialize stok_kadaluarsa to 0
            document.getElementById('stok_kadaluarsa').value = 0;

            // Calculate initial selisih
            hitungSelisih();
        } else {
            document.getElementById('tanggal_kadaluarsa').value = '';
            document.getElementById('stok_system').value = '';
            document.getElementById('stok_fisik').value = '';
            document.getElementById('stok_kadaluarsa').value = '';
            document.getElementById('selisih').value = '';
        }
    }

    function hitungSelisih() {
        const stokSystem = parseInt(document.getElementById('stok_system').value) || 0;
        const stokFisik = parseInt(document.getElementById('stok_fisik').value) || 0;

        // Calculate difference between system stock and physical stock
        const selisih = stokSystem - stokFisik;
        document.getElementById('selisih').value = selisih;

        // If physical stock is less than system stock, suggest the difference as kadaluarsa
        if (selisih > 0) {
            document.getElementById('stok_kadaluarsa').value = selisih;
        }
    }

    // Initialize the form data when page loads
    document.addEventListener('DOMContentLoaded', function() {
        const detailObatSelect = document.getElementById('id_detailobat');
        if (detailObatSelect.value) {
            updateKadaluarsa();
        }
    });
</script>
@endsection
