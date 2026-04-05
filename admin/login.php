<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include auth functions
require_once '../includes/auth.php';

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}

// Initialize variables
$error_message = '';
$success_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Validate inputs
    if (empty($username) || empty($password)) {
        $error_message = "Please enter both username and password.";
    } else {
        // Attempt login
        if (adminLogin($username, $password)) {
            // Redirect to dashboard
            header('Location: dashboard.php');
            exit();
        } else {
            $error_message = "Invalid username or password.";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - LACH</title>
    <style>
        :root {
            --primary: #1a76d1;
            --primary-dark: #0d5aa7;
            --light: #f8fafc;
            --dark: #1e293b;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --radius: 16px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #e8f4ff 0%, #f0f9ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            width: 100%;
            max-width: 400px;
        }
        
        .login-card {
            background: white;
            border-radius: var(--radius);
            padding: 40px;
            box-shadow: var(--shadow);
            text-align: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 30px;
        }
        
        .logo i {
            color: #ff4757;
            margin-right: 10px;
            font-size: 2.5rem;
        }
        
        .login-card h1 {
            font-size: 1.5rem;
            margin-bottom: 30px;
            color: var(--dark);
        }
        
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26, 118, 209, 0.1);
        }
        
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #1a76d1 0%, #0d5aa7 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        
        .alert-error {
            background: #fee;
            border: 1px solid #fcc;
            color: #c00;
        }
        
        .alert-success {
            background: #dfd;
            border: 1px solid #bfb;
            color: #080;
        }
        
        .back-home {
            display: inline-block;
            margin-top: 20px;
            color: var(--primary);
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .back-home:hover {
            text-decoration: underline;
        }
        
        .forgot-password {
            text-align: right;
            margin-top: 5px;
        }
        
        .forgot-password a {
            color: var(--primary);
            font-size: 0.85rem;
            text-decoration: none;
        }
        
        .forgot-password a:hover {
            text-decoration: underline;
        }
        
        .demo-credentials {
            margin-top: 20px;
            padding: 15px;
            background: #f0f9ff;
            border-radius: 8px;
            border: 1px dashed var(--primary);
            font-size: 0.9rem;
            text-align: left;
        }
        
        .demo-credentials h4 {
            margin-bottom: 10px;
            color: var(--primary);
        }
        
        .demo-credentials p {
            margin-bottom: 5px;
            font-size: 0.85rem;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo">
                <i class="fas fa-heartbeat"></i>
                LACH Admin
            </div>
            
            <h1>Admin Login</h1>
            
            <?php if ($error_message): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success_message): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username or Email</label>
                    <input type="text" id="username" name="username" required 
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                           placeholder="Enter username or email">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required 
                           placeholder="Enter password">

                </div>
                
                <button type="submit" class="btn-login">
                     Login
                </button>
            </form>
            
   
            <a href="../index.php" class="back-home">
                Back to Home
            </a>
        </div>
    </div>
    
    <script>
        // Focus on username field when page loads
        document.getElementById('username').focus();
        
        // Show/hide password toggle (optional enhancement)
        const passwordInput = document.getElementById('password');
        const showPasswordToggle = document.createElement('span');
        showPasswordToggle.innerHTML = '👁️';
        showPasswordToggle.style.position = 'absolute';
        showPasswordToggle.style.right = '15px';
        showPasswordToggle.style.top = '70%';
        showPasswordToggle.style.transform = 'translateY(-50%)';
        showPasswordToggle.style.cursor = 'pointer';
        showPasswordToggle.style.fontSize = '1.2rem';
        
        passwordInput.parentElement.style.position = 'relative';
        passwordInput.parentElement.appendChild(showPasswordToggle);
        
        showPasswordToggle.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.innerHTML = '👁️‍🗨️';
            } else {
                passwordInput.type = 'password';
                this.innerHTML = '👁️';
            }
        });
    </script>
</body>
</html>