@extends('layouts.main')
@section('title', 'Edit Stok Opname')

@push('styles')
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush

@section('artikel')
<form action="{{ route('stokopname.update', $stokopname->id_opname) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- ID Opname -->
    <div class="mb-3">
        <label for="id_opname" class="form-label">ID Opname</label>
        <input type="text" id="id_opname" name="id_opname" class="form-control" value="{{ $stokopname->id_opname }}" readonly>
    </div>

    <!-- ID Detail StokOpname -->
    <div class="mb-3">
        <label for="id_detailopname" class="form-label">ID Detail Stok Opname</label>
        <input type="text" id="id_detailopname" name="id_detailopname" class="form-control" value="{{ $detailStokOpname->id_detailopname }}" readonly>
    </div>

    <!-- Tanggal Masuk - Modern & Readonly -->
    <div class="mb-3">
        <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
        <div class="input-group">
            <input type="text" id="tanggal_masuk" name="tanggal_masuk" class="form-control"
            value="{{ \Carbon\Carbon::parse($stokopname->tanggal)->format('d-m-Y') }}" readonly
                style="background-color: #f8f9fa; border-right: none; cursor: default;">
            <span class="input-group-text bg-white" style="border-left: none;">
                <i class="bi bi-calendar3"></i>
            </span>
            <!-- Hidden input to store the actual date value in Y-m-d format -->
            <input type="hidden" name="tanggal" value="{{ $stokopname->tanggal }}">
        </div>
    </div>

    <!-- Pilih Nama Obat -->
    <div class="mb-3">
        <label for="nama_obat" class="form-label">Nama Obat</label>
        <select id="nama_obat" class="form-select" onchange="filterDetailObat()" required>
            <option value="" disabled>-- Pilih Nama Obat --</option>
            @foreach($obats as $obat)
                <option value="{{ $obat->id_obat }}" {{ $stokopname->detailObat->obat->id_obat == $obat->id_obat ? 'selected' : '' }}>
                    {{ $obat->nama_obat }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Pilih Detail Obat -->
    <div class="mb-3">
        <label for="id_detailobat" class="form-label">Detail Obat (Kadaluarsa)</label>
        <select name="id_detailobat" id="id_detailobat" class="form-select" required onchange="updateKadaluarsa()">
            <option value="" disabled>-- Pilih Detail Obat --</option>
            @foreach($detailObats as $detail)
                <option value="{{ $detail->id_detailobat }}"
                    data-obat="{{ $detail->id_obat }}"
                    data-tgl="{{ \Carbon\Carbon::parse($detail->tgl_kadaluarsa)->format('Y-m-d') }}"
                    data-stok="{{ $detail->stok }}"
                    {{ $stokopname->id_detailobat == $detail->id_detailobat ? 'selected' : '' }}
                >
                    {{ $detail->id_detailobat }} - {{ \Carbon\Carbon::parse($detail->tgl_kadaluarsa)->format('d-m-Y') }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Tanggal Kadaluarsa -->
    <div class="mb-3">
        <label for="tanggal_kadaluwarsa" class="form-label">Tanggal Kadaluarsa</label>
        <input type="date" id="tanggal_kadaluwarsa" name="tanggal_kadaluwarsa" class="form-control"
            value="{{ $detailStokOpname->tanggal_kadaluarsa ?? '' }}" readonly>
    </div>

    <!-- Stok Fisik (Adding this from createstok) -->
  <!-- Stok Fisik -->
<div class="mb-3">
    <label for="stok_fisik" class="form-label">Stok Fisik</label>
    <input type="number" id="stok_fisik" name="stok_fisik" class="form-control" min="0" required
           onchange="hitungSelisih()" placeholder="Masukkan jumlah stok fisik..."
           value="{{ $detailStokOpname->stok_kadaluarsa ?? '' }}">
           <!-- Added null coalescing operator to prevent errors if value is null -->
</div>

    <!-- Stok Kadaluarsa -->
    <div class="mb-3">
        <label for="stok_kadaluarsa" class="form-label">Stok System</label>
        <input type="number" id="stok_kadaluarsa" name="stok_kadaluarsa" class="form-control" readonly>
    </div>

    <!-- Selisih (Adding this from createstok) -->
    <div class="mb-3">
        <label for="selisih" class="form-label">Selisih</label>
        <input type="number" id="selisih" name="selisih" class="form-control" readonly>
    </div>

    <!-- Keterangan (Manual Input) -->
    <div class="mb-3">
        <label for="keterangan" class="form-label">Keterangan</label>
        <input type="text" name="keterangan" id="keterangan" class="form-control"
            value="{{ $detailStokOpname->keterangan }}" placeholder="Isi keterangan..." required>
    </div>

    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    <a href="{{ route('stokopname.index') }}" class="btn btn-secondary">Kembali</a>
</form>

<script>
    function filterDetailObat() {
        const idObat = document.getElementById('nama_obat').value;
        const detailOptions = document.getElementById('id_detailobat').options;

        for (let i = 0; i < detailOptions.length; i++) {
            const opt = detailOptions[i];
            if (!opt.value) continue;

            if (opt.getAttribute('data-obat') === idObat) {
                opt.style.display = 'block';
            } else {
                opt.style.display = 'none';
            }
        }

        document.getElementById('id_detailobat').selectedIndex = 0;
        document.getElementById('tanggal_kadaluwarsa').value = '';
        document.getElementById('stok_kadaluarsa').value = '';
        document.getElementById('stok_fisik').value = '';
        document.getElementById('selisih').value = '';
    }

    function updateKadaluarsa() {
        const selected = document.getElementById('id_detailobat').selectedOptions[0];
        if (selected && selected.value) {
            const tgl = selected.getAttribute('data-tgl');
            const stok = selected.getAttribute('data-stok');

            // Set the value for the expiry date field
            document.getElementById('tanggal_kadaluwarsa').value = tgl || '';

            // Set the system stock value (not user editable)
            document.getElementById('stok_kadaluarsa').value = stok !== null && stok !== '' ? stok : 0;

            // Update selisih if stok_fisik has a value
            hitungSelisih();

            // For debugging: Check if the field was populated
            console.log('Date field set to:', tgl);
            console.log('Stok field set to:', stok);
        } else {
            document.getElementById('tanggal_kadaluwarsa').value = '';
            document.getElementById('stok_kadaluarsa').value = 0;
            document.getElementById('selisih').value = '';
        }
    }

    function hitungSelisih() {
        const stokFisik = parseInt(document.getElementById('stok_fisik').value) || 0;
        const stokKadaluarsa = parseInt(document.getElementById('stok_kadaluarsa').value) || 0;

        const selisih = stokFisik - stokKadaluarsa;
        document.getElementById('selisih').value = selisih;
    }

    // Initialize the form data when page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Make sure to show only the options for the selected obat
        filterDetailObat();

        // Ensure the currently selected detail is visible
        const currentDetailId = "{{ $stokopname->id_detailobat }}";
        const detailSelect = document.getElementById('id_detailobat');
        const options = detailSelect.options;

        for (let i = 0; i < options.length; i++) {
            if (options[i].value === currentDetailId) {
                options[i].style.display = 'block';
                detailSelect.selectedIndex = i;
                break;
            }
        }

        // Call updateKadaluarsa to populate the fields
        updateKadaluarsa();

         // Set the physical stock value (stok_fisik) from detail stok opname
    const stokFisik = "{{ $detailStokOpname->stok_kadaluarsa ?? '' }}";
    document.getElementById('stok_fisik').value = stokFisik;
        // Initialize selisih calculation
        hitungSelisih();
    });
</script>
@endsection
