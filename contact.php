<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Handle form submission
$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone'] ?? '');
    $inquiry_type = $conn->real_escape_string($_POST['inquiry_type']);
    $message = $conn->real_escape_string($_POST['message']);
    
    $sql = "INSERT INTO messages (name, email, phone, inquiry_type, message) 
            VALUES ('$name', '$email', '$phone', '$inquiry_type', '$message')";
    
    if ($conn->query($sql)) {
        $success = "Your message has been sent successfully! We will contact you soon.";
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Contact Us | LACH - Lahore Advanced Care Hospital</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="Contact Lahore Advanced Care Hospital">
    <meta name="description" content="Get in touch with LACH for appointments, inquiries, or medical consultations">
    
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

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-bottom: 60px;
        }

        .contact-card {
            background-color: white;
            border-radius: var(--radius);
            padding: 40px 30px;
            text-align: center;
            box-shadow: var(--shadow-light);
            transition: var(--transition);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .contact-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow);
            border-color: var(--primary);
        }

        .contact-icon {
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

        .contact-layout {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 60px;
            align-items: start;
        }

        .info-sidebar {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .info-card {
            background-color: white;
            border-radius: var(--radius);
            padding: 30px;
            box-shadow: var(--shadow-light);
        }

        .info-card h3 {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .info-card h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 3px;
            background-color: var(--primary);
        }

        .hours-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .hours-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid var(--gray-light);
        }

        .hours-item:last-child {
            border-bottom: none;
        }

        .hours-item .day {
            font-weight: 600;
            color: var(--dark);
        }

        .hours-item .time {
            color: var(--primary);
            font-weight: 500;
        }

        .emergency-banner {
            background: linear-gradient(135deg, #ff4757 0%, #ff3742 100%);
            color: white;
            border-radius: var(--radius);
            padding: 30px;
            text-align: center;
        }

        .emergency-banner h4 {
            color: white;
            margin-bottom: 10px;
        }

        .emergency-banner .phone {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 15px 0;
        }

        .emergency-banner p {
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 0;
        }

        .contact-form-container {
            background-color: white;
            border-radius: var(--radius);
            padding: 40px;
            box-shadow: var(--shadow);
        }

        .contact-form-container h3 {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .contact-form-container h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 3px;
            background-color: var(--primary);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 15px 20px;
            border: 1px solid var(--gray-light);
            border-radius: var(--radius-small);
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26, 118, 209, 0.1);
        }

        .form-group textarea {
            min-height: 150px;
            resize: vertical;
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

        .map-section {
            padding: 0 0 80px;
        }

        .map-container {
            width: 100%;
            height: 450px;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .cta-banner {
            background: var(--gradient-primary);
            color: white;
            padding: 60px 40px;
            text-align: center;
            border-radius: var(--radius);
            margin: 60px auto;
            max-width: 1200px;
        }

        .cta-banner h2 {
            color: white;
            margin-bottom: 20px;
        }

        .cta-banner p {
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
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
            .contact-grid,
            .contact-layout {
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
        }

        @media (max-width: 768px) {
            h1 { font-size: 2.25rem; }
            h2 { font-size: 1.75rem; }
            section { padding: 60px 0; }
            .hero { padding-top: 130px; padding-bottom: 80px; }
            .contact-grid { grid-template-columns: 1fr; }
            .form-row { grid-template-columns: 1fr; }
            .contact-form-container { padding: 30px 20px; }
            .cta-banner { margin: 40px 20px; padding: 40px 20px; }
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
                <li><a href="contact.php" class="active">Contact</a></li>
            </ul>
        </div>
    </nav>

    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="breadcrumb">
                    <a href="index.php">Home</a>
                    <span class="separator">/</span>
                    <span class="current">Contact Us</span>
                </div>
                <h1>Contact Our Medical Team</h1>
                <p>Get in touch with Lahore Advanced Care Hospital for appointments, inquiries, or consultations. Our compassionate team is here to support your healthcare journey.</p>
            </div>
        </div>
    </section>

    <section class="contact" id="contact">
        <div class="container">
            <?php if ($success): ?>
            <div style="background: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #a7f3d0;">
                <?php echo $success; ?>
            </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
            <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #fecaca;">
                <?php echo $error; ?>
            </div>
            <?php endif; ?>
            
            <div class="contact-grid">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h4>Visit Our Hospital</h4>
                    <p>126-A MM Alam Road, Lahore, Pakistan</p>
                    <p><small>Parking available on premises</small></p>
                </div>
                
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <h4>Call Us Anytime</h4>
                    <p>General: +92 324 567 890</p>
                    <p>Emergency: +92 324 567 891</p>
                </div>
                
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h4>Email Support</h4>
                    <p>info@lach.com</p>
                    <p>appointments@lach.com</p>
                </div>
            </div>
            
            <div class="contact-layout">
                <div class="info-sidebar">
                    <div class="info-card">
                        <h3>Working Hours</h3>
                        <div class="hours-list">
                            <div class="hours-item">
                                <span class="day">Monday - Friday</span>
                                <span class="time">8:00 AM - 8:00 PM</span>
                            </div>
                            <div class="hours-item">
                                <span class="day">Saturday</span>
                                <span class="time">9:00 AM - 6:00 PM</span>
                            </div>
                            <div class="hours-item">
                                <span class="day">Sunday</span>
                                <span class="time">9:00 AM - 4:00 PM</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-card">
                        <h3>Department Hours</h3>
                        <div class="hours-list">
                            <div class="hours-item">
                                <span class="day">Cardiology</span>
                                <span class="time">8:00 AM - 6:00 PM</span>
                            </div>
                            <div class="hours-item">
                                <span class="day">Neurology</span>
                                <span class="time">9:00 AM - 5:00 PM</span>
                            </div>
                            <div class="hours-item">
                                <span class="day">Orthopedics</span>
                                <span class="time">8:30 AM - 7:00 PM</span>
                            </div>
                            <div class="hours-item">
                                <span class="day">Pediatrics</span>
                                <span class="time">9:00 AM - 6:00 PM</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="emergency-banner">
                        <h4>Emergency Services</h4>
                        <div class="phone">+92 324 567 891</div>
                        <p>24/7 Emergency Care Available</p>
                        <p><small>No appointment needed for emergencies</small></p>
                    </div>
                </div>
                
                <div class="contact-form-container">
                    <h3>Send Your Inquiry</h3>
                    <form id="contactForm" method="POST" action="">
                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" name="name" placeholder="Full Name *" required>
                            </div>
                            <div class="form-group">
                                <input type="email" name="email" placeholder="Email Address *" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <input type="tel" name="phone" placeholder="Phone Number">
                        </div>
                        
                        <div class="form-group">
                            <select name="inquiry_type" required>
                                <option value="">Select Inquiry Type *</option>
                                <option value="General Inquiry">General Inquiry</option>
                                <option value="Appointment Booking">Appointment Booking</option>
                                <option value="Cardiology">Cardiology</option>
                                <option value="Neurology">Neurology</option>
                                <option value="Orthopedics">Orthopedics</option>
                                <option value="Billing Questions">Billing Questions</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <textarea name="message" placeholder="Your Message *" rows="5" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary form-submit">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="cta-banner">
            <h2>Need Immediate Assistance?</h2>
            <p>Our emergency department is open 24/7. For life-threatening conditions, call our emergency line immediately or visit our hospital directly.</p>
            <a href="tel:+92324567891" class="btn btn-accent">
                <i class="fas fa-phone-alt"></i> Call Emergency: +92 324 567 891
            </a>
        </div>
    </div>

    <section class="map-section">
        <div class="container">
            <div class="section-title">
                <h5>Find Us</h5>
                <h2>Our Location</h2>
            </div>
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3403.748577203475!2d74.331206315134!3d31.470799181392042!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39190483e58107d9%3A0xc23abe6ccc7e2462!2sMM%20Alam%20Rd%2C%20Lahore%2C%20Punjab%2C%20Pakistan!5e0!3m2!1sen!2s!4v1645603675491!5m2!1sen!2s" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
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

        // Contact form submission
        const contactForm = document.getElementById('contactForm');
        contactForm.addEventListener('submit', (e) => {
            const submitBtn = contactForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            submitBtn.disabled = true;
        });
        
        // Newsletter form submission
        const newsletterForm = document.querySelector('.newsletter-form');
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const emailInput = newsletterForm.querySelector('input[type="email"]');
                const submitBtn = newsletterForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                submitBtn.disabled = true;
                
                setTimeout(() => {
                    submitBtn.innerHTML = '<i class="fas fa-check"></i> Subscribed!';
                    emailInput.value = '';
                    
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }, 2000);
                }, 1000);
            });
        }
    </script>
</body>
</html>