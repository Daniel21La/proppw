<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel - SiRent</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>

    {{-- Custom Style --}}
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 119, 198, 0.2) 0%, transparent 50%);
            z-index: -1;
        }

        .navbar-custom {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            /* Tambahkan z-index tinggi untuk navbar */
            z-index: 1030;
            position: relative;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff !important;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 0.75rem 1rem !important;
            border-radius: 8px;
            margin: 0 0.25rem;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .nav-link:hover::before {
            left: 100%;
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: #fff !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .nav-link:hover {
            color: #fff !important;
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        .nav-icon {
            margin-right: 8px;
            font-size: 1.1rem;
        }

        .profile-img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Fix untuk dropdown menu */
        .nav-item.dropdown {
            position: relative;
            z-index: 1040;
        }

        .dropdown-menu {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            /* Pastikan dropdown menu memiliki z-index yang tinggi */
            z-index: 1050 !important;
            position: absolute !important;
        }

        .dropdown-item {
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 0.25rem 0.5rem;
        }

        .dropdown-item:hover {
            background: rgba(102, 126, 234, 0.1);
            transform: translateX(5px);
        }

        .content-wrapper {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-top: 2rem;
            position: relative;
            overflow: hidden;
            /* Pastikan content wrapper memiliki z-index yang lebih rendah */
            z-index: 1;
        }

        .content-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 10% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(255, 255, 255, 0.05) 0%, transparent 20%);
            pointer-events: none;
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .btn-danger {
            background: var(--secondary-gradient);
            border: none;
            box-shadow: 0 4px 15px rgba(245, 87, 108, 0.4);
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 87, 108, 0.6);
        }

        .navbar-toggler {
            border: 1px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.75%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }

        .floating-elements::before {
            content: '';
            position: absolute;
            top: 10%;
            left: 10%;
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .floating-elements::after {
            content: '';
            position: absolute;
            bottom: 20%;
            right: 15%;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .gear-icon {
            animation: rotate 10s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .content-wrapper {
                padding: 1.5rem;
                margin-top: 1rem;
            }
            
            .navbar-brand {
                font-size: 1.3rem;
            }
            
            .nav-link {
                padding: 0.5rem 0.75rem !important;
            }
        }

        /* Scroll styling */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
    </style>
</head>

<body>
    <div class="floating-elements"></div>
    
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-lg">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor"
                    class="bi bi-gear-fill me-2 gear-icon" viewBox="0 0 16 16">
                    <path
                        d="M9.605.223a1 1 0 0 0-1.21 0L7.13 1.24a4.992 4.992 0 0 0-1.641.475L4.393.684a1 1 0 0 0-1.478 1.32l.695 1.41a4.992 4.992 0 0 0-.99 1.13L1.01 4.2a1 1 0 0 0 0 1.6l1.61 1.205a4.992 4.992 0 0 0 0 2.01L1.01 10.22a1 1 0 0 0 0 1.6l1.61 1.205c.293.478.627.925.99 1.33l-.695 1.41a1 1 0 1 0 1.478 1.32l1.096-1.031a4.992 4.992 0 0 0 1.641.474l1.265 1.017a1 1 0 0 0 1.21 0l1.265-1.017a4.992 4.992 0 0 0 1.641-.474l1.096 1.031a1 1 0 1 0 1.478-1.32l-.695-1.41a4.992 4.992 0 0 0 .99-1.33l1.61-1.205a1 1 0 0 0 0-1.6L14.99 7.81a4.992 4.992 0 0 0 0-2.01l1.61-1.205a1 1 0 0 0 0-1.6l-1.61-1.205a4.992 4.992 0 0 0-.99-1.13l.695-1.41a1 1 0 0 0-1.478-1.32l-1.096 1.031a4.992 4.992 0 0 0-1.641-.475L9.605.223zM8 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6z" />
                </svg>
                <span>Admin SiRent</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar"
                aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.transaksi.index') ? 'active' : '' }}"
                            href="{{ route('admin.transaksi.index') }}">
                            <i class="bi bi-list-check nav-icon"></i>Kelola Transaksi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.rentalmobil.index') ? 'active' : '' }}"
                            href="{{ route('admin.rentalmobil.index') }}">
                            <i class="bi bi-car-front nav-icon"></i>Daftar Mobil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.rentalmobil.form') ? 'active' : '' }}"
                            href="{{ route('admin.rentalmobil.form') }}">
                            <i class="bi bi-plus-square nav-icon"></i>Kelola Mobil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.laporan.index') ? 'active' : '' }}"
                            href="{{ route('admin.laporan.index') }}">
                            <i class="bi bi-file-earmark-text nav-icon"></i>Laporan
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=667eea&color=fff&bold=true"
                                    class="profile-img me-2" alt="User Avatar">
                                <span class="text-white">{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="navbarDropdown">
                                <li class="px-3 py-2">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-badge me-2 text-muted"></i>
                                        <small class="text-muted">Role: <strong>{{ Auth::user()->role }}</strong></small>
                                    </div>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li class="px-3 pb-2">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger w-100 btn-sm">
                                            <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>

    <script>
        // Add some interactive elements
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scrolling for navigation links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });

            // Add active state to dropdown when opened
            const dropdownToggle = document.getElementById('navbarDropdown');
            const dropdown = new bootstrap.Dropdown(dropdownToggle);
            
            dropdownToggle.addEventListener('show.bs.dropdown', function() {
                this.classList.add('active');
            });
            
            dropdownToggle.addEventListener('hide.bs.dropdown', function() {
                this.classList.remove('active');
            });
        });
    </script>
</body>

</html>