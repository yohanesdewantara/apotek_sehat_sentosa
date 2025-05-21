@extends('layouts.main')
@section('title', 'Detail Obat')

@section('artikel')
<div class="card">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">Detail Obat</h5>
    </div>
    <div class="card-body">

        <!-- Form untuk ID Obat dan Nama Obat -->
        <div class="mb-3">
            <label for="id_obat">ID Obat</label>
            <input type="text" name="id_obat" id="id_obat" class="form-control"
                value="{{ $obat->id_obat }}" readonly>
        </div>

        <div class="mb-3">
            <label for="nama_obat">Nama Obat</label>
            <input type="text" name="nama_obat" id="nama_obat" class="form-control"
                value="{{ $obat->nama_obat }}" readonly>
        </div>

        <div class="mb-3">
            <label for="stok_total">Stok Total</label>
            <input type="text" name="stok_total" id="stok_total" class="form-control"
                value="{{ $obat->stok_total }}" readonly>
        </div>

        <div class="mb-3">
            <label for="jenis_obat">Jenis Obat</label>
            <input type="text" name="jenis_obat" id="jenis_obat" class="form-control"
                value="{{ $obat->jenis_obat }}" readonly>
        </div>

        <div class="mb-3">
            <label for="nama_rak">Nama Rak</label>
            <input type="text" name="nama_rak" id="nama_rak" class="form-control"
                value="{{ $obat->rakObat->nama_rak ?? 'Tidak Tersedia' }}" readonly>
        </div>

        <!-- Tabel Detail Obat -->
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr class="text-center">
                    <th>No</th>
                    <th>ID Detail Obat</th>
                    <th>Jumlah Stok</th>
                    <th>Diskon</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Tanggal Kadaluarsa</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                    $grandTotal = 0;
                @endphp
                @foreach($obat->detailObat as $detail)
                    @php
                        // Ambil harga jual dari obat atau detail jika tersedia
                        $hargaJualAwal = $obat->harga_jual;

                        // Hitung harga jual dengan diskon
                        $diskon = $detail->disc ?? 0;
                        $hargaJualDiskon = $hargaJualAwal * (1 - ($diskon / 100));

                        // Hitung total untuk baris ini
                        $total = $detail->stok * $detail->harga_beli;
                        $grandTotal += $total;
                    @endphp
                    <tr class="text-center">
                        <td>{{ $no++ }}</td>
                        <td>{{ $detail->id_detailobat }}</td>
                        <td>{{ $detail->stok }}</td>
                        <td>{{ $detail->disc }}%</td>
                        <td>Rp {{ number_format($detail->harga_beli ?? $obat->harga_beli, 0, ',', '.') }}</td>
                        <td>
                            @if($diskon > 0)
                                <span class="text-decoration-line-through text-muted">Rp {{ number_format($hargaJualAwal, 0, ',', '.') }}</span>
                                <br>
                                <span class="text-danger">Rp {{ number_format($hargaJualDiskon, 0, ',', '.') }}</span>
                            @else
                                Rp {{ number_format($hargaJualAwal, 0, ',', '.') }}
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($detail->tgl_kadaluarsa)->format('d/m/Y') }}</td>

                        <td>Rp {{ number_format($total, 0, ',', '.') }}</td>

                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="text-center fw-bold bg-light">
                    <td colspan="7">Total</td>
                    <td>Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Kembali Button -->
        <a href="{{ route('obat.index') }}" class="btn btn-secondary mt-3">
            <i class="bi bi-arrow-left-circle"></i> Kembali
        </a>

    </div>
</div>
@endsection
