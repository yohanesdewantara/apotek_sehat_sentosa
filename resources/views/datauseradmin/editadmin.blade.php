@extends('layouts.main')
@section('title', 'Edit Admin')

@section('artikel')
    <h3>Edit Admin</h3>

    <form action="{{ route('admin.update', $admin->id_admin) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama_admin" class="form-label">Nama Admin</label>
            <input type="text" class="form-control" id="nama_admin" name="nama_admin" value="{{ $admin->nama_admin }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email Admin</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $admin->email }}" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password Baru (Opsional)</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
        </div>

        <button type="submit" class="btn btn-primary">Update Admin</button>
    </form>
@endsection
