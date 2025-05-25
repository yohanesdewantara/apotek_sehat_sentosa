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
                    style="display: none;"
                >
                    {{ $detail->id_detailobat }} - {{ \Carbon\Carbon::parse($detail->tgl_kadaluarsa)->format('d-m-Y') }}
                </option>
            @endforeach
        </select>
        <small class="text-muted">Pilih detail obat berdasarkan tanggal kadaluarsa</small>
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
        <label for="stok_fisik" class="form-label">Stok Fisik <span class="text-danger">*</span></label>
        <input type="number" id="stok_fisik" name="stok_fisik" class="form-control" min="0" required
               onchange="hitungSelisih()" oninput="validateNonNegative(this)" placeholder="Masukkan jumlah stok fisik yang ditemukan...">
        <small class="text-muted">Jumlah stok fisik yang ditemukan saat pengecekan</small>
    </div>

    <!-- Jenis Penyesuaian -->
    <div class="mb-3">
        <label for="jenis" class="form-label">Jenis Penyesuaian <span class="text-danger">*</span></label>
        <select id="jenis" name="jenis" class="form-select" required onchange="handleJenisPenyesuaian()">
            <option value="" disabled selected>-- Pilih Jenis Penyesuaian --</option>
            <option value="penambahan">Penambahan Stok</option>
            <option value="pengurangan">Pengurangan Stok</option>
            <option value="normal">Tidak Ada Penyesuaian</option>
        </select>
        <small class="text-muted">Pilih jenis penyesuaian berdasarkan hasil stok opname</small>
    </div>

    <!-- Jumlah Penyesuaian (Qty) -->
    <div class="mb-3" id="div_qty" style="display: none;">
        <label for="qty" class="form-label">Jumlah Penyesuaian (Qty)</label>
        <input type="number" id="qty" name="qty" class="form-control" min="0"
               onchange="hitungStokAkhir()" oninput="validateNonNegative(this)" placeholder="Masukkan jumlah penyesuaian...">
        <small class="text-muted" id="help_qty">Jumlah yang akan disesuaikan</small>
    </div>

    <!-- Stok Setelah Penyesuaian -->
    <div class="mb-3">
        <label for="stok_akhir" class="form-label">Stok Setelah Penyesuaian</label>
        <input type="number" id="stok_akhir" name="stok_akhir" class="form-control" readonly>
        <small class="text-muted">Stok sistem setelah dilakukan penyesuaian</small>
    </div>

    <!-- Selisih -->
    <div class="mb-3">
        <label for="selisih" class="form-label">Selisih (System - Fisik)</label>
        <input type="number" id="selisih" class="form-control" readonly>
        <small class="text-muted">Selisih antara stok sistem dan stok fisik</small>
    </div>

    <!-- Status Alert -->
    <div id="status_alert" class="mb-3" style="display: none;">
        <div class="alert" id="alert_content">
            <i class="bi bi-info-circle"></i>
            <span id="alert_text"></span>
        </div>
    </div>

    <!-- Keterangan -->
    <div class="mb-3">
        <label for="keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
        <textarea name="keterangan" id="keterangan" class="form-control" rows="3" placeholder="Jelaskan detail hasil stok opname..." required></textarea>
        <small class="text-muted">Berikan keterangan detail mengenai hasil stok opname</small>
    </div>

    <button type="submit" class="btn btn-primary" id="btn_submit" disabled>Simpan</button>
    <a href="{{ route('stokopname.index') }}" class="btn btn-secondary">Kembali</a>
</form>

