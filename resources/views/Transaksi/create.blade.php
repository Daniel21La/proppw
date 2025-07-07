@extends('layouts.app')

@section('content')
<style>
    /* Modern Car Booking Styles */
    .hero-section {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.05) 100%);
        backdrop-filter: blur(20px);
        border-radius: 30px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 4rem 2rem;
        margin: 2rem 0 3rem 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite;
        pointer-events: none;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 900;
        color: #fff;
        margin-bottom: 1rem;
        text-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        position: relative;
        z-index: 1;
        background: linear-gradient(45deg, #fff, #f0f0f0);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-subtitle {
        font-size: 1.3rem;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 2rem;
        position: relative;
        z-index: 1;
        font-weight: 500;
    }

    .car-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
        gap: 2.5rem;
        margin-bottom: 4rem;
        padding: 0 1rem;
    }

    .car-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(25px);
        border-radius: 30px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        overflow: hidden;
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        transform-style: preserve-3d;
    }

    .car-card:hover {
        transform: translateY(-15px) scale(1.02);
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.25);
        border-color: rgba(255, 255, 255, 0.4);
    }

    .car-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), transparent);
        opacity: 0;
        transition: opacity 0.5s ease;
        pointer-events: none;
        z-index: 1;
    }

    .car-card:hover::before {
        opacity: 1;
    }

    .car-image-container {
        position: relative;
        height: 250px;
        overflow: hidden;
        border-radius: 30px 30px 0 0;
    }

    .car-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
        filter: brightness(0.9);
    }

    .car-card:hover .car-image {
        transform: scale(1.1);
        filter: brightness(1);
    }

    .car-info {
        padding: 2rem;
        position: relative;
        z-index: 2;
    }

    .car-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #fff;
        margin-bottom: 0.8rem;
        text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        line-height: 1.2;
    }

    .car-description {
        color: rgba(255, 255, 255, 0.85);
        margin-bottom: 1.5rem;
        line-height: 1.6;
        font-size: 0.95rem;
        min-height: 3rem;
    }

    .car-price {
        font-size: 1.8rem;
        font-weight: 900;
        color: #fff;
        margin-bottom: 1.2rem;
        text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        display: flex;
        align-items: baseline;
        gap: 0.5rem;
    }

    .car-price .currency {
        font-size: 1.2rem;
        color: rgba(255, 255, 255, 0.8);
    }

    .car-price .period {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 500;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1.2rem;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .status-available {
        background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%);
        color: white;
        box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
    }

    .status-unavailable {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
    }

    .btn-rent {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        border: none;
        border-radius: 20px;
        padding: 1rem 2rem;
        font-weight: 700;
        color: white;
        width: 100%;
        transition: all 0.4s ease;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-size: 0.9rem;
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }

    .btn-rent::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.6s ease;
    }

    .btn-rent:hover::before {
        left: 100%;
    }

    .btn-rent:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(59, 130, 246, 0.6);
    }

    .btn-rent:disabled {
        background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
        cursor: not-allowed;
        transform: none;
        box-shadow: 0 4px 15px rgba(156, 163, 175, 0.3);
    }

    .btn-rent:disabled:hover {
        transform: none;
        box-shadow: 0 4px 15px rgba(156, 163, 175, 0.3);
    }

    .form-section {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(25px);
        border-radius: 35px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        margin-top: 4rem;
        animation: slideUpFade 0.8s ease-out;
        transform: translateY(0);
    }

    @keyframes slideUpFade {
        from {
            opacity: 0;
            transform: translateY(40px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-header {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.25) 0%, rgba(255, 255, 255, 0.1) 100%);
        padding: 2.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        text-align: center;
        position: relative;
    }

    .form-header h3 {
        color: #fff;
        font-weight: 800;
        margin: 0;
        text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        font-size: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
    }

    .selected-car-info {
        background: rgba(59, 130, 246, 0.15);
        border: 1px solid rgba(59, 130, 246, 0.3);
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        text-align: center;
        backdrop-filter: blur(10px);
    }

    .selected-car-name {
        color: #fff;
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .form-body {
        padding: 3rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        color: rgba(255, 255, 255, 0.9);
        font-weight: 700;
        margin-bottom: 0.8rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1rem;
    }

    .form-control {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        padding: 1rem 1.5rem;
        color: #fff;
        font-size: 1rem;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        width: 100%;
    }

    .form-control:focus {
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(59, 130, 246, 0.5);
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.2);
        color: #fff;
        outline: none;
    }

    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }

    .total-section {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.2) 0%, rgba(5, 150, 105, 0.1) 100%);
        border: 1px solid rgba(16, 185, 129, 0.3);
        border-radius: 25px;
        padding: 2rem;
        margin-bottom: 2rem;
        text-align: center;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .total-label {
        color: rgba(255, 255, 255, 0.8);
        font-size: 1rem;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .total-amount {
        color: #fff;
        font-size: 2.5rem;
        font-weight: 900;
        text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        margin-bottom: 0.5rem;
    }

    .total-duration {
        color: rgba(255, 255, 255, 0.7);
        font-size: 1rem;
        font-style: italic;
    }

    .btn-submit {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
        border-radius: 20px;
        padding: 1.2rem 2.5rem;
        font-weight: 800;
        color: white;
        width: 100%;
        font-size: 1.1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 8px 30px rgba(16, 185, 129, 0.4);
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }

    .btn-submit::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.6s ease;
    }

    .btn-submit:hover::before {
        left: 100%;
    }

    .btn-submit:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(16, 185, 129, 0.6);
    }

    .btn-submit:disabled {
        background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
        cursor: not-allowed;
        transform: none;
        box-shadow: 0 4px 15px rgba(156, 163, 175, 0.3);
    }

    /* Alert Styles */
    .alert {
        border-radius: 20px;
        padding: 1.5rem 2rem;
        margin-bottom: 2rem;
        backdrop-filter: blur(10px);
        border: 1px solid;
        display: flex;
        align-items: center;
        gap: 1rem;
        font-weight: 600;
    }

    .alert-success {
        background: rgba(16, 185, 129, 0.15);
        border-color: rgba(16, 185, 129, 0.3);
        color: #fff;
    }

    .alert-danger {
        background: rgba(239, 68, 68, 0.15);
        border-color: rgba(239, 68, 68, 0.3);
        color: #fff;
    }

    /* Loading States */
    .loading {
        position: relative;
        pointer-events: none;
    }

    .loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
        margin: -10px 0 0 -10px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top: 2px solid #fff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }
        
        .hero-subtitle {
            font-size: 1.1rem;
        }
        
        .car-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
            padding: 0;
        }
        
        .form-row {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .form-body {
            padding: 2rem;
        }
        
        .hero-section {
            padding: 3rem 1.5rem;
        }
    }

    @media (max-width: 480px) {
        .hero-title {
            font-size: 2rem;
        }
        
        .car-info {
            padding: 1.5rem;
        }
        
        .form-body {
            padding: 1.5rem;
        }
        
        .total-amount {
            font-size: 2rem;
        }
    }
