@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Laporan Sistem Rental Mobil</h2>
    
    <div class="row my-4">
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body">
                    <h5>Total Pemasukan</h5>
                    <p class="text-success fs-4">Rp{{ number_format($keuangan) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body">
                    <h5>Mobil Tersedia</h5>
                    <p class="text-primary fs-4">{{ $stok_tersedia }} Unit</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-danger">
                <div class="card-body">
                    <h5>Mobil Disewa</h5>
                    <p class="text-danger fs-4">{{ $stok_disewa }} Unit</p>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mt-5">Riwayat Transaksi</h4>
    <table class="table table-bordered table-striped mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pemesan</th>
                <th>Mobil</th>
                <th>Tanggal</th>
                <th>Total Harga</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $key => $t)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $t->user->name ?? '-' }}</td>
                    <td>{{ $t->mobil->nama_mobil ?? '-' }}</td>
                    <td>{{ $t->tanggal_mulai }} s.d {{ $t->tanggal_selesai }}</td>
                    <td>Rp{{ number_format($t->total_harga) }}</td>
                    <td>
                        @if($t->status == 'disetujui')
                            <span class="badge bg-success">Disetujui</span>
                        @elseif($t->status == 'ditolak')
                            <span class="badge bg-danger">Ditolak</span>
                        @else
                            <span class="badge bg-secondary">Pending</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada data transaksi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
