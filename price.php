<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Get doctors from database
$doctors = getDoctors();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing - LACH Hospital</title>
        <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ========== CSS VARIABLES ========== */
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
            --shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-light: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            --radius: 16px;
            --radius-small: 8px;
            --transition: all 0.3s ease;
            --gradient-primary: linear-gradient(135deg, #1a76d1 0%, #0d5aa7 100%);
        }

        /* ========== BASE STYLES ========== */
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

        /* ========== BUTTONS ========== */
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

       

        /* ========== NAVBAR ========== */
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
            /* left: 20px; */
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

         .btn-login {
            background-color:var(--primary);
            color: white;
            padding: 10px 24px;
            margin-left: 15px;
            width:100px;
            height: 42px;
            border-radius: 12px;
        }

        .btn-login:hover {
            background-color:var(--primary);
            transform: translateY(-3px);
        }

        .btn-donate {
            background-color: var(--secondary);
            color: white;
            margin-left: 15px;
            width: 90px;
            height: 42px;
            border-radius: 15px;
            width:100px;
        }

        .btn-donate:hover {
            background-color: var(--secondary);
            transform: translateY(-3px);
        }
        .mobile-menu-btn {
            display: none;
            font-size: 1.5rem;
            color: var(--dark);
            cursor: pointer;
            background: none;
            border: none;
        }

        /* ========== HERO SECTION ========== */
        .hero {
            padding-top: 150px;
            padding-bottom: 100px;
            background: linear-gradient(135deg, #e8f4ff 0%, #f0f9ff 100%);
            position: relative;
            overflow: hidden;
        }

        .hero::before {
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
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            position: relative;
            z-index: 1;
            display: flex;
                position: relative;
            bottom: 10px;
        }
.pt-1{
    width: 50%;
    position: relative;
    bottom: 10px;
    left: 20px;
}
.pt-2{
    width: 40%;
    position: relative;
    right: 20px;
    bottom: 10px;
}
        .hero-text h5 {
            color: var(--primary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        
        }

        .hero-text h1 {
            margin-bottom: 25px;
            color: var(--dark);
        }

        .hero-text p {
            font-size: 1.1rem;
            margin-bottom: 35px;
            color: var(--gray);
            max-width: 90%;
        }

        .hero-btns {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .hero-image {
            display: flex;
            justify-content: flex-end;
        }

        .hero-image img {
            max-width: 900px;
            position: relative;
            left: 320px;
            bottom: 60px;
        }

        /* ========== ABOUT SECTION ========== */
        .about-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 60px;
            align-items: center;
        }

        .about-image {
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            height: 500px;
        }

        .about-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .about-image:hover img {
            transform: scale(1.03);
        }

        .about-features {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 40px;
        }

        .feature-box {
            background-color: var(--primary-light);
            padding: 25px;
            border-radius: var(--radius);
            text-align: center;
            transition: var(--transition);
            border: 1px solid rgba(26, 118, 209, 0.1);
        }

        .feature-box:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-light);
            border-color: var(--primary);
        }

        .feature-box i {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 15px;
        }

        /* ========== SERVICES SECTION ========== */
        .services {
            background-color: var(--light);
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



        /* ========== PRICING SECTION ========== */
        .pricing-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
        }

        .price-card {
            background-color: white;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow-light);
            transition: var(--transition);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .price-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow);
        }

        .price-image {
            height: 200px;
            overflow: hidden;
            position: relative;
        }

        .price-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .price-card:hover .price-image img {
            transform: scale(1.05);
        }

        .price-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(26, 118, 209, 0.9), rgba(45, 143, 124, 0.9));
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .price-overlay .price {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 10px 0;
        }

        .price-content {
            padding: 30px;
            text-align: center;
        }

        .price-content p {
            margin-bottom: 10px;
            position: relative;
            padding-left: 25px;
            text-align: left;
        }

        .price-content p:before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--secondary);
            font-weight: bold;
        }

        /* ========== DOCTORS SECTION ========== */
        .doctors {
            background-color: var(--light);
        }

        .doctors-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .doctor-card {
            background-color: white;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow-light);
            transition: var(--transition);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .doctor-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow);
        }

        .doctor-image {
            height: 450px;
            overflow: hidden;
        }

        .doctor-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .doctor-card:hover .doctor-image img {
            transform: scale(1.05);
        }

        .doctor-info {
            padding: 30px;
        }

        .doctor-info .specialty {
            color: var(--primary);
            font-style: italic;
            margin-bottom: 15px;
            display: block;
            font-weight: 500;
        }

        .doctor-social {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .doctor-social a {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .doctor-social a:hover {
            background-color: var(--primary);
            color: white;
            transform: translateY(-3px);
        }

        /* ========== TESTIMONIALS ========== */
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


        /* ========== FOOTER ========== */
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

        /* ========== RESPONSIVE STYLES ========== */
        @media (max-width: 1200px) {
            h1 { font-size: 2.75rem; }
            h2 { font-size: 2rem; }
            .pricing-cards { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 992px) {
            .hero-content, .about-container, .appointment-container {
                grid-template-columns: 1fr;
                gap: 50px;
            }
            .hero-content{
                display: flex;
                flex-direction: column-reverse;
                position: relative;
                right: 80px;
            }
            .pt-1{
                position: relative;
                width: 80%;
                left: 70px;
            
            }
            .pt-2{
                width: 70%;

            }
            .hero::before {
                width: 100%;
                height: 40%;
                top: auto;
                bottom: 0;
            }
            
            .hero-image {
                justify-content: center;
                order: -1;
                position: relative;
                width: 640px;
                right: 90px;
            }
            
            .hero-image img {
                max-width: 80%;
                left: 0;
                bottom: 0;
            }
            
            .services-grid, .doctors-grid, .blog-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .footer-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 40px 30px;
            }
            
            .mobile-menu-btn {
                display: block;
                position: relative;
                left: 210px;
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
            }
            
            .nav-links.active {
                left: 0;
            }
            
            .nav-links li {
                margin: 15px 0;
            }
            
            .nav-links .btn {
                margin: 10px 0;
            }
        }

        @media (max-width: 768px) {
            h1 { font-size: 2.25rem; }
            h2 { font-size: 1.75rem; }
            section { padding: 60px 0; }
            .hero { padding-top: 130px; padding-bottom: 80px; }
            .services-grid, .pricing-cards, .doctors-grid, .blog-grid {
                grid-template-columns: 1fr;
            }
            .form-row { grid-template-columns: 1fr; }
            .appointment-form { padding: 30px 20px; }
        }

        @media (max-width: 576px) {
            .container { padding: 0 20px; }
            h1 { font-size: 1.875rem; }
            h2 { font-size: 1.5rem; }
            .btn { padding: 12px 24px; font-size: 0.95rem; }
            .about-features { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
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
                <li><a href="service.php">Services</a></li>
                <li><a href="price.php" class="active">Pricing</a></li>
                <li><a href="team.php">Our Team</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </div>
    </nav>

    <section class="hero" style="padding-top: 150px; padding-bottom: 100px;">
        <div class="container">
            <div class="hero-content" style="text-align: center;">
                <h1>Our Medical Packages</h1>
                <p>Affordable and comprehensive healthcare packages tailored to your needs.</p>
            </div>
        </div>
    </section>

    <section class="pricing" id="pricing">
        <div class="container">
            <div class="section-title">
                <h5>Medical Packages</h5>
                <h2>Awesome Medical Programs</h2>
            </div>
            
            <div class="pricing-cards">
                            <div class="price-card">
                    <div class="price-image">
                        <img src="img/price-1.jpg" alt="Pregnancy Care">
                        <div class="price-overlay">
                            <h3>Pregnancy Care</h3>
                            <div class="price">$49<small>/Year</small></div>
                        </div>
                    </div>
                    <div class="price-content">
                        <p>Emergency Medical Treatment</p>
                        <p>Highly Experienced Doctors</p>
                        <p>Highest Success Rate</p>
                        <p>Telephone Service</p>
                        <a href="#" class="btn btn-primary" style="margin-top: 24px;">Apply Now</a>
                    </div>
                </div>
                <div class="price-card">
                    <div class="price-image">
                        <img src="img/price-1.jpg" alt="Pregnancy Care">
                        <div class="price-overlay">
                            <h3>Pregnancy Care</h3>
                            <div class="price">$49<small>/Year</small></div>
                        </div>
                    </div>
                    <div class="price-content">
                        <p>Emergency Medical Treatment</p>
                        <p>Highly Experienced Doctors</p>
                        <p>Highest Success Rate</p>
                        <p>Telephone Service</p>
                        <a href="appointment.php" class="btn btn-primary" style="margin-top: 24px;">Apply Now</a>
                    </div>
                </div>
                
                <div class="price-card">
                    <div class="price-image">
                        <img src="img/price-2.jpg" alt="Health Checkup">
                        <div class="price-overlay">
                            <h3>Health Checkup</h3>
                            <div class="price">$99<small>/Year</small></div>
                        </div>
                    </div>
                    <div class="price-content">
                        <p>Comprehensive Health Screening</p>
                        <p>Advanced Diagnostic Tests</p>
                        <p>Personalized Health Plan</p>
                        <p>Follow-up Consultations</p>
                        <a href="appointment.php" class="btn btn-primary" style="margin-top: 20px;">Apply Now</a>
                    </div>
                </div>
                
                <div class="price-card">
                    <div class="price-image">
                        <img src="img/price-3.jpg" alt="Dental Care">
                        <div class="price-overlay">
                            <h3>Dental Care</h3>
                            <div class="price">$149<small>/Year</small></div>
                        </div>
                    </div>
                    <div class="price-content">
                        <p>Regular Dental Checkups</p>
                        <p>Teeth Cleaning & Whitening</p>
                        <p>Orthodontic Treatments</p>
                        <p>Emergency Dental Care</p>
                        <a href="appointment.php" class="btn btn-primary" style="margin-top: 45px;">Apply Now</a>
                    </div>
                </div>
                
                <div class="price-card">
                    <div class="price-image">
                        <img src="img/price-4.jpg" alt="Operation & Surgery">
                        <div class="price-overlay">
                            <h3>Operation & Surgery</h3>
                            <div class="price">$199<small>/Year</small></div>
                        </div>
                    </div>
                    <div class="price-content">
                        <p>Pre-surgical Consultations</p>
                        <p>Advanced Surgical Procedures</p>
                        <p>Post-operative Care</p>
                        <p>Rehabilitation Support</p>
                        <a href="appointment.php" class="btn btn-primary" style="margin-top: 47px;">Apply Now</a>
                    </div>
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
                        // Display first 5 doctors in footer
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
</body>
</html>