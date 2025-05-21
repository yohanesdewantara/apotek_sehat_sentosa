@extends('layouts.main')
@section('title', 'Edit Obat')

@section('artikel')

    <form action="{{ route('obat.update', $obat->id_obat) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- ID Obat -->
        <div class="form-group mb-3">
            <label for="id_obat">ID Obat</label>
            <input type="text" name="id_obat" id="id_obat" class="form-control" value="{{ $obat->id_obat }}" readonly>
        </div>

        <!-- Nama Obat -->
        <div class="form-group mb-3">
            <label for="nama_obat">Nama Obat</label>
            <input type="text" name="nama_obat" id="nama_obat" class="form-control" value="{{ $obat->nama_obat }}" required>
        </div>

        <!-- Jenis Obat -->
        <div class="form-group mb-3">
            <label for="jenis_obat">Jenis Obat</label>
            <input type="text" name="jenis_obat" id="jenis_obat" class="form-control" value="{{ $obat->jenis_obat }}"
                required>
        </div>

        <!-- Rak Obat -->
        <div class="form-group mb-3">
            <label for="rak_id">Nama Rak</label>
            <select name="rak_id" id="rak_id" class="form-control" required>
                <option value="">-- Pilih Rak --</option>
                @foreach($raks as $rak)
                    <option value="{{ $rak->id_rak }}" {{ $rak->id_rak == $obat->id_rak ? 'selected' : '' }}>
                        {{ $rak->nama_rak }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Stok Total -->
        <div class="form-group mb-3">
            <label for="stok_total">Stok Total</label>
            <input type="number" name="stok_total" id="stok_total" class="form-control" value="{{ $obat->stok_total }}"
                readonly>
        </div>

        <h5 class="mt-4">Detail Obat</h5>

        <div id="detail-obat-wrapper">
            @foreach($obat->detailObat as $index => $detail)
                <div class="row mb-2 detail-obat-item">
                    <!-- ID Detail Obat -->
                    <div class="col-md-2">
                        <label>ID Detail Obat</label>
                        <input type="text" name="id_detailobat[]" class="form-control id-detail"
                            value="{{ $detail->id_detailobat }}" readonly>
                    </div>

                    <!-- Stok -->
                    <div class="col-md-1">
                        <label>Stok</label>
                        <input type="number" name="stok[]" class="form-control stok-input" value="{{ $detail->stok }}" required>
                    </div>

                    <!-- Harga Beli - Explicit handling -->
                    <div class="col-md-2">
                        <label>Harga Beli</label>
                        @php
                            // Try different sources for the harga_beli value in this priority:
                            // 1. Direct from detail_obat table
                            // 2. From related detail_pembelian table
                            // 3. Default to obat main record
                            // 4. Absolute fallback to 0

                            $hargaBeli = 0; // default fallback

                            // Option 1: Direct from DetailObat
                            if (isset($detail->harga_beli) && $detail->harga_beli > 0) {
                                $hargaBeli = $detail->harga_beli;
                            }
                            // Option 2: From DetailPembelian relationship
                            elseif (isset($detail->detailPembelian) &&
                                   isset($detail->detailPembelian->harga_beli) &&
                                   $detail->detailPembelian->harga_beli > 0) {
                                $hargaBeli = $detail->detailPembelian->harga_beli;
                            }
                            // Option 3: From main Obat record
                            elseif (isset($obat->harga_beli) && $obat->harga_beli > 0) {
                                $hargaBeli = $obat->harga_beli;
                            }

                            // For debugging - output in HTML comment
                            echo "<!-- Detail #{$index}: harga_beli direct = " . ($detail->harga_beli ?? 'NULL') .
                                 ", from detailPembelian = " . (isset($detail->detailPembelian) ?
                                 ($detail->detailPembelian->harga_beli ?? 'NULL') : 'relation NULL') .
                                 ", final value = {$hargaBeli} -->\n";
                        @endphp
                        <input type="number" name="harga_beli[]" class="form-control harga-beli-input" value="{{ $hargaBeli }}" required>
                    </div>

                    <!-- Harga Jual -->
                    <div class="col-md-2">
                        <label>Harga Jual</label>
                        @php
                            // Calculate default harga_jual as 20% markup from harga_beli
                            $hargaJual = $detail->harga_jual ?? ($hargaBeli * 1.2);
                        @endphp
                        <input type="number" name="harga_jual[]" class="form-control harga-jual-input" value="{{ $hargaJual }}" required>
                    </div>

                    <!-- Diskon -->
                    <div class="col-md-1">
                        <label>Diskon(%)</label>
                        <input type="number" name="diskon[]" class="form-control" value="{{ $detail->disc ?? 0 }}" min="0" max="100">
                    </div>

                    <!-- Tanggal Kadaluarsa -->
                    <div class="col-md-3">
                        <label>Tanggal Kadaluarsa</label>
                        <input type="date" name="tgl_kadaluarsa[]" class="form-control" value="{{ $detail->tgl_kadaluarsa }}"
                            required>
                    </div>

                    <!-- Tombol Tambah/Hapus -->
                    <div class="col-md-1 d-flex align-items-end">
                        @if($loop->first)
                            <button type="button" class="btn btn-success add-detail">+</button>
                        @else
                            <button type="button" class="btn btn-danger remove-detail">-</button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update Obat</button>
        <a href="{{ route('obat.index') }}" class="btn btn-secondary mt-3">Kembali</a>
    </form>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const wrapper = document.getElementById('detail-obat-wrapper');
            let detailIdCounter = {{ count($obat->detailObat) }};

            function updateStokTotal() {
                let total = 0;
                document.querySelectorAll('.stok-input').forEach(input => {
                    total += parseInt(input.value) || 0;
                });
                document.getElementById('stok_total').value = total;
            }

            // Calculate default harga jual (20% markup)
            function updateHargaJual(hargaBeliInput) {
                const hargaBeli = parseFloat(hargaBeliInput.value) || 0;
                const hargaJualInput = hargaBeliInput.closest('.detail-obat-item').querySelector('.harga-jual-input');
                if (hargaJualInput && !hargaJualInput.dataset.manuallyEdited) {
                    hargaJualInput.value = (hargaBeli * 1.2).toFixed(2);
                }
            }

            // Set up event listeners for all existing harga_beli inputs
            document.querySelectorAll('.harga-beli-input').forEach(input => {
                input.addEventListener('input', function() {
                    updateHargaJual(this);
                });
            });

            // Mark harga_jual as manually edited when user changes it
            document.querySelectorAll('.harga-jual-input').forEach(input => {
                input.addEventListener('input', function() {
                    this.dataset.manuallyEdited = 'true';
                });
            });

            updateStokTotal();
            wrapper.addEventListener('input', e => {
                if (e.target.classList.contains('stok-input')) {
                    updateStokTotal();
                }
                if (e.target.classList.contains('harga-beli-input')) {
                    updateHargaJual(e.target);
                }
            });

            wrapper.addEventListener('click', function (e) {
                if (e.target.classList.contains('add-detail')) {
                    e.preventDefault();
                    const newItem = e.target.closest('.detail-obat-item').cloneNode(true);

                    detailIdCounter++;
                    const idObat = document.getElementById('id_obat').value;
                    // Menggunakan 'new-' sebagai prefix untuk detail baru agar controller bisa membedakannya
                    const newDetailId = 'new-' + idObat + '-' + detailIdCounter;
                    newItem.querySelector('.id-detail').value = newDetailId;

                    // Reset input values
                    newItem.querySelectorAll('input').forEach(input => {
                        if (!input.classList.contains('id-detail')) input.value = '';
                        if (input.classList.contains('harga-jual-input')) {
                            delete input.dataset.manuallyEdited;
                        }
                    });

                    // Ganti tombol tambah dengan tombol hapus
                    const btn = newItem.querySelector('button');
                    btn.classList.remove('btn-success', 'add-detail');
                    btn.classList.add('btn-danger', 'remove-detail');
                    btn.innerText = '-';

                    wrapper.appendChild(newItem);
                    updateStokTotal();

                    // Set up event listeners for new inputs
                    const newHargaBeliInput = newItem.querySelector('.harga-beli-input');
                    if (newHargaBeliInput) {
                        newHargaBeliInput.addEventListener('input', function() {
                            updateHargaJual(this);
                        });
                    }

                    const newHargaJualInput = newItem.querySelector('.harga-jual-input');
                    if (newHargaJualInput) {
                        newHargaJualInput.addEventListener('input', function() {
                            this.dataset.manuallyEdited = 'true';
                        });
                    }
                }

                if (e.target.classList.contains('remove-detail')) {
                    e.preventDefault();
                    e.target.closest('.detail-obat-item').remove();
                    updateStokTotal();
                }
            });
        });
    </script>

@endsection
