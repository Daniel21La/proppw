@extends('layouts.admin')

@section('content')
<!-- Modern Background -->
<div class="modern-bg">
    <div class="container-fluid px-4 py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <!-- Header Section -->
                <div class="header-card mb-5">
                    <div class="d-flex align-items-center">
                        <div>
                            <h2 class="title-gradient mb-2">{{ $mobil ? 'Edit Mobil' : 'Tambah Mobil' }}</h2>
                            <p class="subtitle mb-0">Kelola informasi mobil</p>
                        </div>
                    </div>
                </div>

                <!-- Error Alert -->
                @if ($errors->any())
                    <div class="modern-alert mb-4" role="alert">
                        <div class="alert-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="alert-content">
                            <strong>Terjadi kesalahan!</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Main Form Card -->
                <div class="modern-card">
                    <div class="card-body">
                        <form action="{{ route('admin.rentalmobil.save', $mobil->id ?? '') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row g-4">
                                <!-- Merk -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="merk" class="form-label">
                                            <i class="fas fa-tag me-2"></i>Merk
                                        </label>
                                        <input type="text" 
                                               name="merk" 
                                               id="merk"
                                               class="modern-input" 
                                               value="{{ old('merk', $mobil->merk ?? '') }}" 
                                               placeholder="Contoh: Toyota, Honda, BMW"
                                               required>
                                    </div>
                                </div>

                                <!-- Nama Mobil -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama_mobil" class="form-label">
                                            <i class="fas fa-car me-2"></i>Nama Mobil
                                        </label>
                                        <input type="text" 
                                               name="nama_mobil" 
                                               id="nama_mobil"
                                               class="modern-input" 
                                               value="{{ old('nama_mobil', $mobil->nama_mobil ?? '') }}" 
                                               placeholder="Contoh: Avanza, Civic, X5"
                                               required>
                                    </div>
                                </div>

                                <!-- Harga per Hari -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="harga_per_hari" class="form-label">
                                            <i class="fas fa-money-bill-wave me-2"></i>Harga per Hari
                                        </label>
                                        <div class="input-group modern-input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" 
                                                   name="harga_per_hari" 
                                                   id="harga_per_hari"
                                                   class="modern-input" 
                                                   value="{{ old('harga_per_hari', $mobil->harga_per_hari ?? '') }}" 
                                                   placeholder="500000"
                                                   required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status" class="form-label">
                                            <i class="fas fa-info-circle me-2"></i>Status
                                        </label>
                                        <select name="status" id="status" class="modern-select">
                                            <option value="tersedia" {{ old('status', $mobil->status ?? '') == 'tersedia' ? 'selected' : '' }}>
                                                ✅ Tersedia
                                            </option>
                                            <option value="disewa" {{ old('status', $mobil->status ?? '') == 'disewa' ? 'selected' : '' }}>
                                                🚗 Disewa
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Gambar Mobil -->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="gambar" class="form-label">
                                            <i class="fas fa-image me-2"></i>Gambar Mobil
                                        </label>
                                        
                                        @if (!empty($mobil->gambar))
                                            <div class="current-image-container mb-4">
                                                <div class="image-wrapper">
                                                    <img src="{{ asset('gambar_mobil/' . $mobil->gambar) }}" 
                                                         class="current-image" 
                                                         alt="Gambar Mobil">
                                                    <div class="image-badge">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                    <div class="image-overlay">
                                                        <span>Gambar Saat Ini</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="upload-container">
                                            <div class="upload-area" id="uploadArea">
                                                <div class="upload-icon">
                                                    <i class="fas fa-cloud-upload-alt"></i>
                                                </div>
                                                <div class="upload-text">
                                                    <h6>Pilih atau Drag & Drop Gambar</h6>
                                                    <p>PNG, JPG, GIF hingga 5MB</p>
                                                </div>
                                                <input type="file" 
                                                       name="gambar" 
                                                       id="gambar"
                                                       class="file-input" 
                                                       accept="image/*">
                                                <div class="upload-button">
                                                    <i class="fas fa-folder-open me-2"></i>Pilih File
                                                </div>
                                            </div>
                                            <div class="upload-info">
                                                <small>
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Biarkan kosong jika tidak ingin mengganti gambar
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="action-buttons">
                                <a href="{{ route('admin.rentalmobil.index') }}" class="btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                </a>
                                <button type="submit" class="btn-primary">
                                    <i class="fas fa-save me-2"></i>{{ $mobil ? 'Update Mobil' : 'Simpan Mobil' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modern CSS -->
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --glass-bg: rgba(255, 255, 255, 0.95);
    --glass-border: rgba(255, 255, 255, 0.2);
    --shadow-primary: 0 20px 40px rgba(102, 126, 234, 0.1);
    --shadow-hover: 0 25px 50px rgba(102, 126, 234, 0.2);
}

* {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

body {
    font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: var(--bg-gradient);
    min-height: 100vh;
}

.modern-bg {
    background: var(--bg-gradient);
    min-height: 100vh;
    position: relative;
}

.modern-bg::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 40% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
    pointer-events: none;
}

.header-card {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 24px;
    padding: 2rem;
    box-shadow: var(--shadow-primary);
    position: relative;
    overflow: hidden;
}

.header-card::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: conic-gradient(from 0deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    animation: rotate 20s linear infinite;
    opacity: 0.3;
}

@keyframes rotate {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.icon-container {
    width: 80px;
    height: 80px;
    background: var(--primary-gradient);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    position: relative;
    z-index: 1;
}

.icon-container i {
    font-size: 2rem;
    color: white;
}

.title-gradient {
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-weight: 800;
    font-size: 2.5rem;
    margin: 0;
}

.subtitle {
    color: #6b7280;
    font-size: 1.1rem;
    font-weight: 500;
}

.modern-alert {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(239, 68, 68, 0.2);
    border-left: 4px solid #ef4444;
    border-radius: 16px;
    padding: 1.5rem;
    display: flex;
    align-items: flex-start;
    box-shadow: 0 10px 30px rgba(239, 68, 68, 0.1);
}

.alert-icon {
    width: 40px;
    height: 40px;
    background: var(--secondary-gradient);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    flex-shrink: 0;
}

.alert-icon i {
    color: white;
    font-size: 1.2rem;
}

.alert-content {
    flex: 1;
}

.modern-card {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 24px;
    box-shadow: var(--shadow-primary);
    overflow: hidden;
    position: relative;
}

.modern-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--primary-gradient);
}