<script>
    // Data untuk debugging
    const detailObatsData = @json($detailObats);
    const obatsData = @json($obats);

    console.log('Detail Obats Data:', detailObatsData);
    console.log('Obats Data:', obatsData);

    // Fungsi untuk memvalidasi input tidak boleh minus
    function validateNonNegative(input) {
        if (input.value < 0) {
            input.value = 0;
            showAlert('warning', 'Nilai tidak boleh minus!');
        }
    }

    // Fungsi filter detail obat yang sudah diperbaiki
    function filterDetailObat() {
        const idObat = document.getElementById('nama_obat').value;
        const detailSelect = document.getElementById('id_detailobat');

        console.log('Selected obat ID:', idObat);
        console.log('Total detail options:', detailSelect.options.length);

        // Reset selection ke placeholder
        detailSelect.selectedIndex = 0;

        let visibleOptions = 0;

        // Loop through all options (skip first placeholder option)
        for (let i = 1; i < detailSelect.options.length; i++) {
            const option = detailSelect.options[i];
            const optionObatId = option.getAttribute('data-obat');

            console.log(`Option ${i}: obat_id=${optionObatId}, selected_id=${idObat}`);

            if (optionObatId === idObat) {
                option.style.display = '';
                option.disabled = false;
                visibleOptions++;
                console.log(`Showing option: ${option.textContent}`);
            } else {
                option.style.display = 'none';
                option.disabled = true;
            }
        }

        console.log('Visible options:', visibleOptions);

        // Update placeholder text based on availability
        const placeholderOption = detailSelect.options[0];
        if (visibleOptions === 0 && idObat) {
            placeholderOption.textContent = 'Tidak ada detail obat tersedia untuk obat ini';
            placeholderOption.style.color = '#dc3545'; // Red color
            showAlert('warning', 'Tidak ada detail obat yang tersedia untuk obat yang dipilih');
        } else {
            placeholderOption.textContent = '-- Pilih Detail Obat --';
            placeholderOption.style.color = ''; // Reset color
            hideStatusAlert();
        }

        // Reset form fields
        resetFormFields();
    }

    // Fungsi update kadaluarsa yang sudah diperbaiki
    function updateKadaluarsa() {
        const detailSelect = document.getElementById('id_detailobat');
        const selectedOption = detailSelect.selectedOptions[0];

        console.log('Selected detail option:', selectedOption);

        if (selectedOption && selectedOption.value && !selectedOption.disabled) {
            const tgl = selectedOption.getAttribute('data-tgl');
            const stok = selectedOption.getAttribute('data-stok');

            console.log('Data from selected option:', { tgl, stok });

            // Set values for form fields
            document.getElementById('tanggal_kadaluarsa').value = tgl || '';
            document.getElementById('stok_system').value = stok !== null && stok !== '' ? parseInt(stok) : 0;

            // Reset other dependent fields
            document.getElementById('stok_fisik').value = '';
            document.getElementById('jenis').selectedIndex = 0;
            document.getElementById('qty').value = '';
            document.getElementById('stok_akhir').value = '';
            document.getElementById('selisih').value = '';

            // Hide qty div
            document.getElementById('div_qty').style.display = 'none';
            hideStatusAlert();

            showAlert('success', 'Detail obat berhasil dipilih. Silakan masukkan stok fisik.');
        } else {
            resetFormFields();
        }
        validateForm();
    }

    function hitungSelisih() {
        const stokSystem = parseInt(document.getElementById('stok_system').value) || 0;
        const stokFisik = parseInt(document.getElementById('stok_fisik').value) || 0;

        // Prevent negative stock input
        if (stokFisik < 0) {
            document.getElementById('stok_fisik').value = 0;
            showAlert('warning', 'Stok fisik tidak boleh minus!');
            return;
        }

        // Calculate difference between system stock and physical stock
        const selisih = stokSystem - stokFisik;
        document.getElementById('selisih').value = selisih;

        console.log('Perhitungan selisih:', { stokSystem, stokFisik, selisih });

        // Auto suggest jenis penyesuaian
        autoSuggestPenyesuaian(selisih);

        // Update status alert
        updateStatusAlert(selisih);

        validateForm();
    }

    function autoSuggestPenyesuaian(selisih) {
        const jenisPenyesuaian = document.getElementById('jenis');

        if (selisih > 0) {
            // System > Fisik, suggest pengurangan
            jenisPenyesuaian.value = 'pengurangan';
            document.getElementById('qty').value = Math.abs(selisih);
            console.log('Auto suggest: pengurangan', Math.abs(selisih));
        } else if (selisih < 0) {
            // System < Fisik, suggest penambahan
            jenisPenyesuaian.value = 'penambahan';
            document.getElementById('qty').value = Math.abs(selisih);
            console.log('Auto suggest: penambahan', Math.abs(selisih));
        } else {
            // System = Fisik, no adjustment needed
            jenisPenyesuaian.value = 'normal';
            document.getElementById('qty').value = 0;
            console.log('Auto suggest: normal');
        }

        handleJenisPenyesuaian();
    }

    function handleJenisPenyesuaian() {
        const jenis = document.getElementById('jenis').value;
        const divQty = document.getElementById('div_qty');
        const inputQty = document.getElementById('qty');
        const helpText = document.getElementById('help_qty');

        console.log('Handle jenis penyesuaian:', jenis);

        if (jenis === 'normal') {
            divQty.style.display = 'none';
            inputQty.value = 0;
        } else {
            divQty.style.display = 'block';

            if (jenis === 'penambahan') {
                helpText.textContent = 'Jumlah yang akan ditambahkan ke stok sistem';
                inputQty.placeholder = 'Masukkan jumlah penambahan...';
                inputQty.removeAttribute('max');
            } else if (jenis === 'pengurangan') {
                helpText.textContent = 'Jumlah yang akan dikurangi dari stok sistem';
                inputQty.placeholder = 'Masukkan jumlah pengurangan...';

                // Set max value untuk pengurangan
                const stokSystem = parseInt(document.getElementById('stok_system').value) || 0;
                inputQty.setAttribute('max', stokSystem);
            }
        }

        hitungStokAkhir();
        validateForm();
    }

    function hitungStokAkhir() {
        const stokSystem = parseInt(document.getElementById('stok_system').value) || 0;
        const jenis = document.getElementById('jenis').value;
        const qty = parseInt(document.getElementById('qty').value) || 0;

        console.log('Hitung stok akhir:', { stokSystem, jenis, qty });

        // Prevent negative qty input
        if (qty < 0) {
            document.getElementById('qty').value = 0;
            showAlert('warning', 'Qty tidak boleh minus!');
            return;
        }

        let stokAkhir = stokSystem;

        if (jenis === 'penambahan') {
            stokAkhir = stokSystem + qty;
        } else if (jenis === 'pengurangan') {
            // Validasi qty tidak boleh melebihi stok sistem
            if (qty > stokSystem) {
                document.getElementById('qty').value = stokSystem;
                showAlert('warning', `Qty pengurangan tidak boleh melebihi stok sistem (${stokSystem})!`);
                return;
            }
            stokAkhir = stokSystem - qty;
        }

        // Pastikan stok akhir tidak minus
        if (stokAkhir < 0) {
            stokAkhir = 0;
        }

        console.log('Stok akhir calculated:', stokAkhir);

        document.getElementById('stok_akhir').value = stokAkhir;
        validateForm();
    }

    function updateStatusAlert(selisih) {
        if (selisih > 0) {
            showAlert('warning', `Stok sistem lebih besar ${selisih} unit dari stok fisik. Kemungkinan ada stok hilang atau rusak.`);
        } else if (selisih < 0) {
            showAlert('info', `Stok fisik lebih besar ${Math.abs(selisih)} unit dari sistem. Kemungkinan ada stok yang belum tercatat.`);
        } else {
            showAlert('success', 'Stok sistem dan fisik sudah sesuai. Tidak perlu penyesuaian.');
        }
    }

    function showAlert(type, message) {
        const alertDiv = document.getElementById('status_alert');
        const alertContent = document.getElementById('alert_content');
        const alertText = document.getElementById('alert_text');

        // Map alert types to Bootstrap classes
        const alertClasses = {
            'success': 'alert alert-success',
            'warning': 'alert alert-warning',
            'info': 'alert alert-info',
            'danger': 'alert alert-danger'
        };

        alertContent.className = alertClasses[type] || 'alert alert-info';
        alertText.textContent = message;
        alertDiv.style.display = 'block';

        console.log('Show alert:', type, message);
    }

    function hideStatusAlert() {
        document.getElementById('status_alert').style.display = 'none';
    }

    function resetFormFields() {
        const fields = [
            'tanggal_kadaluarsa',
            'stok_system',
            'stok_fisik',
            'qty',
            'stok_akhir',
            'selisih'
        ];

        fields.forEach(fieldId => {
            document.getElementById(fieldId).value = '';
        });

        document.getElementById('jenis').selectedIndex = 0;
        document.getElementById('div_qty').style.display = 'none';
        hideStatusAlert();
        validateForm();

        console.log('Form fields reset');
    }

    function validateForm() {
        const detailObat = document.getElementById('id_detailobat').value;
        const stokFisik = document.getElementById('stok_fisik').value;
        const jenis = document.getElementById('jenis').value;
        const qty = document.getElementById('qty').value;
        const keterangan = document.getElementById('keterangan').value.trim();
        const btnSubmit = document.getElementById('btn_submit');

        // Basic validation
        let isValid = detailObat !== '' &&
                     stokFisik !== '' &&
                     parseInt(stokFisik) >= 0 &&
                     jenis !== '' &&
                     keterangan !== '';

        // Validate qty for non-normal types
        if (jenis === 'penambahan' || jenis === 'pengurangan') {
            isValid = isValid && qty !== '' && parseInt(qty) >= 0;

            // Additional validation for pengurangan
            if (jenis === 'pengurangan') {
                const stokSystem = parseInt(document.getElementById('stok_system').value) || 0;
                isValid = isValid && parseInt(qty) <= stokSystem;
            }
        }

        btnSubmit.disabled = !isValid;

        console.log('Form validation:', {
            detailObat: detailObat !== '',
            stokFisik: stokFisik !== '' && parseInt(stokFisik) >= 0,
            jenis: jenis !== '',
            keterangan: keterangan !== '',
            qty: jenis === 'normal' || (qty !== '' && parseInt(qty) >= 0),
            isValid
        });
    }

    // Event listeners setup
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM Content Loaded - Setting up event listeners');

        // Add input event listeners
        const stokFisikInput = document.getElementById('stok_fisik');
        if (stokFisikInput) {
            stokFisikInput.addEventListener('input', function() {
                validateNonNegative(this);
                validateForm();
            });
            stokFisikInput.addEventListener('change', hitungSelisih);
        }

        const qtyInput = document.getElementById('qty');
        if (qtyInput) {
            qtyInput.addEventListener('input', function() {
                validateNonNegative(this);
                validateForm();
            });
            qtyInput.addEventListener('change', hitungStokAkhir);
        }

        const jenisSelect = document.getElementById('jenis');
        if (jenisSelect) {
            jenisSelect.addEventListener('change', validateForm);
        }

        const keteranganTextarea = document.getElementById('keterangan');
        if (keteranganTextarea) {
            keteranganTextarea.addEventListener('input', validateForm);
        }

        const detailObatSelect = document.getElementById('id_detailobat');
        if (detailObatSelect) {
            detailObatSelect.addEventListener('change', validateForm);
        }

        // Initial validation
        validateForm();

        console.log('Event listeners setup complete');
    });
</script>
@endsection
