<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
// admin/header.php - Common header for all admin pages
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Database connection
require_once '../includes/config.php';

// Get common counts for sidebar
$pending_count = $conn->query("SELECT COUNT(*) as count FROM appointments WHERE status = 'pending'")->fetch_assoc()['count'] ?? 0;
$unread_count = $conn->query("SELECT COUNT(*) as count FROM messages WHERE status = 'unread'")->fetch_assoc()['count'] ?? 0;

?>