<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
// admin/messages.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../includes/config.php';
require_once '../includes/auth.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Define functions inline
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

// Handle mark as read
if (isset($_GET['read'])) {
    $id = (int)$_GET['read'];
    $conn->query("UPDATE messages SET status = 'read' WHERE id = $id");
    header('Location: messages.php?success=Message+marked+as+read');
    exit();
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM messages WHERE id = $id");
    header('Location: messages.php?success=Message+deleted+successfully');
    exit();
}

// Get success message if any
$success = isset($_GET['success']) ? urldecode($_GET['success']) : '';

// Get filter
$filter = $_GET['filter'] ?? 'all';

// Build query
$where = '';
if ($filter == 'unread') {
    $where = "WHERE status = 'unread'";
} elseif ($filter == 'read') {
    $where = "WHERE status = 'read'";
}

$sql = "SELECT * FROM messages $where ORDER BY created_at DESC";
$result = $conn->query($sql);
$messages = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

$unread_count = getUnreadMessagesCount();
$total_count = $conn->query("SELECT COUNT(*) as count FROM messages")->fetch_assoc()['count'] ?? 0;
$read_count = $conn->query("SELECT COUNT(*) as count FROM messages WHERE status = 'read'")->fetch_assoc()['count'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - LACH Admin</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
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
        
        .filter-tab:hover, .filter-tab.active {
            background: #1a76d1;
            color: white;
            border-color: #1a76d1;
        }
        
        .appointment-details {
            background: #f8fafc;
            padding: 20px;
            border-radius: 16px;
            margin-bottom: 20px;
        }
        
        .detail-row {
            display: grid;
            grid-template-columns: 150px 1fr;
            margin-bottom: 10px;
        }
        
        .detail-label {
            font-weight: 600;
            color: #1e293b;
        }
        
        .detail-value {
            color: #64748b;
        }
        
        .message-box {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #1a76d1;
            margin-top: 15px;
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
        
        .alert-error {
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #991b1b;
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
        
        .message-card {
            background: white;
            border-radius: var(--radius);
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: var(--shadow-light);
            border-left: 4px solid var(--primary);
            transition: var(--transition);
        }
        
        .message-card.unread {
            border-left-color: var(--accent);
            background: #fff9f9;
        }
        
        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--gray-light);
        }
        
        .message-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .message-sender {
            font-weight: 600;
            color: var(--dark);
        }
        
        .message-email {
            color: var(--primary);
            font-size: 0.9rem;
        }
        
        .message-date {
            color: var(--gray);
            font-size: 0.85rem;
        }
        
        .message-type {
            background: var(--primary-light);
            color: var(--primary);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .message-content {
            color: var(--dark);
            line-height: 1.6;
            margin-bottom: 15px;
        }
        
        .message-actions {
            display: flex;
            gap: 10px;
        }
        
        .btn-mark-read {
            background: var(--secondary);
            color: white;
        }
        
        .no-messages {
            text-align: center;
            padding: 60px 20px;
            color: var(--gray);
        }
    </style>
</head>
<body>
    <!-- Sidebar (same as appointments.php) -->
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
            <a href="appointments.php" class="nav-item">
                <i class="fas fa-calendar-check"></i> Appointments
            </a>
            <a href="messages.php" class="nav-item active">
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
                <h1>Contact Messages</h1>
                <p>View and manage patient inquiries</p>
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
            <a href="?filter=unread" class="filter-tab <?php echo $filter == 'unread' ? 'active' : ''; ?>">
                Unread (<?php echo $unread_count; ?>)
            </a>
            <a href="?filter=read" class="filter-tab <?php echo $filter == 'read' ? 'active' : ''; ?>">
                Read (<?php echo $read_count; ?>)
            </a>
        </div>
        
        <div class="content-section">
            <div class="section-header">
                <h2>Messages List</h2>
            </div>
            
            <?php if (empty($messages)): ?>
            <div class="no-messages">
                <i class="fas fa-envelope-open" style="font-size: 3rem; color: var(--gray-light); margin-bottom: 20px;"></i>
                <h3>No messages found</h3>
                <p>All messages have been read and processed.</p>
            </div>
            <?php else: ?>
                <?php foreach ($messages as $message): ?>
                <div class="message-card <?php echo $message['status'] == 'unread' ? 'unread' : ''; ?>">
                    <div class="message-header">
                        <div class="message-info">
                            <div class="message-sender"><?php echo htmlspecialchars($message['name']); ?></div>
                            <div class="message-email"><?php echo htmlspecialchars($message['email']); ?></div>
                            <?php if ($message['phone']): ?>
                            <div class="message-phone"><?php echo htmlspecialchars($message['phone']); ?></div>
                            <?php endif; ?>
                        </div>
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <span class="message-type"><?php echo htmlspecialchars($message['inquiry_type']); ?></span>
                            <span class="message-date"><?php echo date('M d, Y h:i A', strtotime($message['created_at'])); ?></span>
                        </div>
                    </div>
                    
                    <div class="message-content">
                        <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                    </div>
                    
                    <div class="message-actions">
                        <?php if ($message['status'] == 'unread'): ?>
                        <a href="?read=<?php echo $message['id']; ?>" class="btn btn-mark-read">
                            <i class="fas fa-check"></i> Mark as Read
                        </a>
                        <?php endif; ?>
                        <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>" class="btn btn-view">
                            <i class="fas fa-reply"></i> Reply
                        </a>
                        <a href="?delete=<?php echo $message['id']; ?>" 
                           class="btn btn-decline"
                           onclick="return confirm('Delete this message?')">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
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