@extends('layouts.main')

@section('title', 'Diskon Obat')

@section('artikel')
<div class="container py-4">
    <h2 class="mb-4">Beri Diskon Obat</h2>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card p-4">
        <form action="{{ route('obat.simpanDiskon', $obat->id_detailobat) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">ID Detail Obat</label>
                <input type="text" class="form-control" value="{{ $obat->id_detailobat }}" readonly>
                <small class="text-muted">Diskon hanya akan diterapkan pada batch obat ini</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Obat</label>
                <input type="text" class="form-control" value="{{ $obat->obat->nama_obat ?? '-' }}" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal Kadaluarsa</label>
                <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($obat->tgl_kadaluarsa)->format('j/n/Y') }}" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Jumlah Stok</label>
                <input type="text" class="form-control" value="{{ $obat->stok }}" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Harga Jual Awal</label>
                <input type="text" class="form-control" id="harga_jual_awal" value="Rp {{ number_format($obat->obat->harga_jual ?? 0, 0, ',', '.') }}" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Diskon (%)</label>
                <input type="number" class="form-control" name="diskon" id="diskon" placeholder="Contoh: 10" min="0" max="100" required value="{{ old('diskon', $obat->disc) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Harga Setelah Diskon</label>
                <input type="text" class="form-control" id="harga_setelah_diskon" readonly>
                <small class="text-success">Diskon ini hanya berlaku untuk batch dengan ID detail obat {{ $obat->id_detailobat }}</small>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Diskon</button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary ms-2">Batal</a>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const diskonInput = document.getElementById('diskon');
        const hargaSetelahDiskonInput = document.getElementById('harga_setelah_diskon');
        const hargaAwal = {!! json_encode($obat->obat->harga_jual ?? 0) !!};

        function hitungDiskon() {
            const diskonPersen = parseFloat(diskonInput.value) || 0;
            const hargaDiskon = hargaAwal * (diskonPersen / 100);
            const hargaSetelahDiskon = hargaAwal - hargaDiskon;

            hargaSetelahDiskonInput.value = 'Rp ' + hargaSetelahDiskon.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        }

        diskonInput.addEventListener('input', hitungDiskon);
        hitungDiskon();
    });
</script>
@endsection
