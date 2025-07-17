<?php
session_start();
require_once "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Check in users table for both admin and student roles
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Check password directly
        if ($password === $user['password']) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_id'] = $user['id'];
            
            // Redirect based on role
            switch($user['role']) {
                case 'admin':
                    header("Location: tryadmin.php");
                    break;
                case 'student':
                    header("Location: ../student.php");
                    break;
                case 'techvoc':
                    header("Location: felta-site-main/techvoc/learningsite.html");
                    break;
                default:
                    $error = "Invalid role assigned. Please contact administrator.";
                    break;
            }
            exit();
        } else {
            $error = "Invalid password. Please try again.";
        }
    } else {
        $error = "Username not found. Please check your username.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Felta TechVoc Academy</title>
    <link rel="icon" type="image/x-icon" href="../assets/pics/felta-logo (2).png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .login-container {
            background: white;
            padding: 1.5rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(36, 30, 98, 0.1);
            width: 100%;
            max-width: 450px;
            border: 1px solid rgba(36, 30, 98, 0.1);
        }
        .login-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .login-header img {
            max-width: 150px;
            margin-bottom: 1rem;
        }
        .login-header h2 {
            color: #241e62;
            font-weight: 700;
            font-size: 28px;
            margin-bottom: 0.5rem;
        }
        .form-label {
            color: #241e62;
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 0.5rem;
        }
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 12px 15px;
            font-size: 15px;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }
        .form-control:focus {
            border-color: #241e62;
            box-shadow: 0 0 0 4px rgba(36, 30, 98, 0.1);
            background-color: #fff;
        }
        .input-group-text {
            background: transparent;
            border: 2px solid #e9ecef;
            border-right: none;
            color: #241e62;
        }
        .input-group .form-control {
            border-left: none;
        }
        .input-group .form-control:focus {
            border-left: none;
        }
        .btn-primary {
            background: linear-gradient(90deg, #241e62, #3a2f8f);
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(36, 30, 98, 0.2);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(36, 30, 98, 0.3);
            background: linear-gradient(90deg, #3a2f8f, #241e62);
        }
        .alert {
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border: none;
            font-weight: 500;
        }
        .alert-danger {
            background: linear-gradient(135deg, #ffe8e8 0%, #ffd1d1 100%);
            color: #dc3545;
        }
        .back-link {
            color: #241e62;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .back-link:hover {
            color: #3a2f8f;
            transform: translateX(-3px);
        }
        .password-toggle {
            cursor: pointer;
            color: #6c757d;
            transition: color 0.3s ease;
        }
        .password-toggle:hover {
            color: #241e62;
        }
        .mb-4 {
            margin-bottom: 1rem !important;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <img src="../assets/pics/felta-logo (2).png" alt="Felta TechVoc Academy Logo">
            <h2>Welcome Back</h2>
            <p class="text-muted">Please login to your account</p>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-4">
                <label for="username" class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-person"></i>
                    </span>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                </div>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                    <span class="input-group-text password-toggle" onclick="togglePassword()">
                        <i class="bi bi-eye"></i>
                    </span>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-4">Login</button>
        </form>
        
        <div class="text-center">
            <a href="../index.php" class="back-link">
                <i class="bi bi-arrow-left"></i>
                Back to Website
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = document.querySelector('.password-toggle i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>
