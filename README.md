# 🏥 Lahore Advanced Care Hospital (LACH) - Hospital Management System

A comprehensive web-based Hospital Management System built with PHP, MySQL, HTML, CSS, and JavaScript. This system streamlines hospital operations including appointment booking, doctor management, patient messaging, and administrative controls.

## 📋 Table of Contents
- [Features](#features)
- [Technology Stack](#technology-stack)
- [System Requirements](#system-requirements)
- [Installation Guide](#installation-guide)
- [Database Setup](#database-setup)
- [Admin Login Credentials](#admin-login-credentials)
- [Project Structure](#project-structure)
- [Screenshots](#screenshots)
- [Future Enhancements](#future-enhancements)
- [License](#license)

## ✨ Features

### Frontend (Patient Side)
- **Homepage** - Hospital introduction, services overview, and featured doctors
- **About Us** - Hospital history, mission, vision, and statistics
- **Services** - Detailed medical services information
- **Pricing** - Medical packages and treatment costs
- **Our Team** - Display of all doctors with their specialties and qualifications
- **Appointment Booking** - Online appointment scheduling with department and doctor selection
- **Contact Form** - Patient inquiries and message submission
- **Donation Page** - Bank details and QR code for hospital donations

### Admin Panel
- **Secure Login System** - Authentication for admin access
- **Dashboard** - Overview of total doctors, appointments, messages, and pending requests
- **Doctor Management** - Add, edit, update, and delete doctor profiles with image upload
- **Appointment Management** - View, approve, decline, and delete patient appointments
- **Message Management** - Read, mark as read, and delete contact form messages
- **Logout Functionality** - Secure session termination

### Database Features
- Doctors table with profile images
- Appointments table with status tracking (pending/approved/declined)
- Messages table with read/unread status
- Departments table for service categorization
- Admins table for authentication

## 🛠 Technology Stack

| Technology | Purpose |
|------------|---------|
| **PHP** | Backend logic and server-side processing |
| **MySQL** | Database management and data storage |
| **HTML5** | Website structure and content |
| **CSS3** | Styling, animations, and responsive design |
| **JavaScript** | Interactive elements and AJAX requests |
| **XAMPP** | Local development environment |
| **Font Awesome** | Icons and visual elements |
| **Google Fonts** | Typography (Inter & Poppins fonts) |

## 💻 System Requirements

- **Operating System**: Windows / macOS / Linux
- **Web Server**: Apache (via XAMPP/WAMP/MAMP)
- **PHP Version**: 7.4 or higher (8.x recommended)
- **MySQL Version**: 5.7 or higher
- **Web Browser**: Chrome, Firefox, Safari, Edge (latest versions)

## 🔧 Installation Guide

### Step 1: Install XAMPP
1. Download XAMPP from [Apache Friends](https://www.apachefriends.org/)
2. Install XAMPP on your computer
3. Launch XAMPP Control Panel
4. Start **Apache** and **MySQL** services

### Step 2: Set Up the Project
1. Navigate to XAMPP's htdocs folder:
   - **Windows**: `C:\xampp\htdocs\`
   - **Mac**: `/Applications/XAMPP/htdocs/`
2. Create a new folder named `hospital`
3. Copy all project files into `C:\xampp\htdocs\hospital\`

### Step 3: Import Database
1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Click on **New** to create a database
3. Name the database: `hospital_db`
4. Click **Create**
5. Click on the **Import** tab
6. Click **Choose File** and select the `hospital_db.sql` file (provided in the project)
7. Click **Import** at the bottom

### Step 4: Configure Database Connection
1. Open `includes/config.php`
2. Verify these settings (default XAMPP settings):
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'hospital_db');
   ```
### Step 5: Run the Application
1. Open your browser
2. Navigate to: http://localhost/hospital/
3. The hospital website should now be visible
### Step 6: Access Admin Panel
1. Go to: http://localhost/hospital/admin/login.php
2. Use the credentials provided below
## 🔐 Admin Login Credentials
| Field	 | Value|
|------------|---------|
| **Username** |admin|
| **Email** | admin@lach.com|
| **Password** | admin123 |
#### ⚠️ Security Note: Change the default password immediately after first login in a production environment.
## 📁 Project Structure
```
hospital/
│
├── admin/                       Admin Panel Directory
│   ├── appointments.php         Manage patient appointments
│   ├── dashboard.php            Admin dashboard with statistics
│   ├── doctors.php              CRUD operations for doctors
│   ├── header.php               Common admin header
│   ├── login.php                Admin authentication page
│   ├── logout.php              # Session termination
│   └── messages.php            # View contact messages
│
├── includes/                   # Core PHP Files
│   ├── auth.php                # Authentication functions
│   ├── config.php              # Database configuration
│   └── functions.php           # Reusable helper functions
│
├── img/                        # Images Directory
│   ├── uploads/                # Doctor profile photos
│   ├── about.jpg               # About page image
│   ├── hero.png                # Homepage hero image
│   ├── price-1.jpg             # Pricing package image
│   ├── price-2.jpg             # Pricing package image
│   ├── price-3.jpg             # Pricing package image
│   ├── price-4.jpg             # Pricing package image
│   ├── qr.jpg                  # Donation QR code
│   ├── team-default.jpg        # Default doctor image
│   └── usama 2.jpg             # Testimonial image
│
├── about.php                   # Hospital information page
├── appointment.php             # Online booking form
├── contact.php                 # Contact form page
├── donate.php                  # Donation information
├── huh.php                     # Additional appointment page
├── index.php                   # Homepage
├── price.php                   # Pricing packages
├── service.php                 # Medical services
└── team.php                    # Doctors team page
```
## 📊 Database Schema
### Table: admins
| Column | Type | Description |
|------------|---------|-------|	
| id	| INT(11)	| Primary Key |
|username	| VARCHAR(50)	|Admin username |
|email	| VARCHAR(100)	|Admin email |
|password	| VARCHAR(255)	|Admin password |
### Table: doctors
|Column	| Type | Description |
|------------|---------|-------|	
|id	| INT(11)	| Primary Key |
|name	| VARCHAR(100)	| Doctor's full name |
|specialty	| VARCHAR(100)	| Medical specialty |
|department	| VARCHAR(100)	| Department name |
|image	| VARCHAR(255)	| Profile image path |
|experience	| TEXT	| Experience details |
|qualifications	| TEXT	| Educational qualifications |
### Table: appointments
|Column	| Type	| Description |
|------------|---------|-------|	
|id	| INT(11)	| Primary Key
|first_name	| VARCHAR(50)	| Patient first name |
|last_name	| VARCHAR(50)	| Patient last name |
|email	| VARCHAR(100)	| Patient email |
|phone	| VARCHAR(20)	| Contact number |
|department_id	| INT(11)	| Foreign key to departments |
|doctor_id	| INT(11)	| Foreign key to doctors |
|appointment_date	| DATE	| Scheduled date |
|appointment_time	| VARCHAR(20)	| Time slot |
|message	| TEXT	| Additional notes |
|status	| ENUM	| pending/approved/declined |
|created_at	| TIMESTAMP	| Submission time |
### Table: messages
|Column	| Type	| Description |
|------------|---------|-------|	
|id	| INT(11)	| Primary Key |
|name	| VARCHAR(100)	| Sender name |
|email	| VARCHAR(100)	| Sender email |
|phone	| VARCHAR(20)	| Contact number |
|inquiry_type	| VARCHAR(50)	| Type of inquiry |
|message	| TEXT	| Message content |
|status	| ENUM	| unread/read |
|created_at	| TIMESTAMP	| Submission time |
### Table: departments
|Column	| Type	| Description | 
|------------|---------|-------|	
|id	| INT(11)	| Primary Key |
|name	| VARCHAR(100)	| Department name |

## 📸 Screenshots
### Homepage
- Hero section with call-to-action buttons

<img width="1887" height="912" alt="image" src="https://github.com/user-attachments/assets/2b6f99dc-a21b-49ef-8aa3-c70c2fa9fa42" />


 
- About section with hospital features


<img width="1894" height="919" alt="image" src="https://github.com/user-attachments/assets/bec58896-02b0-4f55-b6b2-4536ba70ee3e" />


- Services grid display


<img width="1903" height="922" alt="image" src="https://github.com/user-attachments/assets/7a70b26b-d4c3-4989-9782-274271a743fb" />


- Doctor profiles showcase


<img width="1890" height="915" alt="image" src="https://github.com/user-attachments/assets/c214fa63-c402-49d7-822d-b471d0f12ffc" />

- Pricing packages


<img width="1895" height="798" alt="image" src="https://github.com/user-attachments/assets/d2579ab8-b131-48ed-8437-186ed0ed5d99" />

- Contact us

<img width="1898" height="921" alt="image" src="https://github.com/user-attachments/assets/6242ab61-f6ff-4ef7-b05d-5e794f8668ca" />

<img width="1899" height="917" alt="image" src="https://github.com/user-attachments/assets/bec6a560-adcc-4fad-97b0-c382a422f00d" />

<img width="1898" height="920" alt="image" src="https://github.com/user-attachments/assets/53b67371-488f-426e-8618-f122b1e58042" />

- Donate


<img width="1715" height="903" alt="image" src="https://github.com/user-attachments/assets/612044f9-f1c9-4715-959e-002c20b72bc1" />

<img width="1866" height="898" alt="image" src="https://github.com/user-attachments/assets/82683e71-d14e-4d96-ac49-4104e1a44631" />

### Admin Dashboard
- Login

<img width="1703" height="854" alt="image" src="https://github.com/user-attachments/assets/974883a2-2509-4d3f-9c25-534798a17e95" />


- Dashboard

<img width="1887" height="894" alt="image" src="https://github.com/user-attachments/assets/c26c4a0b-72fa-4149-bd9e-205aff83393d" />


- Appointment Management

<img width="1891" height="892" alt="image" src="https://github.com/user-attachments/assets/20774cbd-b309-48a1-9c8d-2c52a49059ff" />


- Doctors


<img width="1871" height="857" alt="image" src="https://github.com/user-attachments/assets/002040c5-516c-4aec-873f-9c095ffe1624" />

- Patient contact information display

<img width="1871" height="894" alt="image" src="https://github.com/user-attachments/assets/0a79877f-7537-4799-9819-5bc891cdf752" />


## 🚀 Future Enhancements
- Online payment integration for appointments
- Email notification system for appointment confirmation
- Prescription management module
- Patient portal for viewing medical history
- Invoice and billing system
- Two-factor authentication for admin
- Multi-language support
- Mobile responsive improvements
- Appointment reminders via SMS
- Doctor availability calendar
- Patient feedback and rating system
- Medical record upload functionality

## 🤝 Contributing
- Contributions are welcome! Please follow these steps:
- Fork the repository
- Create a feature branch ```(git checkout -b feature/AmazingFeature)```
- Commit your changes ```(git commit -m 'Add some AmazingFeature')```
- Push to the branch ```(git push origin feature/AmazingFeature)```
- Open a Pull Request

## 📧 Contact
 For any inquiries or support:

- **Hospital Name:** Lahore Advanced Care Hospital (LACH)
- **Location:** 126-A MM Alam Road, Lahore, Pakistan
- **Email:** info@lach.com
- **Phone:** +92 324 567 890

## 📄 License
This project is open-source and available under the MIT License.	

## ⭐ Show Your Support
If you found this project helpful, please give it a star on GitHub!
Developed with ❤️ for better healthcare management
