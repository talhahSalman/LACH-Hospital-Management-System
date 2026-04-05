<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'hospital_db');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");

// Site configuration
define('SITE_NAME', 'Lahore Advanced Care Hospital');
define('SITE_URL', 'http://localhost/hospital/');
define('ADMIN_URL', 'http://localhost/hospital/admin/');
define('UPLOAD_PATH', $_SERVER['DOCUMENT_ROOT'] . '/hospital/img/uploads/');
define('UPLOAD_URL', 'img/uploads/');
?>