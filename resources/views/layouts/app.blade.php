<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rental Mobil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Optional: Bootstrap JS Bundle --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --accent-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated background elements */
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
            z-index: -2;
        }

        /* Floating particles */
        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }

        .shape {
            position: absolute;
            opacity: 0.1;
            animation: float 20s infinite linear;
        }

        .shape:nth-child(1) {
            top: 10%;
            left: 10%;
            width: 80px;
            height: 80px;
            background: var(--accent-gradient);
            border-radius: 50%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            top: 60%;
            right: 20%;
            width: 120px;
            height: 120px;
            background: var(--secondary-gradient);
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            animation-delay: -5s;
        }

        .shape:nth-child(3) {
            bottom: 20%;
            left: 20%;
            width: 60px;
            height: 60px;
            background: var(--primary-gradient);
            border-radius: 50%;
            animation-delay: -10s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            25% { transform: translateY(-20px) rotate(90deg); }
            50% { transform: translateY(0px) rotate(180deg); }
            75% { transform: translateY(-10px) rotate(270deg); }
        }

        /* Modern Navbar */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            z-index: 1030;
            position: relative;
            padding: 1rem 0;
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 800;
            color: #fff !important;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
            color: #fff !important;
        }

        .navbar-brand svg {
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
            transition: all 0.3s ease;
        }

        .navbar-brand:hover svg {
            transform: rotate(10deg);
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 600;
            transition: all 0.3s ease;
            padding: 0.75rem 1.5rem !important;
            border-radius: 50px;
            margin: 0 0.5rem;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
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
            transform: scale(1.05);
        }

        .nav-link:hover {
            color: #fff !important;
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        .nav-icon {
            margin-right: 8px;
            font-size: 1.1rem;
        }

        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .profile-img:hover {
            transform: scale(1.1);
            border-color: #fff;
        }

        /* Modern Dropdown */
        .nav-item.dropdown {
            position: relative;
            z-index: 1040;
        }

        .dropdown-menu {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            z-index: 1050 !important;
            position: absolute !important;
            min-width: 250px;
            padding: 1rem;
            margin-top: 0.5rem;
        }

        .dropdown-item {
            transition: all 0.3s ease;
            border-radius: 12px;
            margin: 0.25rem 0;
            padding: 0.75rem 1rem;
            font-weight: 500;
        }

        .dropdown-item:hover {
            background: rgba(102, 126, 234, 0.1);
            transform: translateX(10px);
        }

        .dropdown-divider {
            margin: 1rem 0;
            border-color: rgba(0, 0, 0, 0.1);
        }

        /* Modern Content Container */
        .content-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
            margin-top: 2rem;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .content-container::before {
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
            z-index: -1;
        }

        /* Modern Buttons */
        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            border-radius: 50px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
        }

        .btn-danger {
            background: var(--secondary-gradient);
            border: none;
            border-radius: 50px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 6px 20px rgba(245, 87, 108, 0.4);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-danger::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-danger:hover::before {
            left: 100%;
        }

        .btn-danger:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(245, 87, 108, 0.6);
        }

        /* Modern Toggle Button */
        .navbar-toggler {
            border: 1px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 0.5rem;
            transition: all 0.3s ease;
        }

        .navbar-toggler:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.85%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .content-container {
                padding: 1.5rem;
                margin-top: 1rem;
                border-radius: 20px;
            }
            
            .navbar-brand {
                font-size: 1.5rem;
            }
            
            .nav-link {
                padding: 0.5rem 1rem !important;
                margin: 0.25rem 0;
                border-radius: 12px;
            }

            .dropdown-menu {
                min-width: 200px;
            }
        }

        /* Scroll Styling */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 4px;
            transition: background 0.3s ease;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* Additional hover effects */
        .container {
            position: relative;
            z-index: 2;
        }

        /* Login button special styling */
        .nav-link.login-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff !important;
        }

        .nav-link.login-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
        }

        /* User role styling */
        .user-role {
            background: rgba(102, 126, 234, 0.1);
            border-radius: 20px;
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
            color: #667eea;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-lg">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-car-front-fill me-3" viewBox="0 0 16 16">
                    <path d="M4 9a1 1 0 1 0 0 2 1 1 0 0 0 0-2Zm8 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2ZM4.273 1.31A1 1 0 0 1 5.162 1h5.676a1 1 0 0 1 .889.51l1.92 3.36A2 2 0 0 1 14 6.236V11a2 2 0 0 1-2 2v1a1 1 0 0 1-2 0v-1H6v1a1 1 0 0 1-2 0v-1a2 2 0 0 1-2-2V6.236c0-.358.095-.709.273-1.017l1.92-3.36ZM4.605 3l-.857 1.5h8.504L11.395 3H4.605Z"/>
                </svg>
                <span>SiRent</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('Transaksi.create') ? 'active' : '' }}" href="{{ route('Transaksi.create') }}">
                            <i class="bi bi-car-front nav-icon"></i>Pesan Mobil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('Transaksi.index') ? 'active' : '' }}" href="{{ route('Transaksi.index') }}">
                            <i class="bi bi-receipt nav-icon"></i>Transaksi Saya
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=667eea&color=fff&bold=true" class="profile-img me-2" alt="User Avatar">
                                <span>{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg">
                                <li class="px-3 py-2">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-person-circle me-2 text-primary"></i>
                                        <strong>{{ Auth::user()->name }}</strong>
                                    </div>
                                    <div class="user-role">
                                        <i class="bi bi-shield-check me-1"></i>{{ Auth::user()->role }}
                                    </div>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li class="px-3 pb-2">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger w-100">
                                            <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link login-btn" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right nav-icon"></i>Masuk
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="content-container">
            @yield('content')
        </div>
    </div>

    @yield('scripts')

    <script>
        // Enhanced interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scrolling for navigation links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Enhanced dropdown interactions
            const dropdownToggle = document.querySelector('.dropdown-toggle');
            if (dropdownToggle) {
                dropdownToggle.addEventListener('show.bs.dropdown', function() {
                    this.style.transform = 'scale(1.05)';
                });
                
                dropdownToggle.addEventListener('hide.bs.dropdown', function() {
                    this.style.transform = 'scale(1)';
                });
            }

            // Add parallax effect to floating shapes
            document.addEventListener('mousemove', function(e) {
                const shapes = document.querySelectorAll('.shape');
                const mouseX = e.clientX / window.innerWidth;
                const mouseY = e.clientY / window.innerHeight;
                
                shapes.forEach((shape, index) => {
                    const speed = (index + 1) * 0.5;
                    const x = (mouseX - 0.5) * speed * 50;
                    const y = (mouseY - 0.5) * speed * 50;
                    
                    shape.style.transform = `translate(${x}px, ${y}px)`;
                });
            });
        });
    </script>
</body>
</html>