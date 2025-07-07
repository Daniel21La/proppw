@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2 class="mb-4">Kelola Transaksi</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama User</th>
                        <th>Mobil</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksis as $index => $transaksi)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $transaksi->user->name }}</td>
                            <td>{{ $transaksi->mobil->nama_mobil ?? '-' }}</td>
                            <td>{{ $transaksi->tanggal_mulai }}</td>
                            <td>{{ $transaksi->tanggal_selesai }}</td>
                            <td>Rp{{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $transaksi->status === 'disetujui' ? 'success' : ($transaksi->status === 'ditolak' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($transaksi->status) }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('admin.transaksi.setujui', $transaksi->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                                </form>
                                <form action="{{ route('admin.transaksi.tolak', $transaksi->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                </form>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">Belum ada transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
