@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">List Mobil</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @foreach($mobils as $mobil)
            <div class="col-md-4">
                <div class="card mb-4 {{ $mobil->status !== 'tersedia' ? 'bg-light text-muted' : '' }}">
                    <img src="{{ asset('gambar_mobil/' . $mobil->gambar) }}" class="card-img-top" alt="{{ $mobil->nama_mobil }}" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $mobil->merk }} - {{ $mobil->nama_mobil }}</h5>
                        <p class="card-text">{{ $mobil->deskripsi }}</p>
                        <p class="card-text"><strong>Rp{{ number_format($mobil->harga_per_hari) }}/hari</strong></p>
                        <span class="badge {{ $mobil->status === 'tersedia' ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($mobil->status) }}
                        </span>
                        @if($mobil->status === 'tersedia')
                            <button class="btn btn-primary select-mobil mt-2" data-id="{{ $mobil->id }}">Sewa Mobil Ini</button>
                        @else
                            <button class="btn btn-secondary mt-2" disabled>Sudah Disewa</button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <hr>

    <!-- Form Pemesanan -->
    <div class="card mt-4" id="formContainer" style="display: none;">
        <div class="card-header">Form Pemesanan Mobil</div>
        <div class="card-body">
            <form action="{{ route('Transaksi.store') }}" method="POST" id="formTransaksi">
                @csrf
                <input type="hidden" name="mobil_id" id="selectedMobilId">

                <div class="mb-3">
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-success">Sewa Sekarang</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const buttons = document.querySelectorAll('.select-mobil');
        const formContainer = document.getElementById('formContainer');
        const mobilIdInput = document.getElementById('selectedMobilId');

        buttons.forEach(button => {
            button.addEventListener('click', function () {
                const mobilId = this.dataset.id;
                mobilIdInput.value = mobilId;
                formContainer.style.display = 'block';
                formContainer.scrollIntoView({ behavior: 'smooth' });
            });
        });
    });
</script>
@endsection
