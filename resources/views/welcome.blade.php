<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiRent - Rental Mobil Terpercaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        /* Modern Design */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 1rem 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-size: 2rem;
            font-weight: 800;
            color: #fff !important;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 600;
            margin: 0 1rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            transition: width 0.3s ease;
        }

        .navbar-nav .nav-link:hover::after {
            width: 100%;
        }

        .navbar-nav .nav-link:hover {
            color: #fff !important;
        }

        /* Hero Section */
        .hero {
            padding: 150px 0 100px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero h1 {
            font-size: 4rem;
            font-weight: 900;
            color: #fff;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            margin-bottom: 1.5rem;
            animation: fadeInUp 1s ease-out;
        }

        .hero p {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
            animation: fadeInUp 1s ease-out 0.3s both;
        }

        .btn-hero {
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            border: none;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 1px;
            animation: fadeInUp 1s ease-out 0.6s both;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: #fff;
            box-shadow: 0 10px 30px rgba(79, 172, 254, 0.3);
        }

        .btn-outline-light {
            background: transparent;
            color: #fff;
            border: 2px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }

        .btn-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: #fff;
        }

        /* Features Section */
        .features {
            padding: 100px 0;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2.5rem;
            text-align: center;
            height: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .feature-icon {
            font-size: 3rem;
            color: #4facfe;
            margin-bottom: 1.5rem;
        }

        .feature-card h3 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .feature-card p {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
        }

        /* About Section */
        .about {
            padding: 100px 0;
            text-align: center;
        }

        .about h2 {
            font-size: 3rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 2rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .about p {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.9);
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.8;
        }

        /* Contact Section */
        .contact {
            padding: 100px 0;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }

        .contact h2 {
            font-size: 3rem;
            font-weight: 800;
            color: #fff;
            text-align: center;
            margin-bottom: 3rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .contact-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            height: 100%;
            transition: all 0.3s ease;
        }

        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .contact-icon {
            font-size: 2.5rem;
            color: #4facfe;
            margin-bottom: 1rem;
        }

        .contact-card h4 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .contact-card p {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 0;
        }

        /* Footer */
        .footer {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            padding: 2rem 0;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer p {
            color: rgba(255, 255, 255, 0.7);
            margin: 0;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero p {
                font-size: 1.1rem;
            }
            
            .btn-hero {
                padding: 0.8rem 2rem;
                font-size: 1rem;
                margin: 0.5rem;
            }
            
            .about h2,
            .contact h2 {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-car-front me-2"></i>SiRent
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                </ul>
                <div class="d-flex ms-3">
                    <a href="{{ route('login') }}" class="btn btn-outline-light me-2">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Selamat Datang di SiRent</h1>
                <p>Solusi rental mobil terpercaya dengan layanan profesional. Nikmati perjalanan Anda dengan kenyamanan dan keamanan terjamin bersama armada terbaik kami.</p>
                <div class="hero-buttons">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-hero">Mulai Sewa</a>
                    <a href="#about" class="btn btn-outline-light btn-hero">Pelajari Lebih</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h3>Terpercaya</h3>
                        <p>Lebih dari 5 tahun melayani dengan komitmen memberikan pelayanan terbaik dan kendaraan berkualitas tinggi.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-clock"></i>
                        </div>
                        <h3>Layanan 24/7</h3>
                        <p>Siap melayani Anda kapan saja dengan customer service yang responsif dan bantuan darurat di jalan.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <h3>Harga Terjangkau</h3>
                        <p>Dapatkan harga sewa yang kompetitif dengan kualitas layanan premium. Tanpa biaya tersembunyi.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about">
        <div class="container">
            <h2>Tentang SiRent</h2>
            <p>
                SiRent adalah perusahaan rental mobil yang telah berpengalaman selama bertahun-tahun dalam memberikan layanan transportasi terbaik. Kami berkomitmen untuk memberikan kendaraan berkualitas tinggi dengan harga yang terjangkau. 
                <br><br>
                Dengan armada yang terawat dan tim profesional, kami memastikan setiap perjalanan Anda aman, nyaman, dan memorable. Kepuasan pelanggan adalah prioritas utama kami, dan kami selalu berusaha memberikan pengalaman rental yang tak terlupakan.
            </p>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact">
        <div class="container">
            <h2>Hubungi Kami</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <h4>Telepon</h4>
                        <p>+62 812-3456-7890</p>
                        <p>+62 21-1234-5678</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <h4>Email</h4>
                        <p>adminrental@gmail.com</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <h4>Alamat</h4>
                        <p>Jl. Medan Raya No. 12</p>
                        <p>Medan, Sumatra Utara</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 SiRent. All rights reserved. Made with ❤️ for better transportation.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Navbar background on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(255, 255, 255, 0.2)';
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.1)';
            }
        });
    </script>
</body>
</html>