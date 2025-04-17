@extends('layouts.main')
@section('title', 'datauseradmin')

@section('artikel')
    <div class="d-flex justify-content-between mb-3">
        <div>
            <input type="text" class="form-control d-inline-block" style="width: 200px;" placeholder="🔍 Filter user...">
        </div>
        <div>
            <a href="#" class="btn btn-success"><i class="bi bi-plus-circle"></i> Tambah User</a>
        </div>
    </div>

    <table class="table table-bordered text-center">
        <thead class="thead-light">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            {{-- Data dummy sementara --}}
            @for ($i = 1; $i <= 5; $i++)
                <tr>
                    <td>{{ $i }}</td>
                    <td>User {{ $i }}</td>
                    <td>user{{ $i }}@example.com</td>
                    <td>Admin</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i> Update</a>
                        <a href="#" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Delete</a>
                    </td>
                </tr>
            @endfor
        </tbody>
    </table>
@endsection
