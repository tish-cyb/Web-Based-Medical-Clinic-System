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

        /* Enable smooth scrolling for all internal links */
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
            height: 55px; /* ADJUSTED: Increased size */
            width: auto;
            object-fit: contain;
            transition: height 0.3s ease;
        }
        
        .itechcare-logo {
            height: 70px; /* ADJUSTED: Increased size */
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
            text-decoration: none; /* For anchor tags */
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

        /* --- HERO SECTION STYLES (Restored to normal height) --- */
        /* .hero-placeholder {
            height: 50vh; 
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(180deg, #f3f4f6 0%, #e5e7eb 100%);
            text-align: center;
            padding: 2rem;
            border-bottom: 1px solid #d1d5db;
        } */

        /* Placeholder Content Styling */
        /* .main-content-section {
            padding: 80px 20px;
            background-color: white;
            min-height: 80vh;
        } */

        /* .section-card {
            background-color: var(--primary-soft);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        } */
    </style>
</head>
<body>

    <header class="main-header">
        <div class="container header-container">
            
            <!-- Logo Section -->
            <a href="#home" class="logo-wrapper">
                <img src="img/pup_logo.png" alt="School Logo" class="logo-img">
                <img src="img/logo.png" alt="iTechCare Logo" class="logo-img itechcare-logo">
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

    <!-- MAIN CONTENT -->
    <!-- <main>
        <!-- Anchor for 'Home' links to scroll to the top of the main content -->
        <!-- <div id="home-top"></div> -->
        
        <!-- Hero Section -->
        <!-- <div class="hero-placeholder">
            <div class="container">
                <h1 class="display-4 fw-bold mb-3" style="font-size: 3rem;">iTechCare Portal</h1>
                <p class="lead text-muted mb-4">
                    Your secure platform for academic health and assessment management.
                </p>
                <a href="#about-us" class="btn btn-lg fw-bold" style="background-color: var(--primary-color); color: white; border-radius: 50px;">
                    Learn More <i class="bi bi-arrow-down-circle-fill ms-2"></i>
                </a>
            </div>
        </div> -->

        <!-- Placeholder Content Sections -->
        <!-- <section id="about-us" class="main-content-section">
            <div class="container">
                <h2 class="text-center mb-5 fw-bold" style="color: var(--primary-color);">About iTechCare</h2>
                <div class="section-card">
                    <p class="fs-5">iTechCare is committed to providing comprehensive, technology-driven health monitoring and assessment services for the student body. Our platform streamlines the medical examination process, making it efficient and accessible. We focus on early detection and preventative care to ensure a healthy and safe academic environment.</p>
                    <p>The system integrates secure data management, appointment scheduling, and health record tracking, adhering strictly to privacy regulations.</p>
                </div>
            </div>
        </section> -->

        <!-- <section id="forms" class="main-content-section" style="background-color: var(--bg-body); padding-top: 40px; min-height: 50vh;">
            <div class="container">
                <h2 class="text-center mb-5 fw-bold" style="color: var(--primary-color);">Downloadable Forms</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="p-4 bg-white rounded-3 shadow-sm text-center">
                            <i class="bi bi-file-earmark-medical-fill fs-1" style="color: var(--primary-color);"></i>
                            <h5 class="mt-3">Medical Exam Form</h5>
                            <p class="text-muted small">Required for all incoming students.</p>
                            <a href="#" class="btn btn-sm btn-outline-danger">Download PDF</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-4 bg-white rounded-3 shadow-sm text-center">
                            <i class="bi bi-calendar-check-fill fs-1" style="color: var(--primary-color);"></i>
                            <h5 class="mt-3">Appointment Request</h5>
                            <p class="text-muted small">Schedule consultation with a physician.</p>
                            <a href="#" class="btn btn-sm btn-outline-danger">Download DOC</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-4 bg-white rounded-3 shadow-sm text-center">
                            <i class="bi bi-info-circle-fill fs-1" style="color: var(--primary-color);"></i>
                            <h5 class="mt-3">Privacy Policy</h5>
                            <p class="text-muted small">Our commitment to data security.</p>
                            <a href="#" class="btn btn-sm btn-outline-danger">View Document</a>
                        </div>
                    </div>
                </div>
            </div>
        </section> -->
        
        <!-- <section id="faqs" class="main-content-section">
            <div class="container">
                <h2 class="text-center mb-5 fw-bold" style="color: var(--primary-color);">Frequently Asked Questions</h2>
                <!-- Simple Accordion Placeholder -->
                <!-- <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">What documents are needed for the medical exam?</button></h2>
                        <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">You generally need a valid student ID, a completed Medical Exam Form, and any recent laboratory results if requested by the university.</div></div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">How do I schedule an appointment?</button></h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion"><div class="accordion-body">Appointments can be scheduled by downloading and submitting the Appointment Request form via email or visiting the clinic during operating hours.</div></div>
                    </div>
                </div>
            </div>
        </section> --> 

        <!-- <section id="contact-us" class="main-content-section" style="background-color: var(--bg-body); padding-top: 40px; min-height: 40vh;">
            <div class="container text-center">
                <h2 class="mb-4 fw-bold" style="color: var(--primary-color);">Get in Touch</h2>
                <p class="lead text-muted">For urgent inquiries, please visit the campus health services office or call us.</p>
                <div class="row justify-content-center mt-5">
                    <div class="col-md-3">
                        <i class="bi bi-telephone-fill fs-2" style="color: var(--primary-light);"></i>
                        <p class="mt-2 fw-medium">Call Us</p>
                        <p class="small text-muted">(02) 8716-1234</p>
                    </div>
                    <div class="col-md-3">
                        <i class="bi bi-envelope-fill fs-2" style="color: var(--primary-light);"></i>
                        <p class="mt-2 fw-medium">Email</p>
                        <p class="small text-muted">healthservices@itechcare.edu</p>
                    </div>
                    <div class="col-md-3">
                        <i class="bi bi-geo-alt-fill fs-2" style="color: var(--primary-light);"></i>
                        <p class="mt-2 fw-medium">Location</p>
                        <p class="small text-muted">Health Services Unit, Main Campus</p>
                    </div>
                </div>
            </div>
        </section> -->

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
