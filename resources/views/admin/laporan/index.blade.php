@extends('layouts.admin')

@section('content')
<div class="min-vh-100 position-relative" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <!-- Animated Background Elements -->
    <div class="position-absolute w-100 h-100 overflow-hidden">
        <div class="floating-shape" style="position: absolute; top: 10%; left: 15%; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%; animation: float 6s ease-in-out infinite;"></div>
        <div class="floating-shape" style="position: absolute; top: 60%; right: 20%; width: 120px; height: 120px; background: rgba(255,255,255,0.05); border-radius: 50%; animation: float 8s ease-in-out infinite reverse;"></div>
        <div class="floating-shape" style="position: absolute; bottom: 20%; left: 25%; width: 100px; height: 100px; background: rgba(255,255,255,0.08); border-radius: 50%; animation: float 7s ease-in-out infinite;"></div>
    </div>

    <div class="container py-5 position-relative z-1">
        <!-- Header Section -->
        <div class="text-center mb-5">
            <h1 class="text-white mb-3 fw-bold display-4">Dashboard Analytics</h1>
            <p class="text-white-50 fs-5">Sistem Rental Mobil - Laporan Komprehensif</p>
            <div class="text-white-50 small">
                <i class="fas fa-calendar me-2"></i>
                {{ date('d F Y') }}
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-5">
            <!-- Total Pemasukan -->
            <div class="col-md-4">
                <div class="card border-0 shadow-lg h-100 overflow-hidden position-relative" 
                     style="background: linear-gradient(135deg, #00c9ff 0%, #92fe9d 100%); transform: translateY(0); transition: all 0.3s ease;"
                     onmouseover="this.style.transform='translateY(-5px)'"
                     onmouseout="this.style.transform='translateY(0)'">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-white-50 mb-2 fw-semibold">TOTAL PEMASUKAN</h6>
                                <h3 class="fw-bold mb-0">Rp{{ number_format($keuangan) }}</h3>
                                <small class="text-white-50">
                                    <i class="fas fa-arrow-up me-1"></i>+12.5% dari bulan lalu
                                </small>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-3 p-3">
                                <i class="fas fa-money-bill-wave fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <div class="position-absolute bottom-0 start-0 w-100 h-25" 
                         style="background: linear-gradient(transparent, rgba(0,0,0,0.1));"></div>
                </div>
            </div>

            <!-- Mobil Tersedia -->
            <div class="col-md-4">
                <div class="card border-0 shadow-lg h-100 overflow-hidden position-relative" 
                     style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); transform: translateY(0); transition: all 0.3s ease;"
                     onmouseover="this.style.transform='translateY(-5px)'"
                     onmouseout="this.style.transform='translateY(0)'">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-white-50 mb-2 fw-semibold">MOBIL TERSEDIA</h6>
                                <h3 class="fw-bold mb-0">{{ $stok_tersedia }} Unit</h3>
                                <small class="text-white-50">
                                    <i class="fas fa-check-circle me-1"></i>Siap untuk disewa
                                </small>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-3 p-3">
                                <i class="fas fa-car-side fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <div class="position-absolute bottom-0 start-0 w-100 h-25" 
                         style="background: linear-gradient(transparent, rgba(0,0,0,0.1));"></div>
                </div>
            </div>

            <!-- Mobil Disewa -->
            <div class="col-md-4">
                <div class="card border-0 shadow-lg h-100 overflow-hidden position-relative" 
                     style="background: linear-gradient(135deg, #ff6b6b 0%, #ffa726 100%); transform: translateY(0); transition: all 0.3s ease;"
                     onmouseover="this.style.transform='translateY(-5px)'"
                     onmouseout="this.style.transform='translateY(0)'">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-white-50 mb-2 fw-semibold">MOBIL DISEWA</h6>
                                <h3 class="fw-bold mb-0">{{ $stok_disewa }} Unit</h3>
                                <small class="text-white-50">
                                    <i class="fas fa-clock me-1"></i>Sedang dalam penyewaan
                                </small>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-3 p-3">
                                <i class="fas fa-key fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <div class="position-absolute bottom-0 start-0 w-100 h-25" 
                         style="background: linear-gradient(transparent, rgba(0,0,0,0.1));"></div>
                </div>
            </div>
        </div>

        <!-- Transaction History Section -->
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);">
            <div class="card-header border-0 bg-transparent p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 fw-bold text-dark">Riwayat Transaksi</h4>
                        <p class="text-muted mb-0">Daftar semua transaksi rental mobil</p>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                            <tr>
                                <th class="border-0 py-3 px-4 text-muted fw-semibold">No</th>
                                <th class="border-0 py-3 px-4 text-muted fw-semibold">Pemesan</th>
                                <th class="border-0 py-3 px-4 text-muted fw-semibold">Mobil</th>
                                <th class="border-0 py-3 px-4 text-muted fw-semibold">Periode</th>
                                <th class="border-0 py-3 px-4 text-muted fw-semibold">Total Harga</th>
                                <th class="border-0 py-3 px-4 text-muted fw-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksi as $key => $t)
                                <tr class="border-0" style="transition: all 0.3s ease;">
                                    <td class="px-4 py-3 align-middle">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 35px; height: 35px; font-weight: 600; color: #667eea;">
                                            {{ $key + 1 }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 align-middle">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $t->user->name ?? '-' }}</h6>
                                                <small class="text-muted">{{ $t->user->email ?? '-' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 align-middle">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-car text-white"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $t->mobil->nama_mobil ?? '-' }}</h6>
                                                <small class="text-muted">{{ $t->mobil->merk ?? '-' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 align-middle">
                                        <div class="text-center">
                                            <div class="fw-semibold text-dark">{{ date('d M', strtotime($t->tanggal_mulai)) }}</div>
                                            <div class="text-muted small">s.d</div>
                                            <div class="fw-semibold text-dark">{{ date('d M', strtotime($t->tanggal_selesai)) }}</div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 align-middle">
                                        <div class="fw-bold text-success fs-5">
                                            Rp{{ number_format($t->total_harga) }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 align-middle">
                                        @if($t->status == 'disetujui')
                                            <span class="badge bg-success px-3 py-2 rounded-pill">
                                                <i class="fas fa-check-circle me-1"></i>Disetujui
                                            </span>
                                        @elseif($t->status == 'ditolak')
                                            <span class="badge bg-danger px-3 py-2 rounded-pill">
                                                <i class="fas fa-times-circle me-1"></i>Ditolak
                                            </span>
                                        @else
                                            <span class="badge bg-warning px-3 py-2 rounded-pill">
                                                <i class="fas fa-clock me-1"></i>Pending
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <h5>Belum ada data transaksi</h5>
                                            <p class="mb-0">Transaksi akan muncul di sini setelah pelanggan melakukan pemesanan</p>
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
    
    .floating-shape {
        animation: float 6s ease-in-out infinite;
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
    
    .text-success {
        color: #28a745 !important;
    }
    
    .bg-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }
    
    .z-1 {
        z-index: 1;
    }
    
    .display-4 {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1.2;
    }
    
    @media (max-width: 768px) {
        .display-4 {
            font-size: 2rem;
        }
        
        .card-body {
            padding: 1rem !important;
        }
        
        .table-responsive {
            font-size: 0.875rem;
        }
    }
</style>
@endsection