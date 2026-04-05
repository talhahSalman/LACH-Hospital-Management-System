<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Get departments
$departments = getDepartments();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $department_id = (int)$_POST['department'];
    $doctor_id = $_POST['doctor'] ? (int)$_POST['doctor'] : 'NULL';
    $appointment_date = $conn->real_escape_string($_POST['date']);
    $appointment_time = $conn->real_escape_string($_POST['time']);
    $message = $conn->real_escape_string($_POST['message']);
    
    $sql = "INSERT INTO appointments (first_name, last_name, email, phone, department_id, doctor_id, appointment_date, appointment_time, message) 
            VALUES ('$first_name', '$last_name', '$email', '$phone', $department_id, $doctor_id, '$appointment_date', '$appointment_time', '$message')";
    
    if ($conn->query($sql)) {
        $success = "Your appointment has been scheduled successfully! We will contact you shortly.";
    } else {
        $error = "Error: " . $conn->error;
    }
}

// Get doctors for selected department (for AJAX)
if (isset($_GET['dept_id'])) {
    $dept_id = (int)$_GET['dept_id'];
    $department = $conn->query("SELECT name FROM departments WHERE id = $dept_id")->fetch_assoc();
    
    if ($department) {
        $doctors = $conn->query("SELECT * FROM doctors WHERE department = '{$department['name']}' ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);
        
        echo '<option value="">Select Doctor</option>';
        echo '<option value="">Any Available Doctor</option>';
        foreach ($doctors as $doctor) {
            echo "<option value=\"{$doctor['id']}\">{$doctor['name']} - {$doctor['specialty']}</option>";
        }
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Appointment - LACH Hospital</title>
   
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

        .appointment-section {
            background-color: white;
        }

        .appointment-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: start;
        }

        .appointment-info h2 {
            margin-bottom: 20px;
        }

        .appointment-info p {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 30px;
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

        .appointment-form-container {
            background: white;
            border-radius: var(--radius);
            padding: 40px;
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-light);
        }

        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-header h3 {
            color: var(--dark);
            margin-bottom: 10px;
        }

        .form-header p {
            color: var(--gray);
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
            font-size: 0.95rem;
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

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-submit {
            width: 100%;
            padding: 16px;
            margin-top: 10px;
            font-size: 1.1rem;
        }

        .form-note {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
            color: var(--gray);
        }

        .doctors-section {
            background-color: var(--light);
        }

        .doctors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .doctor-card {
            background: white;
            border-radius: var(--radius);
            padding: 30px;
            text-align: center;
            box-shadow: var(--shadow-light);
            transition: var(--transition);
            border: 1px solid var(--gray-light);
        }

        .doctor-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow);
            border-color: var(--primary);
        }

        .doctor-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 20px;
            border: 4px solid var(--primary-light);
        }

        .doctor-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .doctor-card h4 {
            margin-bottom: 5px;
        }

        .doctor-specialty {
            color: var(--primary);
            font-weight: 500;
            margin-bottom: 15px;
            display: block;
        }

        .doctor-availability {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin: 15px 0;
            color: var(--gray);
            font-size: 0.9rem;
        }

        .doctor-availability i {
            color: var(--secondary);
        }

        .availability-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: var(--secondary);
        }

        .availability-dot.available {
            background-color: var(--secondary);
        }

        .availability-dot.busy {
            background-color: var(--accent);
        }

        .cta-section {
            background: var(--gradient-primary);
            color: white;
            text-align: center;
            padding: 100px 0;
        }

        .cta-content {
            max-width: 700px;
            margin: 0 auto;
        }

        .cta-content h2 {
            color: white;
            margin-bottom: 20px;
        }

        .cta-content p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1rem;
            margin-bottom: 40px;
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
                    <span class="current">Book Appointment</span>
                </div>
                <h1>Schedule Your Appointment</h1>
                <p>Book your medical appointment conveniently online with our expert doctors. Choose your preferred time and department for a seamless healthcare experience.</p>
                <a href="#appointment-form" class="btn btn-primary">
                    <i class="fas fa-calendar-check"></i> Book Now
                </a>
            </div>
        </div>
    </section>
    
    <section class="appointment-section" id="appointment-form">
        <div class="container">
                         <div class="appointment-info">
                    <h2>Easy Online Booking</h2>
                    <p>Schedule your appointment with our medical specialists in just a few clicks. Our online booking system ensures you get the care you need when you need it.</p>
                    <p>All appointments are confirmed via email and SMS, with reminders sent 24 hours before your scheduled time.</p>
                    
                    <div class="info-features">
                        <div class="info-feature">
                            <i class="fas fa-clock"></i>
                            <div>
                                <h4>Flexible Timing</h4>
                                <p>Choose from morning, afternoon, or evening slots according to your convenience.</p>
                            </div>
                        </div>
                        
                        <div class="info-feature">
                            <i class="fas fa-user-md"></i>
                            <div>
                                <h4>Expert Doctors</h4>
                                <p>Book appointments with board-certified specialists across various medical fields.</p>
                            </div>
                        </div>
                        
                        <div class="info-feature">
                            <i class="fas fa-calendar-alt"></i>
                            <div>
                                <h4>Quick Confirmation</h4>
                                <p>Receive instant confirmation and reminders for your scheduled appointment.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="appointment-form-container">
                    <div class="form-header">
                        <h3>Book Your Appointment</h3>
                        <p>Fill out the form below to schedule your visit</p>
                    </div>
                    
                    <?php if (isset($success)): ?>
                    <div style="background: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                        <?php echo $success; ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($error)): ?>
                    <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                        <?php echo $error; ?>
                    </div>
                    <?php endif; ?>
                    
                    <form id="appointmentForm" method="POST" action="">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="firstName">First Name *</label>
                                <input type="text" id="firstName" name="first_name" required>
                            </div>
                            <div class="form-group">
                                <label for="lastName">Last Name *</label>
                                <input type="text" id="lastName" name="last_name" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number *</label>
                                <input type="tel" id="phone" name="phone" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="department">Select Department *</label>
                                <select id="department" name="department" required onchange="loadDoctors(this.value)">
                                    <option value="">Choose Department</option>
                                    <?php foreach ($departments as $dept): ?>
                                    <option value="<?php echo $dept['id']; ?>">
                                        <?php echo htmlspecialchars($dept['name']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="doctor">Preferred Doctor</label>
                                <select id="doctor" name="doctor">
                                    <option value="">Select Doctor (Choose Department First)</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="date">Appointment Date *</label>
                                <input type="date" id="date" name="date" required>
                            </div>
                            <div class="form-group">
                                <label for="time">Preferred Time *</label>
                                <select id="time" name="time" required>
                                    <option value="">Select Time</option>
                                    <option value="morning">Morning (9 AM - 12 PM)</option>
                                    <option value="afternoon">Afternoon (2 PM - 5 PM)</option>
                                    <option value="evening">Evening (6 PM - 9 PM)</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Additional Information</label>
                            <textarea id="message" name="message" rows="4" placeholder="Any specific concerns or notes for the doctor..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary form-submit">
                            <i class="fas fa-paper-plane"></i> Book Appointment
                        </button>
                        
                        <p class="form-note">We'll confirm your appointment within 2 hours via email and SMS.</p>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <script>
        function loadDoctors(deptId) {
            if (!deptId) {
                document.getElementById('doctor').innerHTML = '<option value="">Select Doctor (Choose Department First)</option>';
                return;
            }
            
            fetch('appointment.php?dept_id=' + deptId)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('doctor').innerHTML = data;
                });
        }
        
        // Set min date to tomorrow
        const dateInput = document.getElementById('date');
        if (dateInput) {
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            dateInput.min = tomorrow.toISOString().split('T')[0];
            dateInput.value = dateInput.min;
        }
   // Appointment form submission
        const appointmentForm = document.getElementById('appointmentForm');
        if (appointmentForm) {
            appointmentForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get form data
                const formData = new FormData(this);
                const formObject = {};
                formData.forEach((value, key) => {
                    formObject[key] = value;
                });
                
                // Show success message
                alert('Thank you! Your appointment has been scheduled. We will contact you shortly to confirm.');
                
                // Reset form
                this.reset();
                
                // Reset date to tomorrow
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                const tomorrowFormatted = tomorrow.toISOString().split('T')[0];
                dateInput.value = tomorrowFormatted;
            });
        }

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