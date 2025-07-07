@extends('layouts.admin')

@section('content')
<div class="min-vh-100 position-relative" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <!-- Animated Background Elements -->
    <div class="position-absolute w-100 h-100 overflow-hidden">
        <div class="floating-shape" style="position: absolute; top: 15%; left: 10%; width: 100px; height: 100px; background: rgba(255,255,255,0.08); border-radius: 50%; animation: float 8s ease-in-out infinite;"></div>
        <div class="floating-shape" style="position: absolute; top: 70%; right: 15%; width: 150px; height: 150px; background: rgba(255,255,255,0.05); border-radius: 50%; animation: float 10s ease-in-out infinite reverse;"></div>
        <div class="floating-shape" style="position: absolute; bottom: 30%; left: 20%; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%; animation: float 6s ease-in-out infinite;"></div>
        <div class="floating-shape" style="position: absolute; top: 40%; right: 40%; width: 60px; height: 60px; background: rgba(255,255,255,0.06); border-radius: 50%; animation: float 7s ease-in-out infinite reverse;"></div>
    </div>

    <div class="container-fluid px-4 py-5 position-relative z-1">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h1 class="h3 mb-0 fw-bold" style="color: #667eea;">
                                    <i class="fas fa-exchange-alt me-2"></i>
                                    Kelola Transaksi
                                </h1>
                                <p class="text-muted mb-0 mt-2">Kelola semua transaksi rental mobil dengan mudah</p>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary btn-sm rounded-3" onclick="refreshTable()">
                                    <i class="fas fa-sync-alt me-1"></i>
                                    Refresh
                                </button>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle rounded-3" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-filter me-1"></i>
                                        Filter
                                    </button>
                                    <ul class="dropdown-menu shadow-lg border-0 rounded-3">
                                        <li><a class="dropdown-item" href="#" onclick="filterByStatus('all')">Semua Status</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="filterByStatus('pending')">Pending</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="filterByStatus('disetujui')">Disetujui</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="filterByStatus('ditolak')">Ditolak</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Section -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-lg border-0 rounded-4 mb-4" role="alert" style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row mb-4 g-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-lg h-100 overflow-hidden position-relative rounded-4" 
                     style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); transition: all 0.3s ease;"
                     onmouseover="this.style.transform='translateY(-5px)'"
                     onmouseout="this.style.transform='translateY(0)'">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-2 fw-semibold">TOTAL TRANSAKSI</h6>
                                <h3 class="fw-bold mb-0">{{ $transaksis->count() }}</h3>
                                <small class="text-white-50">
                                    <i class="fas fa-chart-up me-1"></i>Semua periode
                                </small>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-3 p-3">
                                <i class="fas fa-calendar fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-lg h-100 overflow-hidden position-relative rounded-4" 
                     style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); transition: all 0.3s ease;"
                     onmouseover="this.style.transform='translateY(-5px)'"
                     onmouseout="this.style.transform='translateY(0)'">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-2 fw-semibold">PENDING</h6>
                                <h3 class="fw-bold mb-0">{{ $transaksis->where('status', 'pending')->count() }}</h3>
                                <small class="text-white-50">
                                    <i class="fas fa-clock me-1"></i>Menunggu persetujuan
                                </small>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-3 p-3">
                                <i class="fas fa-hourglass-half fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-lg h-100 overflow-hidden position-relative rounded-4" 
                     style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); transition: all 0.3s ease;"
                     onmouseover="this.style.transform='translateY(-5px)'"
                     onmouseout="this.style.transform='translateY(0)'">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-2 fw-semibold">DISETUJUI</h6>
                                <h3 class="fw-bold mb-0">{{ $transaksis->where('status', 'disetujui')->count() }}</h3>
                                <small class="text-white-50">
                                    <i class="fas fa-thumbs-up me-1"></i>Berhasil disetujui
                                </small>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-3 p-3">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-lg h-100 overflow-hidden position-relative rounded-4" 
                     style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); transition: all 0.3s ease;"
                     onmouseover="this.style.transform='translateY(-5px)'"
                     onmouseout="this.style.transform='translateY(0)'">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-2 fw-semibold">DITOLAK</h6>
                                <h3 class="fw-bold mb-0">{{ $transaksis->where('status', 'ditolak')->count() }}</h3>
                                <small class="text-white-50">
                                    <i class="fas fa-times-circle me-1"></i>Tidak memenuhi syarat
                                </small>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-3 p-3">
                                <i class="fas fa-ban fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Table Card -->
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);">
            <div class="card-header border-0 bg-transparent p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 fw-bold text-dark">
                            <i class="fas fa-table me-2" style="color: #667eea;"></i>
                            Daftar Transaksi
                        </h4>
                        <p class="text-muted mb-0">Kelola dan pantau semua transaksi rental</p>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle rounded-3" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu shadow-lg border-0 rounded-3">
                            <div class="dropdown-header">Aksi:</div>
                            <a class="dropdown-item" href="#" onclick="exportData()">
                                <i class="fas fa-download me-2"></i>Export Data
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="transactionTable">
                        <thead style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                            <tr>
                                <th class="border-0 py-3 px-4 text-muted fw-semibold text-center">
                                    <i class="fas fa-hashtag me-1"></i>No
                                </th>
                                <th class="border-0 py-3 px-4 text-muted fw-semibold">
                                    <i class="fas fa-user me-1"></i>Nama User
                                </th>
                                <th class="border-0 py-3 px-4 text-muted fw-semibold">
                                    <i class="fas fa-car me-1"></i>Mobil
                                </th>
                                <th class="border-0 py-3 px-4 text-muted fw-semibold">
                                    <i class="fas fa-calendar-alt me-1"></i>Tanggal Mulai
                                </th>
                                <th class="border-0 py-3 px-4 text-muted fw-semibold">
                                    <i class="fas fa-calendar-check me-1"></i>Tanggal Selesai
                                </th>
                                <th class="border-0 py-3 px-4 text-muted fw-semibold">
                                    <i class="fas fa-money-bill-wave me-1"></i>Total Harga
                                </th>
                                <th class="border-0 py-3 px-4 text-muted fw-semibold text-center">
                                    <i class="fas fa-info-circle me-1"></i>Status
                                </th>
                                <th class="border-0 py-3 px-4 text-muted fw-semibold text-center">
                                    <i class="fas fa-cogs me-1"></i>Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transaksis as $index => $transaksi)
                                <tr class="align-middle border-0 fade-in" data-status="{{ $transaksi->status }}" style="transition: all 0.3s ease;">
                                    <td class="text-center px-4 py-3">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto fw-bold" 
                                             style="width: 35px; height: 35px; color: #667eea;">
                                            {{ $index + 1 }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-initial rounded-circle d-flex align-items-center justify-content-center me-3 text-white fw-bold" 
                                                 style="width: 45px; height: 45px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                {{ substr($transaksi->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $transaksi->user->name }}</h6>
                                                <small class="text-muted">{{ $transaksi->user->email ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-car text-white"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $transaksi->mobil->nama_mobil ?? '-' }}</div>
                                                @if ($transaksi->mobil)
                                                    <small class="text-muted">{{ $transaksi->mobil->merk ?? '' }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-center">
                                            <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($transaksi->tanggal_mulai)->format('d M Y') }}</div>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($transaksi->tanggal_mulai)->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-center">
                                            <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($transaksi->tanggal_selesai)->format('d M Y') }}</div>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($transaksi->tanggal_selesai)->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-center">
                                            <div class="fw-bold text-success fs-5">Rp{{ number_format($transaksi->total_harga, 0, ',', '.') }}</div>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($transaksi->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($transaksi->tanggal_selesai)) }} hari
                                            </small>
                                        </div>
                                    </td>
                                    <td class="text-center px-4 py-3">
                                        @if ($transaksi->status === 'pending')
                                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                                <i class="fas fa-clock me-1"></i>Pending
                                            </span>
                                        @elseif($transaksi->status === 'disetujui')
                                            <span class="badge bg-success px-3 py-2 rounded-pill">
                                                <i class="fas fa-check me-1"></i>Disetujui
                                            </span>
                                        @else
                                            <span class="badge bg-danger px-3 py-2 rounded-pill">
                                                <i class="fas fa-times me-1"></i>Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center px-4 py-3">
                                        @if ($transaksi->status === 'pending')
                                            <div class="btn-group" role="group">
                                                <form action="{{ route('admin.transaksi.setujui', $transaksi->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm me-1 rounded-3"
                                                        onclick="return confirm('Apakah Anda yakin ingin menyetujui transaksi ini?')"
                                                        data-bs-toggle="tooltip" title="Setujui">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.transaksi.tolak', $transaksi->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm rounded-3"
                                                        onclick="return confirm('Apakah Anda yakin ingin menolak transaksi ini?')"
                                                        data-bs-toggle="tooltip" title="Tolak">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="badge bg-light text-muted border rounded-pill px-3 py-2">
                                                <i class="fas fa-check-circle me-1"></i>Sudah diproses
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="empty-state p-5">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">Belum ada transaksi</h5>
                                            <p class="text-muted">Transaksi akan muncul di sini setelah user melakukan pemesanan</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
        100% { transform: translateY(0px); }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .floating-shape {
        animation: float 6s ease-in-out infinite;
    }

    .fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.05) !important;
        transform: translateX(5px);
    }

    .card:hover {
        transform: translateY(-5px) !important;
        box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
    }

    .btn-outline-primary:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: transparent;
    }

    .btn-outline-secondary:hover {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        border-color: transparent;
        color: white;
    }

    .badge {
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .table tbody tr {
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .table tbody tr:last-child {
        border-bottom: none;
    }

    .z-1 {
        z-index: 1;
    }

    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 0.375rem;
        transition: all 0.3s ease;
    }

    .btn-sm:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .dropdown-menu {
        border-radius: 0.5rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .empty-state {
        padding: 3rem;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 1rem;
        }

        .table-responsive {
            font-size: 0.875rem;
        }

        .btn-group {
            flex-direction: column;
            gap: 0.25rem;
        }

        .card-body {
            padding: 1rem !important;
        }

        .floating-shape {
            display: none;
        }
    }
</style>

<script>
    // Modern JavaScript functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Add staggered fade-in animation to table rows
        const tableRows = document.querySelectorAll('tbody tr');
        tableRows.forEach((row, index) => {
            row.style.animationDelay = `${index * 0.1}s`;
            row.classList.add('fade-in');
        });

        // Add hover effects to cards
        const cards = document.querySelectorAll('.card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 20px 40px rgba(0,0,0,0.15)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '';
            });
        });
    });

    // Filter functionality
    function filterByStatus(status) {
        const rows = document.querySelectorAll('#transactionTable tbody tr');
        
        rows.forEach(row => {
            if (status === 'all' || row.dataset.status === status) {
                row.style.display = '';
                row.classList.add('fade-in');
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Refresh table with animation
    function refreshTable() {
        // Add loading animation
        const tableBody = document.querySelector('#transactionTable tbody');
        tableBody.style.opacity = '0.5';
        
        setTimeout(() => {
            location.reload();
        }, 500);
    }

    // Export data functionality
    function exportData() {
        // Create export animation
        const exportBtn = event.target;
        const originalText = exportBtn.innerHTML;
        
        exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Exporting...';
        exportBtn.disabled = true;
        
        setTimeout(() => {
            exportBtn.innerHTML = originalText;
            exportBtn.disabled = false;
            alert('Fitur export akan diimplementasikan');
        }, 2000);
    }

    // Real-time updates simulation
    setInterval(function() {
        // Add a subtle pulse animation to pending transactions
        const pendingBadges = document.querySelectorAll('.badge.bg-warning');
        pendingBadges.forEach(badge => {
            badge.style.animation = 'pulse 2s infinite';
        });
    }, 5000);

    // Add pulse animation keyframes
    const style = document.createElement('style');
    style.textContent = `
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    `;
    document.head.appendChild(style);
</script>
@endsection