@extends('layouts.main')
@section('title', 'Stok Opname')

@section('artikel')
    <div class="d-flex justify-content-between mb-3">
        <div>
            <!-- Filter Form -->
            <form action="{{ route('stokopname.index') }}" method="GET" class="d-flex">
                <input type="text" name="id_opname" class="form-control d-inline-block" style="width: 200px;"
                    placeholder="🔍 Filter ID Opname..." value="{{ request('id_opname') }}">
                <button type="submit" class="btn btn-secondary" style="margin-left: 10px;">Cari</button>
            </form>
        </div>
        <div>
            <!-- Tambah Stok Opname Button -->
            <a href="{{ route('stokopname.create') }}" class="btn btn-success"><i class="bi bi-plus-circle"></i> Tambah Stok
                Opname</a>
        </div>
    </div>


    <!-- Alert Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif




    <!-- Table -->
    <table class="table table-bordered text-center">
        <thead class="thead-light">
            <tr>
                <th>No</th>
                <th>ID Detail Stokopname</th>
                <th>Nama Obat</th>
                <th>Nama Admin</th>
                <th>Tanggal Masuk</th>
                <th>Stok Fisik</th>
                <th>Stok Kadaluarsa</th>
                <th>Selisih</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stokopnames as $index => $opname)
                @php
                    $detail = $opname->detailStokOpname->first(); // Ambil 1 data detail stokopname
                    $stokFisik = $detail->stok_kadaluarsa ?? 0; // stok_fisik disimpan sebagai stok_kadaluarsa
                    $stokKadaluarsa = $opname->detailObat->stok ?? 0; // Ambil stok dari detail_obat
                    $selisih = $stokFisik - $stokKadaluarsa;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $detail->id_detailopname ?? '-' }}</td>
                    <td>{{ $opname->detailObat->obat->nama_obat ?? '-' }}</td>
                    <td>{{ $opname->admin->nama_admin ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($opname->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ $stokFisik }}</td>
                    <td>{{ $stokKadaluarsa }}</td>
                    <td>{{ $selisih }}</td>
                    <td>{{ $detail->keterangan ?? '-' }}</td>
                    <td>
                        <!-- Detail Button -->
                        <a href="{{ route('stokopname.show', $opname->id_opname) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                        <!-- Edit Button -->
                        <a href="{{ route('stokopname.edit', $opname->id_opname) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                        <!-- Delete Form -->
                        <form action="{{ route('stokopname.destroy', $opname->id_opname) }}" method="POST"
                            style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Hapus stok opname ini?')"
                                class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