.card-body {
    padding: 3rem;
}

.form-group {
    margin-bottom: 2rem;
}

.form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.75rem;
    display: block;
    font-size: 1rem;
}

.form-label i {
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.modern-input, .modern-select {
    width: 100%;
    padding: 1rem 1.5rem;
    font-size: 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 16px;
    background: #f9fafb;
    transition: all 0.3s ease;
    outline: none;
}

.modern-input:focus, .modern-select:focus {
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    transform: translateY(-2px);
}

.modern-input-group {
    display: flex;
    align-items: center;
    background: #f9fafb;
    border: 2px solid #e5e7eb;
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.modern-input-group:focus-within {
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    transform: translateY(-2px);
}

.modern-input-group .input-group-text {
    background: transparent;
    border: none;
    font-weight: 600;
    color: #667eea;
    padding: 1rem 1.5rem;
}

.modern-input-group .modern-input {
    border: none;
    background: transparent;
    margin: 0;
    padding: 1rem 1.5rem;
}

.modern-input-group .modern-input:focus {
    box-shadow: none;
    transform: none;
}

.current-image-container {
    display: flex;
    justify-content: center;
    margin-bottom: 2rem;
}

.image-wrapper {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.image-wrapper:hover {
    transform: translateY(-5px);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
}

.current-image {
    width: 300px;
    height: 200px;
    object-fit: cover;
    display: block;
}

.image-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    width: 40px;
    height: 40px;
    background: var(--success-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.image-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
    color: white;
    padding: 1.5rem;
    text-align: center;
    font-weight: 600;
    transform: translateY(100%);
    transition: transform 0.3s ease;
}

.image-wrapper:hover .image-overlay {
    transform: translateY(0);
}

.upload-container {
    position: relative;
}

.upload-area {
    border: 3px dashed #d1d5db;
    border-radius: 20px;
    padding: 3rem;
    text-align: center;
    background: #f9fafb;
    position: relative;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.3s ease;
}

.upload-area:hover {
    border-color: #667eea;
    background: rgba(102, 126, 234, 0.05);
    transform: translateY(-2px);
}

.upload-area.dragover {
    border-color: #667eea;
    background: rgba(102, 126, 234, 0.1);
    transform: scale(1.02);
}

.upload-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    background: var(--primary-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.upload-icon i {
    font-size: 2rem;
    color: white;
}

.upload-text h6 {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.upload-text p {
    color: #6b7280;
    margin-bottom: 1.5rem;
}

.file-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.upload-button {
    background: var(--primary-gradient);
    color: white;
    padding: 0.75rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    display: inline-block;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    transition: all 0.3s ease;
}

.upload-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.upload-info {
    margin-top: 1rem;
    text-align: center;
}

.upload-info small {
    color: #6b7280;
    font-size: 0.875rem;
}

.action-buttons {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 1px solid #e5e7eb;
}

.btn-primary, .btn-secondary {
    padding: 1rem 2rem;
    font-weight: 600;
    border-radius: 16px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    border: none;
    cursor: pointer;
    font-size: 1rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-primary {
    background: var(--primary-gradient);
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    color: white;
}

.btn-secondary {
    background: white;
    color: #6b7280;
    border: 2px solid #e5e7eb;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.btn-secondary:hover {
    background: #f9fafb;
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    color: #374151;
    text-decoration: none;
}

/* Preview Image Styles */
.image-preview {
    display: flex;
    justify-content: center;
    margin-bottom: 2rem;
}

.image-preview .image-wrapper {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.image-preview .image-wrapper .image-badge {
    background: var(--success-gradient);
}

.image-preview .image-wrapper .image-overlay {
    background: linear-gradient(transparent, rgba(76, 175, 80, 0.8));
}

.image-preview .image-wrapper .image-overlay span {
    color: white;
}

/* Responsive Design */
@media (max-width: 768px) {
    .card-body {
        padding: 1.5rem;
    }
    
    .title-gradient {
        font-size: 2rem;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 1rem;
    }
    
    .btn-primary, .btn-secondary {
        width: 100%;
        justify-content: center;
    }
    
    .current-image {
        width: 250px;
        height: 160px;
    }
}

/* Loading Animation */
.loading {
    opacity: 0.6;
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
    border: 2px solid #f3f3f3;
    border-top: 2px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<!-- Enhanced JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('gambar');
    const uploadArea = document.getElementById('uploadArea');
    
    // Drag and drop functionality
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });
    
    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
    });
    
    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            imageInput.files = files;
            handleImagePreview(files[0]);
        }
    });
    
    // File input change handler
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                handleImagePreview(file);
            }
        });
    }
    
    // Image preview function
    function handleImagePreview(file) {
        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('Please select a valid image file.');
            return;
        }
        
        // Validate file size (5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('File size must be less than 5MB.');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            // Remove existing preview
            const existingPreview = document.querySelector('.image-preview');
            if (existingPreview) {
                existingPreview.remove();
            }
            
            // Create new preview
            const preview = document.createElement('div');
            preview.className = 'image-preview';
            preview.innerHTML = `
                <div class="image-wrapper">
                    <img src="${e.target.result}" 
                         class="current-image" 
                         alt="Preview Gambar">
                    <div class="image-badge">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="image-overlay">
                        <span>Preview Gambar Baru</span>
                    </div>
                </div>
            `;
            
            // Insert preview before upload area
            const uploadContainer = document.querySelector('.upload-container');
            uploadContainer.insertBefore(preview, uploadContainer.firstChild);
            
            // Add animation
            setTimeout(() => {
                preview.style.opacity = '1';
                preview.style.transform = 'translateY(0)';
            }, 100);
        };
        reader.readAsDataURL(file);
    }
    
    // Form submission loading state
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('.btn-primary');
            if (submitBtn) {
                submitBtn.classList.add('loading');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
            }
        });
    }
    
    // Auto-format currency input
    const priceInput = document.getElementById('harga_per_hari');
    if (priceInput) {
        priceInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = value;
        });
    }
    
    // Smooth scroll for error alerts
    const alertElement = document.querySelector('.modern-alert');
    if (alertElement) {
        alertElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});
</script>
@endsection