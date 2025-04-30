@extends('layouts.main')
@section('title', 'Data User Admin')

@section('artikel')
    <div class="d-flex justify-content-between mb-3">
        <div>

            <form action="{{ route('admin.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control d-inline-block" style="width: 200px;" placeholder="🔍 Filter Nama Admin..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-secondary" style="margin-left: 10px;">Cari</button>
            </form>
        </div>
        <div>
            <a href="{{ route('admin.create') }}" class="btn btn-success"><i class="bi bi-plus-circle"></i> Tambah User</a>
        </div>
    </div>

    <table class="table table-bordered text-center">
        <thead class="thead-light">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($admin as $index => $admin)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $admin->nama_admin }}</td>
                    <td>{{ $admin->email }}</td>
                    <td>
                        <a href="{{ route('admin.edit', $admin->id_admin) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                        <form action="{{ route('admin.destroy', $admin->id_admin) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Hapus user ini?')" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
