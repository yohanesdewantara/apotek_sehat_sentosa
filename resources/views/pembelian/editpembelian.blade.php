@extends('layouts.main')
@section('title', 'Edit Pembelian')

@section('artikel')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5>Edit Pembelian</h5>
            </div>
            <div class="card-body">
            <form action="{{ route('pembelian.update', $pembelian->id_pembelian) }}" method="POST">
    @csrf
    @method('PUT')

                    <div class="form-group mb-3">
                        <label>ID Pembelian</label>
                        <input type="text" class="form-control" value="{{ $pembelian->id_pembelian }}" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label>Tanggal Pembelian</label>
                        <input type="date" name="tgl_pembelian" class="form-control"
                            value="{{ old('tgl_pembelian', $pembelian->tgl_pembelian) }}" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label>Nama Admin</label>
                        <select name="id_admin" class="form-control" required>
                            <option value="">-- Pilih Admin --</option>
                            @foreach ($admins as $admin)
                                <option value="{{ $admin->id_admin }}" {{ $admin->id_admin == $pembelian->id_admin ? 'selected' : '' }}>
                                    {{ $admin->nama_admin }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <h5 class="mt-4">Detail Obat Dibeli</h5>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Obat</th>
                                <th>Harga Beli</th>
                                <th>Jumlah Beli</th>
                                <th>Total</th>
                                <th>Tanggal Kadaluarsa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pembelian->detailPembelian as $index => $detail)
                                <tr>
                                    <td>
                                    <input type="hidden" name="id_detailbeli[]" value="{{ $detail->id_detailbeli }}">


                                        <input type="text" class="form-control"
                                            value="{{ $detail->detailObat->obat->nama_obat ?? 'N/A' }}" readonly>
                                    </td>
                                    <td>
                                    <input type="number" class="form-control harga-beli"value="{{ $detail->harga_beli }}" readonly>

                                    </td>
                                    <td>
                                    <input type="number" name="jumlah_beli[]" value="{{ $detail->jumlah_beli }}" class="form-control jumlah-beli">




                                    </td>
                                    <td>
                                    <input type="number" class="form-control total-harga"
                                    value="{{ $detail->harga_beli * $detail->jumlah_beli }}" readonly>
                                    </td>
                                    <td>
                                    <input type="date" name="tgl_kadaluarsa[]" class="form-control"
                                    value="{{ \Carbon\Carbon::parse($detail->tgl_kadaluarsa)->format('Y-m-d') }}"
                                    required>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <button type="submit" class="btn btn-primary">Update Pembelian</button>
                    <a href="{{ route('pembelian.index') }}" class="btn btn-secondary">Kembali</a>
                </form>

            </div>
        </div>
    </div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const jumlahInputs = document.querySelectorAll('.jumlah-beli');

    jumlahInputs.forEach(input => {
        input.addEventListener('input', function () {
            const row = input.closest('tr');
            const harga = parseFloat(row.querySelector('.harga-beli').value) || 0;
            const jumlah = parseInt(input.value) || 0;
            const totalInput = row.querySelector('.total-harga');
            totalInput.value = harga * jumlah;
        });
    });
});
</script>
@endsection

