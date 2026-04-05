<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
// includes/functions.php
require_once 'config.php';

// Get all doctors
function getDoctors() {
    global $conn;
    $sql = "SELECT * FROM doctors ORDER BY name ASC";
    $result = $conn->query($sql);
    $doctors = [];
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $doctors[] = $row;
        }
    }
    
    return $doctors;
}

// Get doctor by ID
function getDoctorById($id) {
    global $conn;
    $id = (int)$id;
    $sql = "SELECT * FROM doctors WHERE id = $id LIMIT 1";
    $result = $conn->query($sql);
    return $result ? $result->fetch_assoc() : null;
}

// Get all departments
function getDepartments() {
    global $conn;
    $sql = "SELECT * FROM departments ORDER BY name ASC";
    $result = $conn->query($sql);
    $departments = [];
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $departments[] = $row;
        }
    }
    
    return $departments;
}

// Get doctors by department
function getDoctorsByDepartment($department_id) {
    global $conn;
    $dept_id = (int)$department_id;
    $sql = "SELECT * FROM doctors WHERE department = (SELECT name FROM departments WHERE id = $dept_id)";
    $result = $conn->query($sql);
    $doctors = [];
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $doctors[] = $row;
        }
    }
    
    return $doctors;
}



// Get total appointments count
function getTotalAppointmentsCount() {
    global $conn;
    $sql = "SELECT COUNT(*) as count FROM appointments";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['count'];
    }
    return 0;
}

// Get approved appointments count
function getApprovedAppointmentsCount() {
    global $conn;
    $sql = "SELECT COUNT(*) as count FROM appointments WHERE status = 'approved'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['count'];
    }
    return 0;
}

// Get declined appointments count
function getDeclinedAppointmentsCount() {
    global $conn;
    $sql = "SELECT COUNT(*) as count FROM appointments WHERE status = 'declined'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['count'];
    }
    return 0;
}

// Get total messages count
function getTotalMessagesCount() {
    global $conn;
    $sql = "SELECT COUNT(*) as count FROM messages";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['count'];
    }
    return 0;
}

// Get read messages count
function getReadMessagesCount() {
    global $conn;
    $sql = "SELECT COUNT(*) as count FROM messages WHERE status = 'read'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['count'];
    }
    return 0;
}

// Upload image function
function uploadImage($file, $target_dir = UPLOAD_PATH) {
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $target_file = $target_dir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Generate unique filename
    $new_filename = uniqid() . '_' . time() . '.' . $imageFileType;
    $target_file = $target_dir . $new_filename;
    
    // Check if image file is actual image
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        return ['success' => false, 'message' => 'File is not an image.'];
    }
    
    // Check file size (5MB max)
    if ($file["size"] > 5000000) {
        return ['success' => false, 'message' => 'File is too large.'];
    }
    
    // Allow certain file formats
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        return ['success' => false, 'message' => 'Only JPG, JPEG, PNG & GIF files are allowed.'];
    }
    
    // Upload file
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return ['success' => true, 'filename' => $new_filename];
    } else {
        return ['success' => false, 'message' => 'Error uploading file.'];
    }
}

// Delete image function
function deleteImage($filename) {
    $file_path = UPLOAD_PATH . $filename;
    if (file_exists($file_path) && is_file($file_path)) {
        return unlink($file_path);
    }
    return false;
}
// Add these functions to includes/functions.php

// Get pending appointments count
function getPendingAppointmentsCount() {
    global $conn;
    $sql = "SELECT COUNT(*) as count FROM appointments WHERE status = 'pending'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['count'];
    }
    return 0;
}

// Get unread messages count
function getUnreadMessagesCount() {
    global $conn;
    $sql = "SELECT COUNT(*) as count FROM messages WHERE status = 'unread'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['count'];
    }
    return 0;
}

// Get all appointments
function getAllAppointments($filter = 'all') {
    global $conn;
    
    $where = '';
    if ($filter == 'pending') {
        $where = "WHERE a.status = 'pending'";
    } elseif ($filter == 'approved') {
        $where = "WHERE a.status = 'approved'";
    } elseif ($filter == 'declined') {
        $where = "WHERE a.status = 'declined'";
    }
    
    $sql = "SELECT a.*, d.name as doctor_name, dept.name as department_name 
            FROM appointments a 
            LEFT JOIN doctors d ON a.doctor_id = d.id 
            LEFT JOIN departments dept ON a.department_id = dept.id 
            $where 
            ORDER BY a.created_at DESC";
    
    $result = $conn->query($sql);
    $appointments = [];
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $appointments[] = $row;
        }
    }
    
    return $appointments;
}
?>