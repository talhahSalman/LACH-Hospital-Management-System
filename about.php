<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Get doctors for the team section
$doctors = getDoctors();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>About Us - LACH Hospital</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Modern Hospital Website Template" name="keywords">
    <meta content="Premium healthcare website template for medical centers and hospitals" name="description">

    <link href="img/favicon.ico" rel="icon">
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
            --radius: 20px;
            --radius-small: 10px;
            --transition: all 0.3s ease;
            --gradient-primary: linear-gradient(135deg, #1a76d1 0%, #0d5aa7 100%);
            --gradient-secondary: linear-gradient(135deg, #2d8f7c 0%, #1e6f5c 100%);
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
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            line-height: 1.3;
            margin-bottom: 1rem;
        }

        h1 {
            font-size: 3.25rem;
            font-weight: 700;
        }

        h2 {
            font-size: 2.25rem;
        }

        h3 {
            font-size: 1.75rem;
        }

        h4 {
            font-size: 1.5rem;
        }

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
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        section {
            padding: 100px 0;
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
            position: relative;
            font-size: 0.9rem;
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

        .section-title h2 {
            margin-top: 15px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 16px 36px;
            border-radius: 50px;
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            border: none;
            transition: var(--transition);
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            gap: 10px;
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

        .hero {
            padding-top: 150px;
            padding-bottom: 120px;
            background: linear-gradient(135deg, #e8f4ff 0%, #f0f9ff 100%);
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 50%;
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
            font-size: 1.2rem;
            color: var(--gray);
            margin-bottom: 40px;
            line-height: 1.8;
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

        
        .info-features {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 40px;
        }

        .info-feature {
            display: flex;
            align-items: flex-start;
            gap: 20px;
        }

        .info-feature i {
            color: var(--primary);
            font-size: 1.5rem;
            margin-top: 5px;
        }

        .info-feature h4 {
            margin-bottom: 5px;
            font-size: 1.2rem;
        }

        
 .about-story {
            background-color: white;
        }

        .story-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
        }

        .story-image {
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            height: 550px;
        }

        .story-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .story-image:hover img {
            transform: scale(1.05);
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

        .feature-card h6 {
            font-size: 1.2rem;
            margin-bottom: 8px;
        }

        .feature-card small {
            color: var(--gray);
            font-weight: 500;
            font-size: 0.95rem;
        }

        .mission-vision {
            background-color: var(--light);
        }

        .mv-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 50px;
        }

        .mv-card {
            background-color: white;
            padding: 60px 50px;
            border-radius: var(--radius);
            box-shadow: var(--shadow-light);
            transition: var(--transition);
            text-align: center;
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .mv-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow);
        }

        .mv-card i {
            font-size: 3.5rem;
            color: var(--primary);
            margin-bottom: 30px;
        }

        .mv-card h3 {
            margin-bottom: 25px;
        }

        .mv-card p {
            font-size: 1.1rem;
            line-height: 1.8;
        }

        .stats {
            background: var(--gradient-primary);
            color: white;
            text-align: center;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 40px;
        }

        .stat-card {
            padding: 40px 20px;
            position: relative;
        }

        .stat-card i {
            font-size: 3rem;
            margin-bottom: 25px;
            opacity: 0.9;
        }

        .stat-number {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            font-family: 'Poppins', sans-serif;
        }

        .stat-label {
            font-size: 1.2rem;
            opacity: 0.9;
            font-weight: 500;
        }

        .team-section {
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

        /* ========== STATS SECTION ========== */
        .stats-section {
            background: var(--gradient-primary);
            color: white;
            padding: 80px 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            text-align: center;
        }

        .stat-item {
            padding: 30px;
        }

        .stat-number {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            font-family: 'Poppins', sans-serif;
        }

        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* ========== CTA SECTION ========== */
        .cta-section {
            background-color: var(--light);
            text-align: center;
            padding: 80px 0;
        }

        .cta-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .cta-title {
            color: var(--dark);
            margin-bottom: 20px;
        }

        .cta-description {
            font-size: 1.2rem;
            margin-bottom: 40px;
            color: var(--gray);
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
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
            .container {
                max-width: 1140px;
            }
            
            h1 {
                font-size: 2.75rem;
            }
            
            h2 {
                font-size: 2rem;
            }
        }

        @media (max-width: 992px) {
            .container {
                max-width: 720px;
            }
            
            .appointment-container {
                grid-template-columns: 1fr;
                gap: 50px;
            }
            
            .hero::before {
                width: 100%;
                height: 40%;
                top: auto;
                bottom: 0;
            }
            
            .footer-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 50px 40px;
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
            }
            
            .nav-links.active {
                left: 0;
            }
            
            .nav-links li {
                margin: 15px 0;
            }
        }

        @media (max-width: 768px) {
            .container {
                max-width: 540px;
            }
            
            h1 {
                font-size: 2.25rem;
            }
            
            h2 {
                font-size: 1.75rem;
            }
            
            section {
                padding: 80px 0;
            }
            
            .hero {
                padding-top: 130px;
                padding-bottom: 100px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .appointment-form-container {
                padding: 30px 20px;
            }
            
            .doctors-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .container {
                padding: 0 20px;
            }
            
            h1 {
                font-size: 1.875rem;
            }
            
            h2 {
                font-size: 1.5rem;
            }
            
            .btn {
                padding: 14px 28px;
                font-size: 1rem;
            }
            
            .footer-grid {
                grid-template-columns: 1fr;
            }
            
            .section-title {
                margin-bottom: 50px;
            }
            
            .hero-content p {
                font-size: 1.1rem;
            }
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
                <li><a href="about.php" class="active">About</a></li>
                <li><a href="service.php">Services</a></li>
                <li><a href="price.php">Pricing</a></li>
                <li><a href="team.php">Our Team</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </div>
    </nav>

    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="breadcrumb">
                    <a href="index.php">Home</a>
                    <span class="separator">/</span>
                    <span class="current">About Us</span>
                </div>
                <h1>About Lahore Advanced Care Hospital</h1>
                <p>Discover our commitment to providing exceptional healthcare services with compassion, innovation, and excellence since 2005. We're dedicated to making quality healthcare accessible to everyone.</p>
                <a href="#story" class="btn btn-primary">
                    <i class="fas fa-arrow-down"></i> Explore Our Story
                </a>
            </div>
        </div>
    </section>

    <section class="about-story" id="story">
        <div class="container">
            <div class="story-grid">
                <div class="story-image">
                    <img src="img/about.jpg" alt="LACH Hospital">
                </div>
                <div class="story-content">
                    <div class="section-title" style="text-align: left; margin-bottom: 30px;">
                        <h5>Our Journey</h5>
                        <h2>Excellence in Healthcare Since 2005</h2>
                    </div>
                    <p>Founded with a vision to transform healthcare in Pakistan, Lahore Advanced Care Hospital (LACH) has grown from a modest 50-bed facility to a 300-bed multi-specialty hospital recognized for clinical excellence.</p>
                    <p>Our journey is marked by relentless dedication to patient care, technological innovation, and medical education. Today, we stand as a beacon of hope and healing for thousands of families across the region.</p>
                    
                    <div class="features-grid">
                        <div class="feature-card">
                            <i class="fas fa-user-md"></i>
                            <h6>Expert Doctors</h6>
                            <small>Board-Certified Specialists</small>
                        </div>
                        <div class="feature-card">
                            <i class="fas fa-heartbeat"></i>
                            <h6>Advanced Care</h6>
                            <small>State-of-the-Art Technology</small>
                        </div>
                        <div class="feature-card">
                            <i class="fas fa-clock"></i>
                            <h6>24/7 Services</h6>
                            <small>Emergency & Critical Care</small>
                        </div>
                        <div class="feature-card">
                            <i class="fas fa-award"></i>
                            <h6>Accredited</h6>
                            <small>International Standards</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mission-vision">
        <div class="container">
            <div class="section-title">
                <h5>Our Philosophy</h5>
                <h2>Mission & Vision</h2>
            </div>
            
            <div class="mv-grid">
                <div class="mv-card">
                    <i class="fas fa-bullseye"></i>
                    <h3>Our Mission</h3>
                    <p>To provide compassionate, accessible, high-quality healthcare to our community while promoting wellness, educating healthcare professionals, and advancing medical research for a healthier tomorrow.</p>
                </div>
                
                <div class="mv-card">
                    <i class="fas fa-eye"></i>
                    <h3>Our Vision</h3>
                    <p>To be Pakistan's leading healthcare institution, setting new standards in patient-centered care, medical innovation, and community health for generations to come.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card">
                    <i class="fas fa-user-md"></i>
                    <div class="stat-number">250+</div>
                    <div class="stat-label">Expert Doctors</div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-procedures"></i>
                    <div class="stat-number">300+</div>
                    <div class="stat-label">Hospital Beds</div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-user-friends"></i>
                    <div class="stat-number">50K+</div>
                    <div class="stat-label">Happy Patients</div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-calendar-alt"></i>
                    <div class="stat-number">20+</div>
                    <div class="stat-label">Years Experience</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Doctors Section -->
    <section class="doctors" id="doctors">
        <div class="container">
            <div class="section-title">
                <h5>Our Doctors</h5>
                <h2>Qualified Healthcare Professionals</h2>
            </div>
            
            <div class="doctors-grid">
                <?php if (empty($doctors)): ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                        <i class="fas fa-user-md" style="font-size: 3rem; color: var(--gray-light); margin-bottom: 20px;"></i>
                        <h3>Our Medical Team</h3>
                        <p>Our team of expert doctors will be listed here soon.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($doctors as $doctor): ?>
                    <div class="doctor-card">
                        <div class="doctor-image">
                            <?php if ($doctor['image']): ?>
                            <img src="img/uploads/<?php echo htmlspecialchars($doctor['image']); ?>" alt="Doctor <?php echo htmlspecialchars($doctor['name']); ?>">
                            <?php else: ?>
                            <img src="img/team-default.jpg" alt="Doctor <?php echo htmlspecialchars($doctor['name']); ?>">
                            <?php endif; ?>
                        </div>
                        <div class="doctor-info">
                            <h3><?php echo htmlspecialchars($doctor['name']); ?></h3>
                            <span class="specialty"><?php echo htmlspecialchars($doctor['specialty']); ?></span>
                            <p><?php echo nl2br(htmlspecialchars($doctor['experience'])); ?></p>

                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="cta">
        <div class="container">
            <h2>Ready for Exceptional Healthcare?</h2>
            <p>Join thousands of satisfied patients who trust LACH for their healthcare needs. Take the first step towards better health with our expert medical team.</p>
            <div class="cta-btns">
                <a href="appointment.php" class="btn btn-primary">
                    <i class="fas fa-calendar-check"></i> Book Appointment
                </a>
                <a href="contact.php" class="btn btn-primary">
                    <i class="fas fa-phone-alt"></i> Contact Us Now
                </a>
            </div>
        </div>
    </section>

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
    </script>
</body>
</html>