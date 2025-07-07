@extends('layouts.admin')

@section('content')
<div class="container">
    <h3 class="mb-4">{{ $mobil ? 'Edit Mobil' : 'Tambah Mobil' }}</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan!</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.rentalmobil.save', $mobil->id ?? '') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="merk" class="form-label">Merk</label>
            <input type="text" name="merk" class="form-control" value="{{ old('merk', $mobil->merk ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="nama_mobil" class="form-label">Nama Mobil</label>
            <input type="text" name="nama_mobil" class="form-control" value="{{ old('nama_mobil', $mobil->nama_mobil ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="harga_per_hari" class="form-label">Harga per Hari</label>
            <input type="number" name="harga_per_hari" class="form-control" value="{{ old('harga_per_hari', $mobil->harga_per_hari ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="gambar" class="form-label">Gambar Mobil</label>
            @if (!empty($mobil->gambar))
                <div class="mb-2">
                    <img src="{{ asset('gambar_mobil/' . $mobil->gambar) }}" width="150" class="img-thumbnail">
                </div>
            @endif
            <input type="file" name="gambar" class="form-control">
            <small class="text-muted">Biarkan kosong jika tidak ingin mengganti gambar.</small>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="tersedia" {{ old('status', $mobil->status ?? '') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="disewa" {{ old('status', $mobil->status ?? '') == 'disewa' ? 'selected' : '' }}>Disewa</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">{{ $mobil ? 'Update' : 'Simpan' }}</button>
        <a href="{{ route('admin.rentalmobil.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