</style>

<div class="container-fluid px-4">
    <!-- Hero Section -->
    <div class="hero-section">
        <h1 class="hero-title">🚗 Premium Car Rental</h1>
        <p class="hero-subtitle">Pilih kendaraan impian Anda untuk petualangan yang tak terlupakan</p>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Car Grid -->
    <div class="car-grid">
        @forelse($mobils as $mobil)
            <div class="car-card">
                <div class="car-image-container">
                    <img src="{{ asset('gambar_mobil/' . $mobil->gambar) }}" 
                         class="car-image" 
                         alt="{{ $mobil->nama_mobil }}"
                         onerror="this.src='{{ asset('images/default-car.jpg') }}'">
                </div>
                <div class="car-info">
                    <h5 class="car-title">{{ $mobil->merk }} - {{ $mobil->nama_mobil }}</h5>
                    <p class="car-description">{{ Str::limit($mobil->deskripsi, 120) }}</p>
                    <div class="car-price">
                        <span class="currency">Rp</span>
                        {{ number_format($mobil->harga_per_hari, 0, ',', '.') }}
                        <span class="period">/hari</span>
                    </div>
                    <div class="status-badge {{ $mobil->status === 'tersedia' ? 'status-available' : 'status-unavailable' }}">
                        <i class="bi bi-{{ $mobil->status === 'tersedia' ? 'check-circle-fill' : 'x-circle-fill' }}"></i>
                        {{ $mobil->status === 'tersedia' ? 'Tersedia' : 'Sudah Disewa' }}
                    </div>
                    @if($mobil->status === 'tersedia')
                        <button class="btn-rent select-mobil" 
                                data-id="{{ $mobil->id }}" 
                                data-name="{{ $mobil->merk }} - {{ $mobil->nama_mobil }}"
                                data-price="{{ $mobil->harga_per_hari }}">
                            <i class="bi bi-car-front-fill"></i>
                            Sewa Mobil Ini
                        </button>
                    @else
                        <button class="btn-rent" disabled>
                            <i class="bi bi-lock-fill"></i>
                            Tidak Tersedia
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-car-front" style="font-size: 4rem; color: rgba(255,255,255,0.5);"></i>
                    <h4 class="text-white mt-3">Tidak ada mobil tersedia</h4>
                    <p class="text-white-50">Silakan coba lagi nanti</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Booking Form -->
    <div class="form-section" id="formContainer" style="display: none;">
        <div class="form-header">
            <h3>
                <i class="bi bi-clipboard-check-fill"></i>
                Form Pemesanan
            </h3>
        </div>
        <div class="form-body">
            <div class="selected-car-info">
                <p class="selected-car-name" id="selectedCarName">Mobil yang dipilih</p>
            </div>

            <form action="{{ route('Transaksi.store') }}" method="POST" id="formTransaksi">
                @csrf
                <input type="hidden" name="mobil_id" id="selectedMobilId">

                <div class="form-row">
                    <div class="form-group">
                        <label for="tanggal_mulai" class="form-label">
                            <i class="bi bi-calendar-event"></i>
                            Tanggal Mulai
                        </label>
                        <input type="date" 
                               name="tanggal_mulai" 
                               id="tanggal_mulai"
                               class="form-control" 
                               required
                               min="{{ date('Y-m-d') }}">
                    </div>

                    <div class="form-group">
                        <label for="tanggal_selesai" class="form-label">
                            <i class="bi bi-calendar-check"></i>
                            Tanggal Selesai
                        </label>
                        <input type="date" 
                               name="tanggal_selesai" 
                               id="tanggal_selesai"
                               class="form-control" 
                               required
                               min="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <div class="total-section" id="totalSection" style="display: none;">
                    <div class="total-label">Total Biaya Rental</div>
                    <div class="total-amount" id="totalAmount">Rp 0</div>
                    <div class="total-duration" id="totalDuration">0 hari</div>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="bi bi-credit-card-fill"></i>
                    Konfirmasi Pemesanan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Elements
        const selectButtons = document.querySelectorAll('.select-mobil');
        const formContainer = document.getElementById('formContainer');
        const mobilIdInput = document.getElementById('selectedMobilId');
        const selectedCarName = document.getElementById('selectedCarName');
        const tanggalMulai = document.getElementById('tanggal_mulai');
        const tanggalSelesai = document.getElementById('tanggal_selesai');
        const totalSection = document.getElementById('totalSection');
        const totalAmount = document.getElementById('totalAmount');
        const totalDuration = document.getElementById('totalDuration');
        const submitBtn = document.getElementById('submitBtn');
        const form = document.getElementById('formTransaksi');
        
        let selectedPrice = 0;
        let isCalculating = false;

        // Car selection
        selectButtons.forEach(button => {
            button.addEventListener('click', function () {
                const mobilId = this.dataset.id;
                const carName = this.dataset.name;
                const price = parseInt(this.dataset.price);
                
                // Update selected car info
                selectedPrice = price;
                mobilIdInput.value = mobilId;
                selectedCarName.textContent = carName;
                
                // Add loading state
                this.classList.add('loading');
                this.innerHTML = '<i class="bi bi-hourglass-split"></i> Memuat...';
                
                // Show form after delay
                setTimeout(() => {
                    formContainer.style.display = 'block';
                    formContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    
                    // Reset button
                    this.classList.remove('loading');
                    this.innerHTML = '<i class="bi bi-car-front-fill"></i> Sewa Mobil Ini';
                    
                    // Reset form
                    form.reset();
                    totalSection.style.display = 'none';
                    mobilIdInput.value = mobilId;
                }, 800);
            });
        });

        // Date change handlers
        tanggalMulai.addEventListener('change', function() {
            tanggalSelesai.min = this.value;
            if (tanggalSelesai.value && tanggalSelesai.value < this.value) {
                tanggalSelesai.value = '';
            }
            calculateTotal();
        });

        tanggalSelesai.addEventListener('change', calculateTotal);

        // Calculate total cost
        function calculateTotal() {
            if (isCalculating) return;
            
            const startDate = new Date(tanggalMulai.value);
            const endDate = new Date(tanggalSelesai.value);
            
            if (startDate && endDate && startDate <= endDate && selectedPrice > 0) {
                isCalculating = true;
                
                const timeDiff = endDate - startDate;
                const days = Math.ceil(timeDiff / (1000 * 60 * 60 * 24)) + 1;
                const total = selectedPrice * days;
                
                // Animate total display
                totalAmount.style.transform = 'scale(0.8)';
                totalDuration.style.transform = 'scale(0.8)';
                
                setTimeout(() => {
                    totalAmount.textContent = `Rp ${total.toLocaleString('id-ID')}`;
                    totalDuration.textContent = `${days} hari`;
                    totalSection.style.display = 'block';
                    
                    totalAmount.style.transform = 'scale(1)';
                    totalDuration.style.transform = 'scale(1)';
                    
                    isCalculating = false;
                }, 200);
            } else {
                totalSection.style.display = 'none';
                isCalculating = false;
            }
        }

        // Form submission
        form.addEventListener('submit', function(e) {
            if (!tanggalMulai.value || !tanggalSelesai.value || !selectedPrice) {
                e.preventDefault();
                alert('Mohon lengkapi semua field terlebih dahulu!');
                return;
            }
            
            // Show loading state
            submitBtn.classList.add('loading');
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';
            submitBtn.disabled = true;
        });

        // Enhanced card animations
        document.querySelectorAll('.car-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-15px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Smooth scrolling for better UX
        function smoothScroll(target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
</script>
@endsection