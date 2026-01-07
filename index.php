<?php
require_once "config/db.php";

$login_error = '';

if (isset($_POST['signin'])) {

    // Use 'identifier' instead of 'username', because that's your form field name
    $identifier = mysqli_real_escape_string($c, $_POST['identifier']); // email or username
    $password   = $_POST['password'];

    // Check if user exists
    $sql = "SELECT * FROM user WHERE email='$identifier' OR username='$identifier' LIMIT 1";
    $result = mysqli_query($c, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Directly check password (plain text)
        if ($password === $user['password']) { // <- Use 'password' column instead of 'password_hash'
            // Start session
            session_start();
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] == 'student') {
                header("Location: student/student_dashboard.php");
                exit();
            } elseif ($user['role'] == 'nurse') {
                header("Location: nurse/nurse_dashboard.php");
                exit();
            } elseif ($user['role'] == 'admin') {
                header("Location: admin/admin_dashboard.php");
                exit();
            }
        } else {
            $login_error = "Incorrect password!";
        }

    } else {
        $login_error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iTechCare - PUP Medical Clinic</title>
    
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
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

        /* HEADER */
        .main-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: var(--header-height);
            z-index: 1000;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .header-container {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            height: 100%;
            max-height: 45px;
            width: auto !important;
        }

        .logo-img {
            max-height: 70px; 
            width: auto;
            object-fit: contain;
        }

        .logo-img.itechcare-logo {
            max-height: 150px; 
            width: auto;
            margin-left: 0; 
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .logo-icon {
            color: var(--primary-color);
        }

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
            transition: width 0.3s ease;
            border-radius: 2px;
        }
        
        .nav-btn:hover { 
            color: var(--primary-color); 
        }
        
        .nav-btn:hover::after { 
            width: calc(100% - 2rem); 
        }
        
        .nav-btn.active { 
            color: var(--primary-color); 
            font-weight: 600; 
        }
        
        .nav-btn.active::after { 
            width: calc(100% - 2rem); 
        }

        .mobile-toggle {
            display: none;
            border: none;
            background: transparent;
            font-size: 1.5rem;
            color: var(--text-dark);
            padding: 0.5rem;
        }
        
        @media (max-width: 991px) {
            .desktop-nav { display: none; }
            .mobile-toggle { display: block; }
            .logo-text { font-size: 1.2rem; }
        }

        /* HERO SECTION */
        .hero-section {
            padding: 60px 0;
            min-height: calc(100vh - var(--header-height));
            display: flex;
            align-items: center;
        }

        .hero-card {
            background: linear-gradient(135deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
            border-radius: 20px;
            padding: 30px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(127, 29, 29, 0.3);
        }

        .hero-slider {
            position: relative;
            width: 100%;
            height: 600px;
            border-radius: 15px;
            overflow: hidden;
        }

        .hero-slides {
            display: flex;
            transition: transform 0.5s ease;
            height: 100%;
        }

        .hero-slides img {
            min-width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-controls {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding: 0 15px;
            z-index: 10;
            pointer-events: none;
        }

        .hero-controls span {
            background: rgba(255, 255, 255, 0.9);
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            cursor: pointer;
            font-weight: bold;
            font-size: 1.3rem;
            color: var(--primary-color);
            transition: all 0.3s;
            pointer-events: all;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .hero-controls span:hover {
            background: white;
            transform: scale(1.1);
        }

        .hero-dots {
            position: absolute;
            bottom: 25px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 10;
        }

        .hero-dots span {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s;
        }

        .hero-dots span.active {
            background: white;
            width: 12px;
            height: 12px;
        }

        .hero-dots span:hover {
            background: rgba(255, 255, 255, 0.8);
        }

        .hero-content {
            color: white;
            padding: 30px;
        }

        .hero-content h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .hero-content ul {
            list-style: none;
            padding: 0;
        }

        .hero-content li {
            padding: 10px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .hero-content li::before {
            content: '✓';
            font-weight: bold;
        }

        /* LOGIN CARD */
        .login-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .login-card h2 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 10px;
        }

        .form-label {
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .form-control {
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px var(--primary-soft);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
            color: white;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(127, 29, 29, 0.3);
        }

        /* ABOUT SECTION */
        .about-section {
            padding: 80px 0;
            background: white;
        }

        .about-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .about-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .about-header h3 {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 20px;
        }

        .about-header p {
            color: var(--text-gray);
            font-size: 1.1rem;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.8;
        }

        .btn-yellow {
            background: #fbbf24;
            color: var(--text-dark);
            padding: 12px 30px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
            margin-top: 20px;
        }

        .btn-yellow:hover {
            background: #f59e0b;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(251, 191, 36, 0.3);
        }

        /* MISSION/VISION/VALUES */
        .values-section {
            padding: 60px 0;
        }

        .value-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            height: 100%;
            border-left: 4px solid var(--primary-color);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
        }

        .value-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        .value-icon {
            color: var(--primary-color);
            font-size: 2rem;
            margin-bottom: 15px;
        }

        .value-card h4 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .value-card p {
            color: var(--text-gray);
            line-height: 1.7;
            margin: 0;
        }

        /* MEDICAL TEAM */
        .team-section {
            padding: 80px 0;
            background: var(--bg-body);
        }

        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .section-title p {
            color: var(--text-gray);
            font-size: 1.1rem;
        }

        .team-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
        }

        .team-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .team-photo {
            width: 100%;
            height: 300px;
            background: linear-gradient(135deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8rem;
            color: rgba(255, 255, 255, 0.3);
        }

        .team-info {
            padding: 25px;
            text-align: center;
        }

        .team-info h4 {
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .team-info p {
            color: var(--text-gray);
            margin: 0;
        }

        /* FORMS SECTION */
        .forms-section {
            padding: 80px 0;
            background: white;
        }

        .form-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            border: 2px solid #e5e7eb;
            transition: all 0.3s;
            height: 100%;
        }

        .form-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 10px 30px rgba(127, 29, 29, 0.1);
        }

        .form-card h4 {
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 15px;
        }

        .form-card p {
            color: var(--text-gray);
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .btn-download {
            background: #fbbf24;
            color: var(--text-dark);
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-download:hover {
            background: #f59e0b;
            transform: translateY(-2px);
        }

        .form-meta {
            font-size: 0.85rem;
            color: var(--text-gray);
            margin-top: 10px;
        }

        /* FAQ SECTION */
        .faq-section {
            padding: 0;
            background: var(--bg-body);
            margin-top: 40px;
        }

        .faq-item {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .faq-question {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        .faq-answer {
            color: var(--text-gray);
            line-height: 1.7;
            margin: 0;
        }

        /* CONTACT US SECTION */
        .contact-section {
            background: #1f2937; /* Dark background */
            padding: 40px 0;
            color: white;
        }

        .contact-section h4 {
            font-weight: 700;
            margin-bottom: 10px;
        }

        .contact-section p {
            margin: 0;
            line-height: 1.6;
        }

        /* FOOTER */
        .footer {
            background: #7f1d1d; /* Dark red background */
            color: white;
            padding: 20px 0;
            text-align: center;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <header class="main-header">
        <div class="container header-container">
            <a href="#home" class="logo-wrapper">
                <img src="assets/img/pup_logo.png" alt="School Logo" class="logo-img">
                <img src="assets/img/BASED IN.png" alt="iTechCare Logo" class="logo-img itechcare-logo">
            </a>

            <nav class="desktop-nav">
                <a href="#home" class="nav-btn active">Home</a>
                <a href="#about-us" class="nav-btn">About Us</a>
                <a href="#forms" class="nav-btn">Forms</a>
                <a href="#faqs" class="nav-btn">FAQs</a>
                <a href="#contact-us" class="nav-btn">Contact Us</a>
            </nav>

            <button class="mobile-toggle" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </header>

    <!-- MOBILE MENU -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="mobileMenu">
        <div class="offcanvas-header" style="background-color: var(--primary-color); color: white;">
            <h5 class="offcanvas-title">iTechCare Menu</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            <a href="#home" class="nav-btn d-block p-3" data-bs-dismiss="offcanvas">Home</a>
            <a href="#about-us" class="nav-btn d-block p-3" data-bs-dismiss="offcanvas">About Us</a>
            <a href="#forms" class="nav-btn d-block p-3" data-bs-dismiss="offcanvas">Forms</a>
            <a href="#faqs" class="nav-btn d-block p-3" data-bs-dismiss="offcanvas">FAQs</a>
            <a href="#contact-us" class="nav-btn d-block p-3" data-bs-dismiss="offcanvas">Contact Us</a>
        </div>
    </div>

    <!-- HERO + LOGIN SECTION -->
    <main id="home" class="hero-section">
        <div class="container">
            <div class="row g-5 align-items-stretch">
                <!-- HERO SLIDER -->
                <div class="col-lg-7">
                    <div>
                        <div class="hero-slider " > 
                            <div class="hero-slides" id="heroSlides">
                                <img src="assets/img/carousel 1.png" alt="Services 1">
                                <img src="assets/img/carousel 2.png" alt="Sercives 2">
                                <img src="assets/img/carousel 3.png" alt="Services 3">
                                <img src="assets/img/carousel 4.png" alt="Services 4">
                                <img src="assets/img/carousel 5.png" alt="Services 5">
                                <img src="assets/img/carousel 6.png" alt="Services 6">
                                <img src="assets/img/carousel 7.png" alt="Services 7">
                            </div>
                            
                            <div class="hero-controls">
                                <span id="prevSlide">&laquo;</span>
                                <span id="nextSlide">&raquo;</span>
                            </div>

                            <div class="hero-dots" id="heroDots"></div>
                        </div>
                    </div>
                </div>

                <!-- LOGIN FORM -->
                <div class="col-lg-5 d-flex align-items-center">
                    <div class="login-card w-100">
                        <div class="text-center mb-4">
                        <img src="assets/img/logo ai.png" width="100" class="mb-2">
                            <div class="logo-icon d-inline-block mb-2">
                                    <path d="M30 15v30M15 30h30" stroke="white" stroke-width="4" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <p class="text-muted mb-1">Secure Access Portal</p>
                            <h2>Sign In</h2>
                            <p class="text-muted small">Access your medical portal</p>
                        </div>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Email or Username</label>
                                <input type="text" name="identifier" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="text" name="password" class="form-control" required>
                            </div>

                            <button type="submit" name="signin" class="btn btn-login w-100 mt-2">
                                Sign In
                            </button>
                        </form>

                        <?php if($login_error != ''): ?>
                        <div class="alert alert-danger mt-2">
                            <?= $login_error ?>
                        </div>
                        <?php endif; ?>



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

    <!-- ABOUT SECTION -->
    <section id="about-us" class="about-section">
        <div class="container">
            <div class="about-header">
                <h2>At PUP AI Care</h2>
                <h3>The Clinic provides essential health services to support student well-being within the PUP Institute of Technology</h3>
                <p>We provide first aid and triage for common illnesses and injuries, assists students with special health needs, and promotes health awareness through counseling and education.</p>
                <button class="btn btn-yellow">More About Us</button>
            </div>
        </div>
    </section>

    <!-- MISSION, VISION, VALUES -->
    <section class="values-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="value-card">
                        <h4><i class="bi bi-bullseye value-icon"></i> Our Mission</h4>
                        <p>Our mission is to deliver professional medical and dental services</p>
                        <p>The enhancement of health and local well-being based on health through preventive measures</p>
                        <p>Providing quality health care, counseling for self-discovery and personal development</p>
                        <p>The maintenance of quality health care services</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="value-card">
                        <h4><i class="bi bi-eye value-icon"></i> Our Vision</h4>
                        <p>The PUP Medical Services is envisioned to create a highly productive PUP Community where individuals achieve and maintain optimum well-being and exercises responsible behavior</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="value-card">
                        <h4><i class="bi bi-heart value-icon"></i> Core Values</h4>
                        <p>Compassion, Dedication, Innovation, and Accountability.</p>
                        <p>We are committed to delivering quality services while upholding integrity and respect for every individual's well-being</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MEDICAL TEAM -->
    <section class="team-section">
        <div class="container">
            <div class="section-title">
                <h2>Our Medical Team</h2>
                <p>Meet our dedicated healthcare professionals committed to your well-being</p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-md-4">
                    <div class="team-card">
                        <div class="team-photo">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <div class="team-info">
                            <h4>Dr. Example MD</h4>
                            <p>PUP iTech Medical Doctor</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="team-card">
                        <div class="team-photo">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <div class="team-info">
                            <h4>Dr. Example RN</h4>
                            <p>PUP iTech Nurse</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- DOWNLOADABLE FORMS -->
    <section id="forms" class="forms-section">
        <div class="container">
            <div class="section-title">
                <h2>Downloadable Forms</h2>
                <p>Download and fill out these forms before your appointment to save time at the clinic</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="form-card">
                        <h4>Medical Slip</h4>
                        <p>Required form for all medical consultations and appointments. Contains your basic information and medical history for our clinic records.</p>
                        <a href="#" class="btn-download">
                            <i class="bi bi-download me-2"></i>Download Medical Slip
                        </a>
                        <div class="form-meta">PDF Format • 142 KB</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-card">
                        <h4>Health Declaration Form</h4>
                        <p>Daily health declaration form required for clinic visits. Includes COVID-19 screening questions and symptom checklist.</p>
                        <a href="#" class="btn-download">
                            <i class="bi bi-download me-2"></i>Download Declaration Form
                        </a>
                        <div class="form-meta">PDF Format • 104 KB</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQS -->
    <section id="faqs" class="faq-section">
        <div class="container">
            <div class="section-title">
                <h2>Frequently Asked Questions</h2>
            </div>
            <div class="faq-item">
                <div class="faq-question">How do I book an appointment?</div>
                <div class="faq-answer">Log in to the "Book Appointment" section to select your preferred date, time, and appointment type. Make sure to provide your contact number and reason for visit.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">What should I bring to my appointment?</div>
                <div class="faq-answer">Bring your valid student ID for any relevant medical documents, and a list of current medications if applicable.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">How can I cancel or reschedule my appointment?</div>
                <div class="faq-answer">You can cancel or reschedule via the appointment management section after logging in. Please provide at least 24 hours notice.</div>
    </div>
    </div>

    <!-- CONTACT US SECTION -->
    <section id="contact-us" class="contact-section">
        <div class="container">
            <div class="row text-white">
                <div class="col-md-4">
                    <h4>Contact Us</h4>
                    <p>PUP Institute of Technology<br>Sta. Mesa, Manila, Philippines</p>
                </div>
                <div class="col-md-4">
                    <h4>Clinic</h4>
                    <p>Clinic: (02) 5335-1PUP (1787)<br>Emergency: (02) 5335-1911<br>Mon-Fri: 8:00 AM - 5:00 PM</p>
                </div>
                <div class="col-md-4">
                    <h4>Email</h4>
                    <p>clinic@pup.edu.ph<br>itechcare@pup.edu.ph<br>Response within 24 hours</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container text-center">
            <p class="mb-0">Academic Prototype - PUP iTech Clinic Management System</p>
        </div>
    </footer>

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
        
        document.addEventListener('DOMContentLoaded', () => {
            const slides = document.querySelectorAll('#heroSlides img');
            const dotsContainer = document.getElementById('heroDots');
            const prevSlide = document.getElementById('prevSlide');
            const nextSlide = document.getElementById('nextSlide');
            let currentSlide = 0;

            // Generate dots dynamically
            slides.forEach((_, index) => {
                const dot = document.createElement('span');
                dot.classList.add('dot');
                if (index === 0) dot.classList.add('active');
                dot.addEventListener('click', () => goToSlide(index));
                dotsContainer.appendChild(dot);
            });

            const dots = document.querySelectorAll('.dot');

            // Function to go to a specific slide
            function goToSlide(index) {
                currentSlide = index;
                updateSlider();
            }

            // Function to update the slider and dots
            function updateSlider() {
                const slideWidth = slides[0].clientWidth;
                document.getElementById('heroSlides').style.transform = `translateX(-${currentSlide * slideWidth}px)`;

                dots.forEach(dot => dot.classList.remove('active'));
                dots[currentSlide].classList.add('active');
            }

            // Navigation controls
            prevSlide.addEventListener('click', () => {
                currentSlide = (currentSlide > 0) ? currentSlide - 1 : slides.length - 1;
                updateSlider();
            });

            nextSlide.addEventListener('click', () => {
                currentSlide = (currentSlide < slides.length - 1) ? currentSlide + 1 : 0;
                updateSlider();
            });

            // Resize event to adjust slider width
            window.addEventListener('resize', updateSlider);
        });
    </script>

<?php if (isset($success_msg)): ?>
    <div class="alert alert-success"><?php echo $success_msg; ?></div>
<?php endif; ?>

<?php if (isset($error_msg)): ?>
    <div class="alert alert-danger"><?php echo $error_msg; ?></div>
<?php endif; ?>

</body>
</html>