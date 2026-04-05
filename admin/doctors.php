<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
// doctors.php - Simplified version
require_once 'header.php';

global $conn;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_doctor'])) {
        // Add new doctor
        $name = $conn->real_escape_string($_POST['name']);
        $specialty = $conn->real_escape_string($_POST['specialty']);
        $department = $conn->real_escape_string($_POST['department']);
        $experience = $conn->real_escape_string($_POST['experience']);
        $qualifications = $conn->real_escape_string($_POST['qualifications'] ?? '');
        
        // Handle image upload
        $image_name = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $upload_dir = '../img/uploads/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $image_name = uniqid() . '_' . time() . '.' . $imageFileType;
            $target_file = $upload_dir . $image_name;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                // Image uploaded successfully
            } else {
                $image_name = null;
                $error = "Error uploading image.";
            }
        }
        
        $sql = "INSERT INTO doctors (name, specialty, department, image, experience, qualifications) 
                VALUES ('$name', '$specialty', '$department', '$image_name', '$experience', '$qualifications')";
        
        if ($conn->query($sql)) {
            $success = "Doctor added successfully!";
        } else {
            $error = "Error adding doctor: " . $conn->error;
        }
    }
    
    if (isset($_POST['update_doctor'])) {
        // Update doctor
        $id = (int)$_POST['id'];
        $name = $conn->real_escape_string($_POST['name']);
        $specialty = $conn->real_escape_string($_POST['specialty']);
        $department = $conn->real_escape_string($_POST['department']);
        $experience = $conn->real_escape_string($_POST['experience']);
        $qualifications = $conn->real_escape_string($_POST['qualifications'] ?? '');
        
        // Handle image upload
        $image_update = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $upload_dir = '../img/uploads/';
            $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $image_name = uniqid() . '_' . time() . '.' . $imageFileType;
            $target_file = $upload_dir . $image_name;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                // Delete old image if exists
                $old_image_result = $conn->query("SELECT image FROM doctors WHERE id = $id");
                if ($old_image_result->num_rows > 0) {
                    $old_image = $old_image_result->fetch_assoc()['image'];
                    if ($old_image && file_exists($upload_dir . $old_image)) {
                        unlink($upload_dir . $old_image);
                    }
                }
                
                $image_update = ", image = '$image_name'";
            }
        }
        
        $sql = "UPDATE doctors SET 
                name = '$name',
                specialty = '$specialty',
                department = '$department',
                experience = '$experience',
                qualifications = '$qualifications'
                $image_update
                WHERE id = $id";
        
        if ($conn->query($sql)) {
            $success = "Doctor updated successfully!";
        } else {
            $error = "Error updating doctor: " . $conn->error;
        }
    }
}

