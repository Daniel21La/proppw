@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Transaksi Saya</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Mobil</th>
                    <th>Tanggal Sewa</th>
                    <th>Tanggal Kembali</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($Transaksi as $trx)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $trx->mobil->merk }} - {{ $trx->mobil->nama_mobil }}</td>
                        <td>{{ $trx->tanggal_mulai }}</td>
                        <td>{{ $trx->tanggal_selesai }}</td>
                        <td>Rp{{ number_format($trx->total_harga) }}</td>
                        <td>
                            @if($trx->status === 'pending')
                                <span class="badge bg-warning text-dark">Menunggu</span>
                            @elseif($trx->status === 'disetujui')
                                <span class="badge bg-success">Disetujui</span>
                            @elseif($trx->status === 'selesai')
                                <span class="badge bg-secondary">Selesai</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
