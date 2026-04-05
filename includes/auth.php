<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
require_once 'config.php';

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . ADMIN_URL . 'login.php');
        exit();
    }
}

// Redirect if already logged in
function requireLogout() {
    if (isLoggedIn()) {
        header('Location: ' . ADMIN_URL . 'dashboard.php');
        exit();
    }
}

// SIMPLE LOGIN FUNCTION - PLAIN TEXT PASSWORDS
function adminLogin($username, $password) {
    global $conn;
    
    // Sanitize input
    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);
    
    // Query to find admin by username OR email
    $sql = "SELECT * FROM admins WHERE (username = '$username' OR email = '$username') LIMIT 1";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows == 1) {
        $admin = $result->fetch_assoc();
        
        // SIMPLE COMPARISON - No password_hash for now
        if ($password === $admin['password']) {
            // Set session variables
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['login_time'] = time();
            
            return true;
        }
    }
    
    return false;
}

// Admin logout function
function adminLogout() {
    // Clear all session variables
    $_SESSION = array();
    
    // Destroy the session
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    session_destroy();
    header('Location: ' . ADMIN_URL . 'login.php');
    exit();
}
?>