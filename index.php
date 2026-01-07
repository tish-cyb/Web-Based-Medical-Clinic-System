<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI-Care</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #7f1d1d; 
            --primary-light: #b91c1c;
            --primary-soft: #fef2f2; 
            --primary-gradient-start: #7f1d1d;
            --primary-gradient-end: #ef4444; 
            --text-dark: #1f2937;
            --text-gray: #6b7280;
            --bg-body: #f3f4f6;
            --header-height: 90px;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-dark);
            padding-top: var(--header-height); 
        }

        /* --- Floating Header Styles --- */
        .main-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: var(--header-height);
            z-index: 1000;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .header-container {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* --- Logo Styles --- */
        .logo-wrapper {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .logo-img {
            height: 55px; 
            width: auto;
            object-fit: contain;
            transition: height 0.3s ease;
        }
        
        .itechcare-logo {
            height: 70px; 
        }

        /* --- Desktop Navigation --- */
        .desktop-nav {
            display: flex;
            align-items: center;
            gap: 15px;
            height: 100%;
        }

        .nav-btn {
            border: none;
            background: transparent;
            padding: 0.5rem 1rem; 
            padding-bottom: 0.75rem;
            border-radius: 0;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-gray);
            transition: color 0.2s ease;
            cursor: pointer;
            position: relative; 
            text-decoration: none;
        }

        .nav-btn::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 3px;
            background-color: var(--primary-color);
            transition: width 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            border-radius: 2px;
        }
        
        .nav-btn:hover { color: var(--primary-color); }
        .nav-btn:hover::after { width: calc(100% - 2rem); }
        .nav-btn.active { color: var(--primary-color); font-weight: 600; }
        .nav-btn.active::after { width: calc(100% - 2rem); }

        /* --- Mobile Styles --- */
        .mobile-toggle {
            display: none;
            border: none;
            background: transparent;
            font-size: 1.5rem;
            color: var(--text-dark);
            padding: 0.5rem;
            border-radius: 8px;
        }
        
        @media (max-width: 991px) {
            .desktop-nav { display: none; }
            .mobile-toggle { display: block; }
        }

        .offcanvas { border-left: none; box-shadow: -5px 0 25px rgba(0,0,0,0.1); }
        .offcanvas-header { background-color: var(--primary-color); color: white; }
        .btn-close-white { filter: invert(1) grayscale(100%) brightness(200%); }

        .mobile-nav-btn {
            width: 100%;
            text-align: left;
            padding: 1rem;
            border: none;
            background: transparent;
            border-bottom: 1px solid #f0f0f0; 
            font-weight: 500;
            color: var(--text-dark);
            transition: background 0.2s, color 0.2s, border-bottom 0.2s;
            text-decoration: none;
            display: block;
        }

        .mobile-nav-btn:hover { background-color: var(--primary-soft); color: var(--primary-color); }
        .mobile-nav-btn.active { 
            background-color: var(--primary-soft); 
            color: var(--primary-color); 
            font-weight: 600; 
            border-bottom: 3px solid var(--primary-color); 
        }
    </style>
