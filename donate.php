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
    <meta charset="utf-8">
    <title>Donate - Lahore Advanced Care Hospital</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Donate to support healthcare services at Lahore Advanced Care Hospital" name="description">
    
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

        .impact-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            margin: 60px 0;
        }

        .impact-card {
            background-color: white;
            border-radius: var(--radius);
            padding: 30px 20px;
            text-align: center;
            box-shadow: var(--shadow-light);
            transition: var(--transition);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .impact-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow);
            border-color: var(--primary);
        }

        .impact-icon {
            width: 70px;
            height: 70px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 1.8rem;
        }

        .impact-card h3 {
            font-size: 2.2rem;
            color: var(--primary);
            margin-bottom: 8px;
            font-weight: 700;
        }

        .impact-card p {
            font-size: 1rem;
            color: var(--gray);
            margin: 0;
        }

        .bank-details {
            background-color: var(--light);
        }

        .bank-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .bank-card {
            background-color: white;
            border-radius: var(--radius);
            padding: 50px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(226, 232, 240, 0.8);
            margin-bottom: 50px;
        }

        .bank-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .bank-icon {
            width: 100px;
            height: 100px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            color: white;
            font-size: 2.5rem;
        }

        .bank-header h2 {
            margin-bottom: 20px;
            color: var(--dark);
        }

        .bank-header p {
            font-size: 1.1rem;
            color: var(--gray);
        }

        .bank-info {
            background: var(--primary-light);
            border-radius: var(--radius);
            padding: 40px;
            margin-bottom: 40px;
            border: 1px solid rgba(26, 118, 209, 0.2);
        }

        .bank-detail-row {
            display: flex;
            align-items: center;
            padding: 20px 0;
            border-bottom: 1px solid rgba(226, 232, 240, 0.6);
        }

        .bank-detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            flex: 0 0 220px;
            font-weight: 600;
            color: var(--dark);
            font-size: 1.1rem;
        }

        .detail-value {
            flex: 1;
            font-size: 1.1rem;
            color: var(--gray);
            font-family: 'Courier New', monospace;
            background: white;
            padding: 10px 15px;
            border-radius: var(--radius-small);
            border: 1px solid var(--gray-light);
        }

        .copy-btn {
            background-color: var(--secondary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: var(--radius-small);
            cursor: pointer;
            font-weight: 600;
            margin-left: 15px;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .copy-btn:hover {
            background-color: #1e6f5c;
            transform: translateY(-2px);
        }

        .copy-btn.copied {
            background-color: var(--secondary);
        }

        .qr-section {
            text-align: center;
            margin-top: 60px;
            padding-top: 50px;
            border-top: 2px dashed var(--gray-light);
        }

        .qr-code-container {
            max-width: 317px;
            margin: 30px auto;
            padding: 25px;
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow-light);
            border: 2px solid var(--primary-light);
     
        }


        .qr-placeholder {
            width: 100%;
            height: 250px;
            background: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-small);
        }

        .qr-placeholder i {
            font-size: 5rem;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .qr-placeholder p {
            color: var(--gray);
            font-size: 0.9rem;
        }

        .donation-uses {
            background: white;
        }

        .uses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
        }

        .use-card {
            background-color: white;
            border-radius: var(--radius);
            padding: 40px 35px;
            text-align: center;
            box-shadow: var(--shadow-light);
            transition: var(--transition);
            border: 1px solid rgba(226, 232, 240, 0.8);
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .use-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow);
            border-color: var(--primary);
        }

        .use-icon {
            width: 90px;
            height: 90px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            color: white;
            font-size: 2.2rem;
        }

        .use-card h3 {
            margin-bottom: 20px;
            color: var(--dark);
        }

        .use-card p {
            font-size: 1.05rem;
            color: var(--gray);
            line-height: 1.7;
        }

        .cta-section {
            background: var(--gradient-primary);
            color: white;
            text-align: center;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 800" opacity="0.1"><path d="M0,0H800V800H0Z" fill="none"/><path d="M250,250l500,500M750,250l-500,500" stroke="white" stroke-width="2"/></svg>');
            background-size: cover;
        }

        .cta-content {
            position: relative;
            z-index: 1;
            max-width: 800px;
            margin: 0 auto;
        }

        .cta-content h2 {
            color: white;
            margin-bottom: 20px;
            font-size: 2.8rem;
        }

        .cta-content p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.2rem;
            margin-bottom: 40px;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .cta-buttons .btn {
            min-width: 180px;
        }

        .cta-buttons .btn-primary {
            background: white;
            color: var(--primary);
        }

        .cta-buttons .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .cta-buttons .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
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
            .impact-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 992px) {
            .hero::before {
                width: 100%;
                height: 40%;
                top: auto;
                bottom: 0;
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
            }
            
            .nav-links.active {
                left: 0;
            }
            
            .nav-links li {
                margin: 15px 0;
            }
            
            .bank-card {
                padding: 40px;
            }
            
            .detail-label {
                flex: 0 0 180px;
            }
        }

        @media (max-width: 768px) {
            h1 { font-size: 2.25rem; }
            h2 { font-size: 1.75rem; }
            h3 { font-size: 1.6rem; }
            
            section { padding: 60px 0; }
            .hero { padding-top: 130px; padding-bottom: 80px; }
            
            .bank-detail-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .detail-label {
                flex: none;
                font-size: 1.05rem;
            }
            
            .detail-value {
                width: 100%;
                font-size: 1rem;
            }
            
            .copy-btn {
                margin-left: 0;
                margin-top: 10px;
                width: 100%;
                justify-content: center;
            }
            
            .impact-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .cta-buttons .btn {
                width: 100%;
                max-width: 300px;
            }
            
            .uses-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .container { padding: 0 20px; }
            h1 { font-size: 1.875rem; }
            h2 { font-size: 1.5rem; }
            .btn { padding: 12px 24px; font-size: 0.95rem; }
            .bank-card { padding: 30px 25px; }
            .bank-info { padding: 30px; }
            .footer-grid { grid-template-columns: 1fr; }
            .hero-content p { font-size: 1.1rem; }
            .cta-content h2 { font-size: 2.2rem; }
            .cta-content p { font-size: 1.1rem; }
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
                    <span class="current">Donate</span>
                </div>
                <h1>Your Donation Saves Lives</h1>
                <p>Join us in providing essential healthcare to those in need. Every contribution helps us expand our medical services, support critical research, and transform lives through compassionate care.</p>
                
                <div class="impact-grid">
                    <div class="impact-card">
                        <div class="impact-icon">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                        <h3>1,000+</h3>
                        <p>Patients Helped Annually</p>
                    </div>
                    <div class="impact-card">
                        <div class="impact-icon">
                            <i class="fas fa-hospital"></i>
                        </div>
                        <h3>50+</h3>
                        <p>Medical Initiatives</p>
                    </div>
                    <div class="impact-card">
                        <div class="impact-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>500+</h3>
                        <p>Generous Donors</p>
                    </div>
                    <div class="impact-card">
                        <div class="impact-icon">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <h3>24/7</h3>
                        <p>Emergency Care</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bank-details">
        <div class="container">
            <div class="bank-container">
                <div class="section-title">
                    <h5>Bank Transfer</h5>
                    <h2>Donate via Bank Transfer</h2>
                    <p>Make a direct impact by transferring your donation to our hospital's trust account. All donations are tax-deductible and come with a formal receipt.</p>
                </div>
                
                <div class="bank-card">
                    <div class="bank-header">
                        <div class="bank-icon">
                            <i class="fas fa-university"></i>
                        </div>
                        <h2>Lahore Advanced Care Hospital Trust</h2>
                        <p>Account Details for Secure Donations</p>
                    </div>
                    
                    <div class="bank-info">
                        <div class="bank-detail-row">
                            <div class="detail-label">Bank Name</div>
                            <div class="detail-value">Habib Bank Limited (HBL)</div>
                        </div>
                        
                        <div class="bank-detail-row">
                            <div class="detail-label">Account Name</div>
                            <div class="detail-value">Lahore Advanced Care Hospital Trust</div>
                        </div>
                        
                        <div class="bank-detail-row">
                            <div class="detail-label">Account Number</div>
                            <div class="detail-value">
                                0159-7900-1234-5678
                            </div>
                            <button class="copy-btn" data-copy="0159-7900-1234-5678">
                                <i class="far fa-copy"></i> Copy
                            </button>
                        </div>
                        
                        <div class="bank-detail-row">
                            <div class="detail-label">IBAN Number</div>
                            <div class="detail-value">
                                PK36HABB00159790012345678
                            </div>
                            <button class="copy-btn" data-copy="PK36HABB00159790012345678">
                                <i class="far fa-copy"></i> Copy
                            </button>
                        </div>
                        
                        <div class="bank-detail-row">
                            <div class="detail-label">Branch Code</div>
                            <div class="detail-value">0159</div>
                        </div>
                        
                        <div class="bank-detail-row">
                            <div class="detail-label">Branch Address</div>
                            <div class="detail-value">Gulberg Main Branch, MM Alam Road, Lahore</div>
                        </div>
                        
                        <div class="bank-detail-row">
                            <div class="detail-label">Swift Code</div>
                            <div class="detail-value">HABBPKKA</div>
                        </div>
                    </div>
                    
                    <div class="qr-section">
                        <h3>Scan QR Code</h3>
                        <p>For instant mobile banking donations, scan this QR code:</p>
                        <div class="qr-code-container">
                            <div class="qr-placeholder">
                                <div>
                                    <img src="img/qr.jpg" style="height: 260px; position:relative; top:25px">
                                    <p>Hospital Donation QR Code</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="donation-uses">
        <div class="container">
            <div class="section-title">
                <h5>Your Impact</h5>
                <h2>How Your Donation Helps</h2>
                <p>Every contribution makes a tangible difference in our healthcare services and community support programs.</p>
            </div>
            
            <div class="uses-grid">
                <div class="use-card">
                    <div class="use-icon">
                        <i class="fas fa-ambulance"></i>
                    </div>
                    <h3>Emergency Response</h3>
                    <p>Funds our 24/7 ambulance services, emergency room equipment upgrades, and critical life-saving medications for underprivileged patients.</p>
                </div>
                
                <div class="use-card">
                    <div class="use-icon">
                        <i class="fas fa-procedures"></i>
                    </div>
                    <h3>Medical Equipment</h3>
                    <p>Enables acquisition and maintenance of advanced medical technology including MRI machines, ventilators, and surgical instruments.</p>
                </div>
                
                <div class="use-card">
                    <div class="use-icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h3>Staff Training</h3>
                    <p>Supports continuous professional development for our medical teams, ensuring they stay updated with the latest healthcare practices.</p>
                </div>
                
                <div class="use-card">
                    <div class="use-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Patient Assistance</h3>
                    <p>Provides financial support for underprivileged patients, organizes free medical camps, and subsidizes treatment for critical illnesses.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Ready to Make a Difference?</h2>
                <p>Your generous contribution today can provide hope and healing for someone in need. Together, we can build a healthier community.</p>
                <div class="cta-buttons">
                    <a href="contact.php" class="btn btn-primary">
                        <i class="fas fa-envelope"></i> Contact for More Info
                    </a>
                    <a href="#bank-details" class="btn btn-outline-light">
                        <i class="fas fa-donate"></i> Donate Now
                    </a>
                </div>
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
                    <?php
                    $doctors = getDoctors();
                    $footer_doctors = array_slice($doctors, 0, 5);
                    ?>
                    <div class="footer-links">
                        <?php foreach ($footer_doctors as $doctor): ?>
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

        // Copy button functionality
        document.querySelectorAll('.copy-btn').forEach(button => {
            button.addEventListener('click', function() {
                const text = this.getAttribute('data-copy');
                navigator.clipboard.writeText(text).then(() => {
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-check"></i> Copied!';
                    this.classList.add('copied');
                    
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.classList.remove('copied');
                    }, 2000);
                });
            });
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>