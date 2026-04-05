<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Get departments
$departments = getDepartments();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LACH - Medical Services</title>
    <meta name="description" content="Comprehensive medical services at Lahore Advanced Care Hospital">
    
    <link rel="icon" href="img/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
         :root {
            --primary: #1a76d1;
            --primary-light: #e8f4ff;
            --primary-dark: #0d5aa7;
            --secondary: #2d8f7c;
            --accent: #ff7e5f;
            --light: #f8fafc;
            --dark: #1e293b;
            --gray: #64748b;
            --gray-light: #e2e8f0;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --shadow-light: 0 4px 12px rgba(0, 0, 0, 0.05);
            --radius: 16px;
            --radius-small: 8px;
            --transition: all 0.3s ease;
            --gradient-primary: linear-gradient(135deg, #1a76d1 0%, #0d5aa7 100%);
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
            font-family: 'Inter', sans-serif;
            color: var(--dark);
            line-height: 1.6;
            background-color: #ffffff;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            line-height: 1.3;
            margin-bottom: 1rem;
        }

        h1 { font-size: 3.25rem; }
        h2 { font-size: 2.25rem; }
        h3 { font-size: 1.75rem; }

        p {
            margin-bottom: 1.5rem;
            color: var(--gray);
        }

        a {
            text-decoration: none;
            color: inherit;
            transition: var(--transition);
        }

        .container {
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
        }

        section {
            padding: 80px 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-title h5 {
            display: inline-block;
            color: var(--primary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 15px;
            font-size: 0.9rem;
            position: relative;
        }

        .section-title h5::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background-color: var(--accent);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 32px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: var(--transition);
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            gap: 8px;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: white;
            box-shadow: var(--shadow-light);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow);
        }

        .btn-secondary {
            background-color: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-secondary:hover {
            background-color: var(--primary);
            color: white;
            transform: translateY(-3px);
        }

        .btn-accent {
            background-color: var(--accent);
            color: white;
        }

        .btn-accent:hover {
            background-color: #ff6b4a;
            transform: translateY(-3px);
        }

        .btn-outline-light {
            background-color: transparent;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.7);
        }

        .btn-outline-light:hover {
            background-color: white;
            color: var(--primary);
        }

        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            z-index: 1000;
            padding: 18px 0;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
            transition: var(--transition);
        }

        .navbar.scrolled {
            padding: 12px 0;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
        }

        .logo i {
            color: #ff4757;
            margin-right: 10px;
            font-size: 2.2rem;
        }

        .nav-links {
            display: flex;
            list-style: none;
            align-items: center;
            position: relative;
            /* left: 120px; */
        }

        .nav-links li {
            margin-left: 25px;
        }

        .nav-links a {
            font-weight: 500;
            padding: 5px 0;
            font-size: 0.95rem;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .nav-links a.active {
            color: var(--primary);
            font-weight: 600;
            position: relative;
        }

        .nav-links a.active::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: var(--primary);
        }

        .mobile-menu-btn {
            display: none;
            font-size: 1.5rem;
            color: var(--dark);
            cursor: pointer;
            background: none;
            border: none;
        }

        .page-header {
            padding-top: 150px;
            padding-bottom: 100px;
            background: linear-gradient(135deg, #e8f4ff 0%, #f0f9ff 100%);
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 45%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 Z" fill="%231a76d1" opacity="0.05"/></svg>');
            background-size: cover;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }

        .hero-content h1 {
            margin-bottom: 25px;
            color: var(--dark);
        }

        .hero-content p {
            font-size: 1.1rem;
            margin-bottom: 35px;
            color: var(--gray);
        }

        .breadcrumb {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 30px;
            font-size: 0.95rem;
            color: var(--gray);
        }

        .breadcrumb a {
            color: var(--primary);
            transition: var(--transition);
        }

        .breadcrumb a:hover {
            color: var(--primary-dark);
        }

        .breadcrumb .separator {
            margin: 0 12px;
            color: var(--gray-light);
        }

        .breadcrumb .current {
            color: var(--primary);
            font-weight: 600;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .service-card {
            background-color: white;
            border-radius: var(--radius);
            padding: 40px 30px;
            text-align: center;
            box-shadow: var(--shadow-light);
            transition: var(--transition);
            border: 1px solid rgba(226, 232, 240, 0.8);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow);
            border-color: var(--primary);
        }

        .service-icon {
            width: 80px;
            height: 80px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            color: white;
            font-size: 2rem;
        }

        .service-card p {
            flex-grow: 1;
            margin-bottom: 25px;
        }

        .services-section {
            background-color: var(--light);
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 60px;
            align-items: start;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
            margin-top: 40px;
        }

        .feature-card {
            background-color: var(--primary-light);
            padding: 30px 25px;
            border-radius: var(--radius);
            text-align: center;
            transition: var(--transition);
            border: 1px solid rgba(26, 118, 209, 0.1);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-light);
            border-color: var(--primary);
        }

        .feature-card i {
            font-size: 2.8rem;
            color: var(--primary);
            margin-bottom: 20px;
        }

        .sidebar {
            background: white;
            border-radius: var(--radius);
            padding: 30px;
            box-shadow: var(--shadow-light);
            position: sticky;
            top: 120px;
        }

        .sidebar-title {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--gray-light);
        }

        .services-list {
            list-style: none;
            margin-bottom: 30px;
        }

        .services-list li {
            margin-bottom: 10px;
        }

        .services-list a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            background: var(--light);
            border-radius: var(--radius-small);
            color: var(--dark);
            font-weight: 500;
            transition: var(--transition);
            border-left: 4px solid transparent;
        }

        .services-list a:hover,
        .services-list a.active {
            background: var(--primary);
            color: white;
            border-left-color: var(--accent);
            transform: translateX(8px);
        }

        .services-list i {
            margin-right: 12px;
            font-size: 1.25rem;
        }

        .contact-card {
            background: var(--gradient-primary);
            color: white;
            padding: 30px;
            border-radius: var(--radius);
            text-align: center;
        }

        .contact-card h3 {
            color: white;
            margin-bottom: 15px;
        }

        .contact-card p {
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 25px;
        }

        .appointment-section {
            background: var(--gradient-primary);
            color: white;
        }

        .appointment-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 60px;
            align-items: center;
        }

        .appointment-content h5 {
            color: rgba(255, 255, 255, 0.9);
        }

        .appointment-content h1, .appointment-content p {
            color: white;
        }

        .appointment-content p {
            opacity: 0.9;
        }

        .appointment-form {
            background-color: white;
            padding: 40px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .appointment-form h1 {
            color: var(--dark);
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 15px 20px;
            border: 1px solid var(--gray-light);
            border-radius: var(--radius-small);
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26, 118, 209, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-submit {
            width: 100%;
            padding: 16px;
            margin-top: 10px;
        }

        .testimonial-card {
            background-color: var(--light);
            border-radius: var(--radius);
            padding: 40px;
            text-align: center;
            margin: 0 auto;
            max-width: 800px;
            box-shadow: var(--shadow-light);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .testimonial-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 25px;
            border: 5px solid white;
            box-shadow: var(--shadow-light);
        }

        .testimonial-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .footer {
            background-color: var(--dark);
            color: white;
            padding-top: 80px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 40px;
        }

        .footer-col h4 {
            color: white;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 15px;
        }

        .footer-col h4::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: var(--accent);
        }

        .footer-col p {
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 20px;
        }

        .footer-contact p {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .footer-contact i {
            color: var(--primary);
            margin-right: 15px;
            margin-top: 5px;
        }

        .footer-links a {
            display: block;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 12px;
            transition: var(--transition);
            font-size: 0.95rem;
        }

        .footer-links a:hover {
            color: var(--primary);
            padding-left: 5px;
        }

        .footer-links a i {
            margin-right: 10px;
            color: var(--primary);
        }

        .newsletter-form {
            display: flex;
            margin-top: 20px;
        }

        .newsletter-form input {
            flex: 1;
            padding: 15px;
            border: none;
            border-radius: var(--radius-small) 0 0 var(--radius-small);
            font-family: 'Inter', sans-serif;
        }

        .newsletter-form button {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 0 25px;
            border-radius: 0 var(--radius-small) var(--radius-small) 0;
            cursor: pointer;
            transition: var(--transition);
        }

        .newsletter-form button:hover {
            background-color: var(--primary-dark);
        }

        .footer-social {
            display: flex;
            gap: 15px;
            margin-top: 25px;
        }

        .footer-social a {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .footer-social a:hover {
            background-color: var(--primary);
            transform: translateY(-3px);
        }

        .footer-bottom {
            margin-top: 60px;
            padding: 25px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }

        .footer-bottom a {
            color: var(--primary);
        }

        @media (max-width: 1200px) {
            h1 { font-size: 2.75rem; }
            h2 { font-size: 2rem; }
        }

        @media (max-width: 992px) {
            .hero-content, .details-grid, .appointment-container {
                grid-template-columns: 1fr;
                gap: 50px;
            }
            
            .page-header::before {
                width: 100%;
                height: 40%;
                top: auto;
                bottom: 0;
            }
            
            .services-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .footer-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 40px 30px;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .nav-links {
                position: fixed;
                top: 80px;
                left: -100%;
                width: 100%;
                height: calc(100vh - 80px);
                background-color: white;
                flex-direction: column;
                align-items: center;
                padding-top: 40px;
                transition: var(--transition);
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                z-index: 999;
                left: 0;
            }
            
            .nav-links.active {
                left: 0;
            }
            
            .nav-links li {
                margin: 15px 0;
            }
        }

        @media (max-width: 768px) {
            h1 { font-size: 2.25rem; }
            h2 { font-size: 1.75rem; }
            section { padding: 60px 0; }
            .page-header { padding-top: 130px; padding-bottom: 80px; }
            .services-grid, .features-grid { grid-template-columns: 1fr; }
            .form-row { grid-template-columns: 1fr; }
            .appointment-form { padding: 30px 20px; }
        }

        @media (max-width: 576px) {
            .container { padding: 0 20px; }
            h1 { font-size: 1.875rem; }
            h2 { font-size: 1.5rem; }
            .btn { padding: 12px 24px; font-size: 0.95rem; }
            .footer-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar" id="navbar">
        <div class="container nav-container">
            <a href="index.php" class="logo">
                <i class="fas fa-heartbeat"></i>LACH
            </a>
            
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
            
            <ul class="nav-links" id="navLinks">
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="service.php" class="active">Services</a></li>
                <li><a href="price.php">Pricing</a></li>
                <li><a href="team.php">Our Team</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </div>
    </nav>

    <section class="page-header">
        <div class="container">
            <div class="hero-content">
                <div class="breadcrumb">
                    <a href="index.php">Home</a>
                    <span class="separator">/</span>
                    <span class="current">Services</span>
                </div>
                <h1>Our Medical Services</h1>
                <p>Experience world-class healthcare with cutting-edge technology and compassionate care delivered by expert medical professionals.</p>
            </div>
        </div>
    </section>

    <section class="services-section" id="services">
        <div class="container">
            <div class="section-title">
                <h5>Our Services</h5>
                <h2>Excellent Medical Services</h2>
            </div>
            
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h4>Emergency Care</h4>
                    <p>24/7 emergency medical treatment with rapid response teams and state-of-the-art emergency facilities.</p>
                    <a href="#" class="btn btn-primary"><i class="fas fa-arrow-right"></i></a>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-procedures"></i>
                    </div>
                    <h4>Operation & Surgery</h4>
                    <p>Advanced surgical procedures performed by expert surgeons using minimally invasive techniques.</p>
                    <a href="#" class="btn btn-primary"><i class="fas fa-arrow-right"></i></a>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-stethoscope"></i>
                    </div>
                    <h4>Outdoor Checkup</h4>
                    <p>Comprehensive health checkups and preventive care services for all age groups.</p>
                    <a href="#" class="btn btn-primary"><i class="fas fa-arrow-right"></i></a>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-ambulance"></i>
                    </div>
                    <h4>Ambulance Service</h4>
                    <p>Fully equipped ambulances with paramedic staff available round the clock for emergencies.</p>
                    <a href="#" class="btn btn-primary"><i class="fas fa-arrow-right"></i></a>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-pills"></i>
                    </div>
                    <h4>Medicine & Pharmacy</h4>
                    <p>Well-stocked pharmacy with all essential medicines and expert pharmacists for guidance.</p>
                    <a href="#" class="btn btn-primary"><i class="fas fa-arrow-right"></i></a>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-microscope"></i>
                    </div>
                    <h4>Blood Testing</h4>
                    <p>Advanced diagnostic laboratory with accurate blood testing and quick result delivery.</p>
                    <a href="#" class="btn btn-primary"><i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </section>

    <section class="about">
        <div class="container">
            <div class="details-grid">
                <div class="details-content">
                    <div class="section-title" style="text-align: left; margin-bottom: 30px;">
                        <h5>Specialized Care</h5>
                        <h2>Advanced Medical Departments</h2>
                    </div>
                    <p>From emergency care to specialized treatments, we provide a complete range of medical services to meet all your healthcare needs.</p>
                    
                    <div class="features-grid">
                        <div class="feature-card">
                            <i class="fas fa-heartbeat"></i>
                            <h6>Cardiology</h6>
                            <small>Advanced cardiac care</small>
                        </div>
                        <div class="feature-card">
                            <i class="fas fa-brain"></i>
                            <h6>Neurology</h6>
                            <small>Brain & nerve care</small>
                        </div>
                        <div class="feature-card">
                            <i class="fas fa-baby"></i>
                            <h6>Pediatrics</h6>
                            <small>Child healthcare</small>
                        </div>
                        <div class="feature-card">
                            <i class="fas fa-bone"></i>
                            <h6>Orthopedics</h6>
                            <small>Bone & joint care</small>
                        </div>
                    </div>
                </div>
                
                <div class="sidebar">
                    <div class="sidebar-title">
                        <h3>All Services</h3>
                    </div>
                    
                    <ul class="services-list">
                        <li><a href="#" class="active"><i class="fas fa-plus-circle"></i>Emergency Care</a></li>
                        <li><a href="#"><i class="fas fa-scissors"></i>Surgery</a></li>
                        <li><a href="#"><i class="fas fa-stethoscope"></i>Outpatient</a></li>
                        <li><a href="#"><i class="fas fa-ambulance"></i>Ambulance</a></li>
                        <li><a href="#"><i class="fas fa-pills"></i>Pharmacy</a></li>
                        <li><a href="#"><i class="fas fa-microscope"></i>Diagnostics</a></li>
                        <li><a href="#"><i class="fas fa-heart"></i>Cardiology</a></li>
                        <li><a href="#"><i class="fas fa-brain"></i>Neurology</a></li>
                        <li><a href="#"><i class="fas fa-baby"></i>Pediatrics</a></li>
                        <li><a href="#"><i class="fas fa-tooth"></i>Dentistry</a></li>
                    </ul>
                    
                    <div class="contact-card">
                        <h3>Need Immediate Help?</h3>
                        <p>Our emergency team is available 24/7 to provide immediate medical assistance.</p>
                        <a href="tel:+92324567890" class="btn btn-accent">
                            <i class="fas fa-phone-alt"></i> Call Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    

    <section class="testimonials">
        <div class="container">
            <div class="section-title">
                <h5>Testimonials</h5>
                <h2>Patients Say About Our Services</h2>
            </div>
            
            <div class="testimonial-card">
                <div class="testimonial-avatar">
                    <img src="img/usama 2.jpg" alt="Patient">
                </div>
                <div class="testimonial-text">
                    "The care I received at LACH was exceptional. The doctors took time to explain everything and made me feel comfortable throughout my treatment."
                </div>
                <div class="testimonial-author">
                    <h3>Ali Parveiz</h3>
                    <p>Cardiac Patient</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h4>Get In Touch</h4>
                    <p>Contact us for any medical inquiries or appointments. We're here to help you with your healthcare needs.</p>
                    <div class="footer-contact">
                        <p><i class="fas fa-map-marker-alt"></i> 126-A MM Alam Road, Lahore, Pakistan</p>
                        <p><i class="fas fa-envelope"></i> info@lach.com</p>
                        <p><i class="fas fa-phone-alt"></i> +92 324 567 890</p>
                    </div>
                </div>
                
                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <div class="footer-links">
                        <a href="index.php"><i class="fas fa-chevron-right"></i> Home</a>
                        <a href="about.php"><i class="fas fa-chevron-right"></i> About Us</a>
                        <a href="service.php"><i class="fas fa-chevron-right"></i> Our Services</a>
                        <a href="team.php"><i class="fas fa-chevron-right"></i> Our Team</a>
                         
                        <a href="contact.php"><i class="fas fa-chevron-right"></i> Contact Us</a>
                    </div>
                </div>
                
                <div class="footer-col">
                    <h4>Our Experts</h4>
                    <div class="footer-links">
                        <?php 
                        // Get doctors from database
                        $doctors = getDoctors();
                        $footer_doctors = array_slice($doctors, 0, 5);
                        foreach ($footer_doctors as $doctor): 
                        ?>
                        <a href="team.php#doctor-<?php echo $doctor['id']; ?>">
                            <i class="fas fa-chevron-right"></i> <?php echo htmlspecialchars($doctor['name']); ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="footer-col">
                    <h4>Join Us</h4>
                    <p>Join our social media to receive the latest health tips and hospital updates.</p>
   
 
                    <div class="footer-social">
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <a href="index.php">Lahore Advanced Care Hospital.</a> <?php echo date('Y'); ?>. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const navLinks = document.getElementById('navLinks');

        mobileMenuBtn.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            mobileMenuBtn.innerHTML = navLinks.classList.contains('active') 
                ? '<i class="fas fa-times"></i>' 
                : '<i class="fas fa-bars"></i>';
        });

        // Navbar scroll effect
        const navbar = document.getElementById('navbar');

        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 50);
        });

        // Close mobile menu when clicking on a link
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 992) {
                    navLinks.classList.remove('active');
                    mobileMenuBtn.innerHTML = '<i class="fas fa-bars"></i>';
                }
            });
        });

        // Set min date for appointment form to tomorrow
        const dateInput = document.querySelector('input[type="date"]');
        if (dateInput) {
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const tomorrowFormatted = tomorrow.toISOString().split('T')[0];
            dateInput.min = tomorrowFormatted;
            dateInput.value = tomorrowFormatted;
        }

        // Service list active state
        document.querySelectorAll('.services-list a').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                document.querySelectorAll('.services-list a').forEach(l => l.classList.remove('active'));
                link.classList.add('active');
            });
        });
    </script>
</body>
</html>