// Handle delete request
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // Get image name before deleting
    $result = $conn->query("SELECT image FROM doctors WHERE id = $id");
    if ($result->num_rows > 0) {
        $doctor = $result->fetch_assoc();
        if ($doctor['image']) {
            $image_path = '../img/uploads/' . $doctor['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
    }
    
    $conn->query("DELETE FROM doctors WHERE id = $id");
    $success = "Doctor deleted successfully!";
}

// Get all doctors
$doctors = [];
$doctors_result = $conn->query("SELECT * FROM doctors ORDER BY name ASC");
if ($doctors_result) {
    while ($row = $doctors_result->fetch_assoc()) {
        $doctors[] = $row;
    }
}

// Get departments for dropdown
$departments = [];
$dept_result = $conn->query("SELECT * FROM departments ORDER BY name ASC");
if ($dept_result) {
    while ($row = $dept_result->fetch_assoc()) {
        $departments[] = $row;
    }
}

// Get doctor for edit
$edit_doctor = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_result = $conn->query("SELECT * FROM doctors WHERE id = $edit_id LIMIT 1");
    if ($edit_result->num_rows > 0) {
        $edit_doctor = $edit_result->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors Management - LACH Admin</title>
    <style>
        /* Copy CSS from dashboard.php and add modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background: white;
            padding: 30px;
            border-radius: var(--radius);
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--gray-light);
        }
        
        .modal-header h2 {
            font-size: 1.5rem;
            color: var(--dark);
        }
        
        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--gray);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--gray-light);
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
        }
        
        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .btn-add {
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }
        
        .doctor-card {
            background: white;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            margin-bottom: 20px;
        }
        
        .doctor-header {
            padding: 20px;
            background: var(--light);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .doctor-info {
            padding: 20px;
        }
        
        .doctor-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 20px;
        }
        
        .doctor-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d1fae5;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }
        
        .alert-error {
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }
        
        /* CSS Variables */
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
            --radius: 16px;
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: var(--light);
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: 250px;
            background: white;
            box-shadow: var(--shadow);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: var(--transition);
            z-index: 100;
        }
        
        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid var(--gray-light);
        }
        
        .logo {
            display: flex;
            align-items: center;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
        }
        
        .logo i {
            color: #ff4757;
            margin-right: 10px;
            font-size: 1.8rem;
        }
        
        .sidebar-nav {
            padding: 20px 0;
        }
        
        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--dark);
            text-decoration: none;
            transition: var(--transition);
            border-left: 4px solid transparent;
        }
        
        .nav-item:hover, .nav-item.active {
            background: var(--primary-light);
            color: var(--primary);
            border-left-color: var(--primary);
        }
        
        .nav-item i {
            width: 24px;
            margin-right: 12px;
            font-size: 1.2rem;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
        }
        
        .top-bar {
            background: white;
            padding: 20px;
            border-radius: var(--radius);
            margin-bottom: 30px;
            box-shadow: var(--shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .welcome-text h1 {
            font-size: 1.8rem;
            margin-bottom: 5px;
            color: var(--dark);
        }
        
        .welcome-text p {
            color: var(--gray);
            font-size: 0.95rem;
        }
        
        .logout-btn {
            background: var(--accent);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
        }
        
        .logout-btn:hover {
            background: #ff6b4a;
            transform: translateY(-2px);
        }
        
        .mobile-menu-btn {
            display: none;
            font-size: 1.5rem;
            color: var(--dark);
            background: none;
            border: none;
            cursor: pointer;
        }
        
        .action-btns {
            display: flex;
            gap: 8px;
        }
        
        .btn {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-view {
            background: var(--primary);
            color: white;
        }
        
        .btn-decline {
            background: var(--accent);
            color: white;
        }
        
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .mobile-menu-btn {
                display: block;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-heartbeat"></i>
                LACH Admin
            </div>
        </div>
        
        <div class="sidebar-nav">
            <a href="dashboard.php" class="nav-item">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="doctors.php" class="nav-item active">
                <i class="fas fa-user-md"></i> Doctors
            </a>
            <a href="appointments.php" class="nav-item">
                <i class="fas fa-calendar-check"></i> Appointments
                <?php if ($pending_count > 0): ?>
                <span style="background: var(--accent); color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.8rem; margin-left: auto;">
                    <?php echo $pending_count; ?>
                </span>
                <?php endif; ?>
            </a>
            <a href="messages.php" class="nav-item">
                <i class="fas fa-envelope"></i> Messages
                <?php if ($unread_count > 0): ?>
                <span style="background: var(--accent); color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.8rem; margin-left: auto;">
                    <?php echo $unread_count; ?>
                </span>
                <?php endif; ?>
            </a>
            <a href="logout.php" class="nav-item">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="top-bar">
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
            
            <div class="welcome-text">
                <h1>Doctors Management</h1>
                <p>Add, edit, or remove doctors</p>
            </div>
            
            <button class="logout-btn" onclick="window.location.href='logout.php'">
                 Logout
            </button>
        </div>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <button class="btn-add" onclick="openModal('addDoctorModal')">
            <i class="fas fa-plus"></i> Add New Doctor
        </button>
        
        <div style="background: white; padding: 25px; border-radius: var(--radius); box-shadow: var(--shadow);">
            <h2 style="margin-bottom: 20px;">All Doctors (<?php echo count($doctors); ?>)</h2>
            
            <div class="doctors-list">
                <?php if (empty($doctors)): ?>
                    <p style="text-align: center; color: var(--gray); padding: 40px;">
                        No doctors found. Add your first doctor!
                    </p>
                <?php else: ?>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                        <?php foreach ($doctors as $doctor): ?>
                        <div class="doctor-card">
                            <div class="doctor-header">
                                <h3 style="margin: 0;"><?php echo htmlspecialchars($doctor['name']); ?></h3>
                                <div class="action-btns">
                                    <a href="?edit=<?php echo $doctor['id']; ?>" class="btn btn-view">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?delete=<?php echo $doctor['id']; ?>" 
                                       class="btn btn-decline"
                                       onclick="return confirm('Are you sure you want to delete this doctor?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="doctor-info">
                                <?php if ($doctor['image']): ?>
                                <div class="doctor-image">
                                    <img src="../img/uploads/<?php echo htmlspecialchars($doctor['image']); ?>" 
                                         alt="<?php echo htmlspecialchars($doctor['name']); ?>">
                                </div>
                                <?php endif; ?>
                                
                                <p><strong>Specialty:</strong> <?php echo htmlspecialchars($doctor['specialty']); ?></p>
                                <p><strong>Department:</strong> <?php echo htmlspecialchars($doctor['department']); ?></p>
                                <p><strong>Experience:</strong> <?php echo nl2br(htmlspecialchars($doctor['experience'])); ?></p>
                                <?php if ($doctor['qualifications']): ?>
                                <p><strong>Qualifications:</strong> <?php echo nl2br(htmlspecialchars($doctor['qualifications'])); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Add/Edit Doctor Modal -->
    <div class="modal" id="addDoctorModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><?php echo $edit_doctor ? 'Edit Doctor' : 'Add New Doctor'; ?></h2>
                <button class="close-modal" onclick="closeModal('addDoctorModal')">&times;</button>
            </div>
            
            <form method="POST" action="" enctype="multipart/form-data">
                <?php if ($edit_doctor): ?>
                <input type="hidden" name="id" value="<?php echo $edit_doctor['id']; ?>">
                <input type="hidden" name="update_doctor" value="1">
                <?php else: ?>
                <input type="hidden" name="add_doctor" value="1">
                <?php endif; ?>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" 
                               value="<?php echo $edit_doctor['name'] ?? ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="specialty">Specialty *</label>
                        <input type="text" id="specialty" name="specialty" 
                               value="<?php echo $edit_doctor['specialty'] ?? ''; ?>" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="department">Department *</label>
                        <select id="department" name="department" required>
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $dept): ?>
                            <option value="<?php echo htmlspecialchars($dept['name']); ?>"
                                <?php echo ($edit_doctor['department'] ?? '') === $dept['name'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($dept['name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="image">Profile Photo</label>
                        <input type="file" id="image" name="image" accept="image/*">
                        <?php if ($edit_doctor && $edit_doctor['image']): ?>
                        <small>Current: <?php echo $edit_doctor['image']; ?></small>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="experience">Experience (3 lines) *</label>
                    <textarea id="experience" name="experience" required><?php echo $edit_doctor['experience'] ?? ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="qualifications">Qualifications</label>
                    <textarea id="qualifications" name="qualifications"><?php echo $edit_doctor['qualifications'] ?? ''; ?></textarea>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn-add">
                        <i class="fas fa-save"></i> 
                        <?php echo $edit_doctor ? 'Update Doctor' : 'Add Doctor'; ?>
                    </button>
                    <button type="button" style="height: 35px; background-color:#ff6b4a" class=" btn btn-decline" onclick="closeModal('addDoctorModal')">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
        
        // Open modal if editing
        <?php if (isset($_GET['edit'])): ?>
        window.onload = function() {
            openModal('addDoctorModal');
        };
        <?php endif; ?>
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        };
        
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.getElementById('sidebar');
        
        mobileMenuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });
    </script>
</body>
</html>