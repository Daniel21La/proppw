@extends('layouts.app')

@section('content')
<style>
    /* Modern Page Title */
    body, html {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    margin: 0;
    padding: 0;
}
    .page-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: #fff;
        text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        margin-bottom: 2rem;
        position: relative;
        display: inline-block;
    }

    .page-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        border-radius: 2px;
        transform: scaleX(0);
        animation: slideIn 1s ease-out 0.5s forwards;
    }

    @keyframes slideIn {
        to { transform: scaleX(1); }
    }

    /* Modern Alert */
    .alert {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 20px;
        color: #fff;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
        border-left: 4px solid #38ef7d;
    }

    .alert::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        border-radius: 2px;
    }

    /* Statistics Cards */
    .stats-row {
        margin-bottom: 2rem;
    }

    .stats-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        text-align: center;
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        border-radius: 2px;
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
    }

    .stats-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: #fff;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .stats-label {
        color: rgba(255, 255, 255, 0.8);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.9rem;
    }

    /* Modern Table Container */
    .table-container {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        border-radius: 25px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        position: relative;
    }

    .table-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: 
            radial-gradient(circle at 20% 30%, rgba(255, 255, 255, 0.05) 0%, transparent 30%),
            radial-gradient(circle at 80% 70%, rgba(255, 255, 255, 0.03) 0%, transparent 30%);
        pointer-events: none;
        z-index: -1;
    }

    /* Modern Table */
/* Fixed Table Alignment */
.table {
    background: transparent;
    color: #fff;
    margin-bottom: 0;
    border-collapse: separate;
    border-spacing: 0;
    table-layout: fixed; /* Tambahkan ini untuk fixed width */
    width: 100%;
}

.table th,
.table td {
    vertical-align: middle; /* Pastikan semua konten center secara vertikal */
    text-align: left; /* Konsisten text alignment */
    padding: 1.5rem 1rem; /* Padding yang sama */
    word-wrap: break-word; /* Untuk text yang panjang */
}

/* Specific column widths untuk alignment yang perfect */
.table th:nth-child(1),
.table td:nth-child(1) {
    width: 8%; /* No */
    text-align: center;
}

.table th:nth-child(2),
.table td:nth-child(2) {
    width: 25%; /* Mobil */
}

.table th:nth-child(3),
.table td:nth-child(3) {
    width: 18%; /* Tanggal Sewa */
}

.table th:nth-child(4),
.table td:nth-child(4) {
    width: 18%; /* Tanggal Kembali */
}

.table th:nth-child(5),
.table td:nth-child(5) {
    width: 18%; /* Total Harga */
    text-align: right;
}

.table th:nth-child(6),
.table td:nth-child(6) {
    width: 13%; /* Status */
    text-align: center;
}

.table th {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(20px);
    border: none;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.9rem;
    color: #fff;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    position: relative;
    white-space: nowrap; /* Prevent text wrapping in header */
}

.table th::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 2px;
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    opacity: 0.7;
}

.table td {
    background: rgba(255, 255, 255, 0.05);
    border: none;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.9);
    font-weight: 500;
    transition: all 0.3s ease;
    position: relative;
}

/* Icon styling untuk konsistensi */
.table th i,
.table td i {
    width: 16px; /* Fixed width untuk semua icon */
    text-align: center;
    margin-right: 8px;
}

/* Car icon specific styling */
.car-icon {
    font-size: 1.2rem;
    color: #4facfe;
    width: 20px;
    display: inline-block;
    text-align: center;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .table th,
    .table td {
        padding: 1rem 0.5rem;
        font-size: 0.85rem;
    }
    
    /* Adjust column widths for mobile */
    .table th:nth-child(1),
    .table td:nth-child(1) {
        width: 10%;
    }
    
    .table th:nth-child(2),
    .table td:nth-child(2) {
        width: 30%;
    }
    
    .table th:nth-child(3),
    .table td:nth-child(3) {
        width: 20%;
    }
    
    .table th:nth-child(4),
    .table td:nth-child(4) {
        width: 20%;
    }
    
    .table th:nth-child(5),
    .table td:nth-child(5) {
        width: 15%;
    }
    
    .table th:nth-child(6),
    .table td:nth-child(6) {
        width: 15%;
    }
}

/* Remove table-responsive if it causes issues */
.table-responsive {
    overflow-x: visible; /* Change from auto to visible */
}

/* Alternative: If you want to keep table-responsive */
.table-responsive .table {
    min-width: 800px; /* Minimum width to prevent cramping */
}
    
</style>

<div class="container mt-4">
    <!-- Page Title -->
    <h1 class="page-title">
        <i class="bi bi-receipt me-3"></i>Transaksi Saya
    </h1>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row stats-row">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number">{{ $Transaksi->count() }}</div>
                <div class="stats-label">Total Transaksi</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number">{{ $Transaksi->where('status', 'pending')->count() }}</div>
                <div class="stats-label">Menunggu</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number">{{ $Transaksi->where('status', 'disetujui')->count() }}</div>
                <div class="stats-label">Disetujui</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number">{{ $Transaksi->where('status', 'selesai')->count() }}</div>
                <div class="stats-label">Selesai</div>
            </div>
        </div>
    </div>

    <!-- Modern Table -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="table align-middle">
    <thead>
        <tr>
            <th><i class="bi bi-hash"></i>No</th>
            <th><i class="bi bi-car-front"></i>Mobil</th>
            <th><i class="bi bi-calendar-event"></i>Tanggal Sewa</th>
            <th><i class="bi bi-calendar-check"></i>Tanggal Kembali</th>
            <th><i class="bi bi-currency-dollar"></i>Total Harga</th>
            <th><i class="bi bi-info-circle"></i>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($Transaksi as $trx)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    <i class="bi bi-car-front-fill car-icon"></i>
                    {{ $trx->mobil->merk }} - {{ $trx->mobil->nama_mobil }}
                </td>
                <td>
                    <i class="bi bi-calendar2-date"></i>
                    {{ \Carbon\Carbon::parse($trx->tanggal_mulai)->format('d M Y') }}
                </td>
                <td>
                    <i class="bi bi-calendar2-check"></i>
                    {{ \Carbon\Carbon::parse($trx->tanggal_selesai)->format('d M Y') }}
                </td>
                <td>
                    <strong>Rp{{ number_format($trx->total_harga, 0, ',', '.') }}</strong>
                </td>
                <td>
                    @if($trx->status === 'pending')
                        <span class="badge bg-warning">
                            <i class="bi bi-clock"></i>Menunggu
                        </span>
                    @elseif($trx->status === 'disetujui')
                        <span class="badge bg-success">
                            <i class="bi bi-check-circle"></i>Disetujui
                        </span>
                    @elseif($trx->status === 'selesai')
                        <span class="badge bg-secondary">
                            <i class="bi bi-check-all"></i>Selesai
                        </span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="empty-state">
                    <div>Belum ada transaksi yang dilakukan.</div>
                    <small class="text-muted">Mulai pesan mobil untuk melihat riwayat transaksi Anda.</small>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
        </div>
    </div>
</div>
@endsection