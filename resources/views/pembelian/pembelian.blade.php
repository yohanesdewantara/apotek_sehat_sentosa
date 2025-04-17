@extends('layouts.main')
@section('title', 'pembelian')

@section('artikel')
    <div class="d-flex justify-content-between mb-3">
        <div>
            <input type="text" class="form-control d-inline-block" style="width: 200px;" placeholder="🔍 Filter obat...">
        </div>
        <div>
        <a href="{{ route('pembelian.create') }}" class="btn btn-primary mb-3">+ Tambah</a>
        </div>
    </div>

    <table class="table table-bordered text-center">
        <thead class="thead-light">
            <tr>
                <th>No</th>
                <th>Nama Obat</th>
                <th>Jumlah</th>
                <th>Tanggal Pembelian</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $pembelian)
                @foreach($pembelian->detailPembelian as $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $detail->obat->obat->nama_obat ?? '-' }}</td>
                        <td>{{ $detail->jumlah_beli }}</td>
                        <td>{{ $pembelian->tgl_pembelian }}</td>
                        <td>
                            <a href="{{ route('pembelian.edit', $pembelian->id_pembelian) }}"
                                class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('pembelian.destroy', $pembelian->id_pembelian) }}" method="POST"
                                style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Hapus data ini?')" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>

    </table>
@endsection
