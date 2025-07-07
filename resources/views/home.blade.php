@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Daftar Mobil</h2>
    <div class="row">
        @foreach($mobils as $mobil)
            <div class="col-md-4">
                <div class="card mb-4">
                    @if($mobil->gambar)
                        <img src="{{ asset('storage/' . $mobil->gambar) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $mobil->nama_mobil }}</h5>
                        <p class="card-text">Rp{{ number_format($mobil->harga_per_hari) }} / hari</p>
                        <p class="text-muted">Status: 
                            <span class="badge bg-{{ $mobil->status == 'tersedia' ? 'success' : 'secondary' }}">{{ ucfirst($mobil->status) }}</span>
                        </p>
                        @if($mobil->status == 'tersedia')
                            <a href="{{ route('Transaksi.create') }}?mobil_id={{ $mobil->id }}" class="btn btn-primary">Pesan Sekarang</a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
