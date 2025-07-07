@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Pesan Mobil</h2>

    <div class="row">
        @foreach($mobils as $mobil)
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="{{ asset('gambar_mobil/' . $mobil->gambar) }}" class="card-img-top" alt="{{ $mobil->nama_mobil }}" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $mobil->merk }} - {{ $mobil->nama_mobil }}</h5>
                        <p class="card-text">{{ $mobil->deskripsi }}</p>
                        <p class="card-text"><strong>Rp{{ number_format($mobil->harga_per_hari) }}/hari</strong></p>
                        <span class="badge bg-success">{{ ucfirst($mobil->status) }}</span>

                        @if($mobil->status === 'tersedia')
                            <form action="{{ route('Transaksi.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="mobil_id" value="{{ $mobil->id }}">
                                <input type="hidden" name="tanggal_mulai" value="{{ date('Y-m-d') }}">
                                <input type="hidden" name="tanggal_selesai" value="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                <button type="submit" class="btn btn-primary mt-2">Sewa Sekarang</button>
                            </form>
                        @else
                            <button class="btn btn-secondary mt-2" disabled>Sudah Disewa</button>
                        @endif

                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