</head>
<body>

    <header class="main-header">
        <div class="container header-container">
            
            <!-- Logo Section -->
            <a href="#home" class="logo-wrapper">
                <img src="assets/img/pup_logo.png" alt="School Logo" class="logo-img">
                <img src="assets/img/logo.png" alt="iTechCare Logo" class="logo-img itechcare-logo">
            </a>

            <!-- Desktop Navigation -->
            <nav class="desktop-nav">
                <!-- Links now point to relevant section anchors -->
                <a href="#home" class="nav-btn active">Home</a>
                <a href="#about-us" class="nav-btn">About Us</a>
                <a href="#forms" class="nav-btn">Forms</a>
                <a href="#faqs" class="nav-btn">FAQs</a>
                <a href="#contact-us" class="nav-btn">Contact Us</a>
            </nav>

            <!-- Mobile Menu Toggle -->
            <button class="mobile-toggle" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </header>

    <!-- Mobile Slide-out Menu -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="mobileMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">iTechCare Menu</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            <!-- Mobile Navigation Links (Active class managed by Bootstrap JS if necessary, or simple links) -->
            <a href="#home-top" class="mobile-nav-btn active" data-bs-dismiss="offcanvas">Home</a>
            <a href="#about-us" class="mobile-nav-btn" data-bs-dismiss="offcanvas">About Us</a>
            <a href="#forms" class="mobile-nav-btn" data-bs-dismiss="offcanvas">Forms</a>
            <a href="#faqs" class="mobile-nav-btn" data-bs-dismiss="offcanvas">FAQs</a>
            <a href="#contact-us" class="mobile-nav-btn" data-bs-dismiss="offcanvas">Contact Us</a>
            <!-- The Mobile Login Button is intentionally removed -->
        </div>
    </div>

    <!-- Main Content Placeholder -->
    <main>
    <main id="home" class="hero-section">
    <div class="container">
        <div class="row g-5 align-items-stretch">

            <!-- HERO -->
            <div class="col-lg-7">
                <div class="hero-card h-100">
                    <div class="hero-controls">
                        <span>&laquo;</span>
                        <span>&raquo;</span>
                    </div>

                    <h2 class="hero-title">Assessment Services</h2>

                    <ul class="hero-list">
                        <li>Medical history taking (students & employees)</li>
                        <li>Vital signs monitoring (BP, temp, HR, RR)</li>
                        <li>Height & weight measurement</li>
                        <li>Complete medical & physical examinations</li>
                        <li>Illness identification, diagnosis & treatment</li>
                    </ul>

                    <div class="hero-dots">
                        <span class="active"></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>

            <!-- LOGIN -->
            <div class="col-lg-5 d-flex align-items-center">
                <div class="login-card w-100">

                    <div class="text-center mb-4">
                        <img src="assets/img/pup_logo.png" width="60" class="mb-2">
                        <p class="text-muted mb-1">Secure Access Portal</p>
                        <h2>Sign In</h2>
                        <p class="text-muted small">Access your medical portal</p>
                    </div>

                    <form>
                        <div class="mb-3">
                            <label class="form-label">Email Address / ID Number</label>
                            <input type="text" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" placeholder="Enter your password">
                        </div>

                        <button class="btn btn-login w-100 mt-2">
                            Sign In
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <small class="text-muted">
                            Trouble signing in?
                            <a href="#" class="text-danger fw-semibold">Forgot password</a>
                        </small>
                    </div>

                </div>
            </div>

        </div>
    </div>
</main>

    </main>
    <!-- </main>
    
    <footer class="text-center py-4 bg-white border-top text-muted">
        <p class="mb-0 small">&copy; 2025 iTechCare. All rights reserved. Powered by PUP.</p>
    </footer> -->

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to manage 'active' class on navigation buttons
        document.addEventListener('DOMContentLoaded', () => {
            const navLinks = document.querySelectorAll('.nav-btn, .mobile-nav-btn');
            
            navLinks.forEach(link => {
                // Ensure only anchor tags get the active class toggling
                link.addEventListener('click', function(event) {
                    
                    // Remove active from all desktop links
                    document.querySelectorAll('.nav-btn').forEach(btn => btn.classList.remove('active'));
                    // Remove active from all mobile links
                    document.querySelectorAll('.mobile-nav-btn').forEach(btn => btn.classList.remove('active'));
                    
                    // Add active to the clicked link
                    this.classList.add('active');

                    // If it's a mobile link, close the offcanvas menu
                    if (this.classList.contains('mobile-nav-btn')) {
                        const offcanvas = document.getElementById('mobileMenu');
                        const bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvas) || new bootstrap.Offcanvas(offcanvas);
                        bsOffcanvas.hide();
                    }
                });
            });
        });

        // Shadow effect on scroll
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.main-header');
            if (window.scrollY > 10) {
                header.style.boxShadow = "0 8px 30px -5px rgba(0, 0, 0, 0.15)";
                header.style.background = "rgba(255, 255, 255, 0.98)";
            } else {
                header.style.boxShadow = "0 4px 20px -5px rgba(0, 0, 0, 0.1)";
                header.style.background = "rgba(255, 255, 255, 0.95)";
            }
        });
        

        


    </script>
</body>
</html>