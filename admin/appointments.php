<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include required files
require_once '../includes/config.php';
require_once '../includes/auth.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Include functions if available
$functions_file = __DIR__ . '/../includes/functions.php';
if (file_exists($functions_file)) {
    require_once $functions_file;
} else {
    // Define functions inline if functions.php doesn't exist
    function getPendingAppointmentsCount()
    {
        global $conn;
        $sql = "SELECT COUNT(*) as count FROM appointments WHERE status = 'pending'";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['count'];
        }
        return 0;
    }

    function getUnreadMessagesCount()
    {
        global $conn;
        $sql = "SELECT COUNT(*) as count FROM messages WHERE status = 'unread'";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['count'];
        }
        return 0;
    }
}

// Handle approve/decline
if (isset($_GET['approve'])) {
    $id = (int)$_GET['approve'];
    $conn->query("UPDATE appointments SET status = 'approved' WHERE id = $id");
    header('Location: appointments.php?success=Appointment+approved+successfully');
    exit();
}

if (isset($_GET['decline'])) {
    $id = (int)$_GET['decline'];
    $conn->query("UPDATE appointments SET status = 'declined' WHERE id = $id");
    header('Location: appointments.php?success=Appointment+declined+successfully');
    exit();
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM appointments WHERE id = $id");
    header('Location: appointments.php?success=Appointment+deleted+successfully');
    exit();
}

// Get success message if any
$success = isset($_GET['success']) ? urldecode($_GET['success']) : '';

// Get filter
$filter = $_GET['filter'] ?? 'all';

// Build query - FIXED: Ensure proper LEFT JOIN with correct column names
$where = '';
if ($filter == 'pending') {
    $where = "WHERE a.status = 'pending'";
} elseif ($filter == 'approved') {
    $where = "WHERE a.status = 'approved'";
} elseif ($filter == 'declined') {
    $where = "WHERE a.status = 'declined'";
}

// Get appointments - FIXED QUERY
$sql = "SELECT a.*, 
               d.name as doctor_name, 
               dept.name as department_name 
        FROM appointments a 
        LEFT JOIN doctors d ON a.doctor_id = d.id 
        LEFT JOIN departments dept ON a.department_id = dept.id 
        $where 
        ORDER BY a.created_at DESC";
$result = $conn->query($sql);
$appointments = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

