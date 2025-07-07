@extends('layouts.admin')

@section('content')
<div class="min-vh-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container py-5">
        <!-- Header Section -->
        <div class="text-center mb-5">
            <h1 class="text-white mb-3 fw-bold">Daftar Mobil Rental</h1>
            <p class="text-white-50 fs-5">Temukan mobil impian Anda dengan mudah</p>
        </div>

        <!-- Cars Grid -->
        <div class="row g-4">
            @foreach($mobils as $mobil)
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden h-100 position-relative" 
                         style="transition: all 0.3s ease; transform: translateY(0);"
                         onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.2)'"
                         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.1)'">
                        
                        <!-- Status Badge -->
                        <div class="position-absolute top-0 start-0 m-3 z-3">
                            @if($mobil->status === 'tersedia')
                                <span class="badge bg-success px-3 py-2 rounded-pill shadow-sm">
                                    <i class="fas fa-check-circle me-1"></i>Tersedia
                                </span>
                            @else
                                <span class="badge bg-danger px-3 py-2 rounded-pill shadow-sm">
                                    <i class="fas fa-times-circle me-1"></i>Disewa
                                </span>
                            @endif
                        </div>

                        <!-- Car Image -->
                        <div class="position-relative overflow-hidden" style="height: 280px;">
                            <img src="{{ asset('gambar_mobil/' . $mobil->gambar) }}" 
                                 class="w-100 h-100" 
                                 alt="{{ $mobil->nama_mobil }}" 
                                 style="object-fit: cover; object-position: center; transition: transform 0.3s ease;"
                                 onmouseover="this.style.transform='scale(1.05)'"
                                 onmouseout="this.style.transform='scale(1)'">
                            
                            <!-- Gradient Overlay -->
                            <div class="position-absolute bottom-0 start-0 w-100 h-50" 
                                 style="background: linear-gradient(transparent, rgba(0,0,0,0.7));"></div>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body p-4">
                            <!-- Car Brand and Model -->
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary rounded-circle p-2 me-3">
                                    <i class="fas fa-car text-white"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-bold text-dark">{{ $mobil->nama_mobil }}</h5>
                                    <small class="text-muted">{{ $mobil->merk }}</small>
                                </div>
                            </div>

                            <!-- Description -->
                            <p class="text-muted mb-3 lh-base">
                                {{ Str::limit($mobil->deskripsi, 100) }}
                            </p>

                            <!-- Features -->
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="d-flex align-items-center text-muted small">
                                        <i class="fas fa-users me-2"></i>
                                        <span>5 Penumpang</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center text-muted small">
                                        <i class="fas fa-cog me-2"></i>
                                        <span>Manual</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <span class="text-muted small">Harga per hari</span>
                                    <h4 class="text-primary fw-bold mb-0">
                                        Rp{{ number_format($mobil->harga_per_hari) }}
                                    </h4>
                                </div>
                                <div class="text-end">
                                    <div class="text-warning">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <small class="text-muted">4.9 (123 ulasan)</small>
                                </div>
                            </div>

                            <!-- Action Button -->
                            @if($mobil->status === 'tersedia')
                                <form action="{{ route('Transaksi.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="mobil_id" value="{{ $mobil->id }}">
                                    <input type="hidden" name="tanggal_mulai" value="{{ date('Y-m-d') }}">
                                    <input type="hidden" name="tanggal_selesai" value="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-semibold">
                                        <i class="fas fa-calendar-check me-2"></i>Sewa Sekarang
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-outline-secondary w-100 py-3 rounded-3 fw-semibold" disabled>
                                    <i class="fas fa-lock me-2"></i>Sudah Disewa
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Empty State -->
        @if($mobils->isEmpty())
            <div class="text-center py-5">
                <div class="bg-white rounded-4 shadow-sm p-5 mx-auto" style="max-width: 500px;">
                    <i class="fas fa-car-side fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Tidak ada mobil yang ditemukan</h4>
                    <p class="text-muted">Coba ubah filter pencarian Anda</p>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Custom Styles -->
<style>
    .card:hover {
        transform: translateY(-5px) !important;
        box-shadow: 0 15px 35px rgba(0,0,0,0.15) !important;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    }
    
    .badge {
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    
    .form-select:focus,
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .text-primary {
        color: #667eea !important;
    }
    
    .bg-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }
</style>
@endsection