// Get counts
$pending_count = getPendingAppointmentsCount();
$total_count = $conn->query("SELECT COUNT(*) as count FROM appointments")->fetch_assoc()['count'] ?? 0;
$approved_count = $conn->query("SELECT COUNT(*) as count FROM appointments WHERE status = 'approved'")->fetch_assoc()['count'] ?? 0;
$declined_count = $conn->query("SELECT COUNT(*) as count FROM appointments WHERE status = 'declined'")->fetch_assoc()['count'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments - LACH Admin</title>
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

        .nav-item:hover,
        .nav-item.active {
            background: var(--primary-light);
            color: var(--primary);
            border-left-color: var(--primary);
        }

        .nav-item i {
            width: 24px;
            margin-right: 12px;
            font-size: 1.2rem;
        }

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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .stat-icon.doctors {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stat-icon.appointments {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .stat-icon.messages {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .stat-icon.pending {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }

        .stat-info h3 {
            font-size: 1.8rem;
            margin-bottom: 5px;
            color: var(--dark);
        }

        .stat-info p {
            color: var(--gray);
            font-size: 0.9rem;
        }

        .content-section {
            background: white;
            padding: 25px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 30px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--gray-light);
        }

        .section-header h2 {
            font-size: 1.5rem;
            color: var(--dark);
        }

        .view-all {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .view-all:hover {
            text-decoration: underline;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 12px 15px;
            background: var(--light);
            color: var(--dark);
            font-weight: 600;
            border-bottom: 2px solid var(--gray-light);
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--gray-light);
            color: var(--gray);
        }

        tr:hover {
            background: var(--light);
        }

        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-approved {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-declined {
            background: #fee2e2;
            color: #991b1b;
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

        .btn-approve {
            background: var(--secondary);
            color: white;
        }

        .btn-decline {
            background: var(--accent);
            color: white;
        }

        .btn-view {
            background: var(--primary);
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
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

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .top-bar {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
        }

        .filter-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-tab {
            padding: 10px 20px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            text-decoration: none;
            color: #1e293b;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .filter-tab:hover,
        .filter-tab.active {
            background: #1a76d1;
            color: white;
            border-color: #1a76d1;
        }

        /* Reuse styles from dashboard */
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

        .nav-item:hover,
        .nav-item.active {
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

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }

        .alert-success {
            background: #d1fae5;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }

        .content-section {
            background: white;
            padding: 25px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 30px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--gray-light);
        }

        .section-header h2 {
            font-size: 1.5rem;
            color: var(--dark);
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 12px 15px;
            background: var(--light);
            color: var(--dark);
            font-weight: 600;
            border-bottom: 2px solid var(--gray-light);
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--gray-light);
            color: var(--gray);
        }

        tr:hover {
            background: var(--light);
        }

        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-approved {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-declined {
            background: #fee2e2;
            color: #991b1b;
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

        .btn-approve {
            background: var(--secondary);
            color: white;
        }

        .btn-decline {
            background: var(--accent);
            color: white;
        }

        .btn-view {
            background: var(--primary);
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
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

        @media (max-width: 768px) {
            .top-bar {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .filter-tabs {
                flex-direction: column;
            }

            .action-btns {
                flex-direction: column;
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
            <a href="doctors.php" class="nav-item">
                <i class="fas fa-user-md"></i> Doctors
            </a>
            <a href="appointments.php" class="nav-item active">
                <i class="fas fa-calendar-check"></i> Appointments
                <?php if ($pending_count > 0): ?>
                    <span style="background: var(--accent); color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.8rem; margin-left: auto;">
                        <?php echo $pending_count; ?>
                    </span>
                <?php endif; ?>
            </a>
            <a href="messages.php" class="nav-item">
                <i class="fas fa-envelope"></i> Messages
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
                <h1>Appointment Management</h1>
                <p>View and manage all patient appointments</p>
            </div>

            <button class="logout-btn" onclick="window.location.href='logout.php'">
                Logout
            </button>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <div class="filter-tabs">
            <a href="?filter=all" class="filter-tab <?php echo $filter == 'all' ? 'active' : ''; ?>">
                All (<?php echo $total_count; ?>)
            </a>
            <a href="?filter=pending" class="filter-tab <?php echo $filter == 'pending' ? 'active' : ''; ?>">
                Pending (<?php echo $pending_count; ?>)
            </a>
            <a href="?filter=approved" class="filter-tab <?php echo $filter == 'approved' ? 'active' : ''; ?>">
                Approved (<?php echo $approved_count; ?>)
            </a>
            <a href="?filter=declined" class="filter-tab <?php echo $filter == 'declined' ? 'active' : ''; ?>">
                Declined (<?php echo $declined_count; ?>)
            </a>
        </div>

        <div class="content-section">
            <div class="section-header">
                <h2>Appointments List</h2>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Patient Name</th>
                            <th>Contact</th>
                            <th>Department</th>
                            <th>Doctor</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th>Submitted On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($appointments)): ?>
                            <tr>
                                <td colspan="9" style="text-align: center; padding: 40px; color: var(--gray);">
                                    No appointments found.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($appointments as $appointment): ?>
                                <tr>
                                    <td>#<?php echo $appointment['id']; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($appointment['first_name'] . ' ' . $appointment['last_name']); ?></strong>
                                    </td>
                                    <td>
                                        <div><?php echo htmlspecialchars($appointment['email']); ?></div>
                                        <small><?php echo htmlspecialchars($appointment['phone']); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($appointment['department_name'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($appointment['doctor_name'] ?? 'Any Doctor'); ?></td>
                                    <td>
                                        <div><?php echo date('M d, Y', strtotime($appointment['appointment_date'])); ?></div>
                                        <small><?php echo htmlspecialchars(ucfirst($appointment['appointment_time'])); ?></small>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo $appointment['status']; ?>">
                                            <?php echo ucfirst($appointment['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php echo date('M d, Y', strtotime($appointment['created_at'])); ?>
                                    </td>
                                    <td>
                                        <div class="action-btns">
                                            <?php if ($appointment['status'] === 'pending'): ?>
                                                <a href="?approve=<?php echo $appointment['id']; ?>"
                                                    class="btn btn-approve"
                                                    onclick="return confirm('Approve this appointment?')">
                                                    <i class="fas fa-check"></i> Approve
                                                </a>
                                                <a href="?decline=<?php echo $appointment['id']; ?>"
                                                    class="btn btn-decline"
                                                    onclick="return confirm('Decline this appointment?')">
                                                    <i class="fas fa-times"></i> Decline
                                                </a>
                                            <?php endif; ?>
                                            <a href="?delete=<?php echo $appointment['id']; ?>"
                                                class="btn btn-decline"
                                                onclick="return confirm('Delete this appointment permanently?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.getElementById('sidebar');

        mobileMenuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 992) {
                if (!sidebar.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });
    </script>
</body>

</html>