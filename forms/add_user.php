<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once "db_connect.php";

$success = $error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $description = $_POST['description'];

    // Check if username already exists
    $check_sql = "SELECT id FROM users WHERE username = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $error = "Username already exists. Please choose a different username.";
    } else {
        // Insert new user with plain text password
        $sql = "INSERT INTO users (name, username, email, password, role, description) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $name, $username, $email, $password, $role, $description);
        
        if ($stmt->execute()) {
            $success = "User added successfully!";
            // Clear form data after successful submission
            $name = $username = $email = $password = $role = $description = "";
        } else {
            $error = "Error adding user: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin Dashboard</title>
    <link href="../assets/pics/felta-logo (2).png" rel="icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-container {
            padding: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .welcome-header {
            background: linear-gradient(135deg, #241e62, #1a1647);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(36, 30, 98, 0.15);
            margin-bottom: 30px;
            color: white;
        }

        .welcome-header h2 {
            font-size: 28px;
            font-weight: 600;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .welcome-header p {
            margin: 10px 0 0;
            opacity: 0.9;
            font-size: 16px;
        }

        .sidebar {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(36, 30, 98, 0.08);
            height: fit-content;
        }

        .list-group-item {
            border: none;
            padding: 15px 20px;
            margin-bottom: 8px;
            border-radius: 12px !important;
            color: #555;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .list-group-item:last-child {
            margin-bottom: 0;
        }

        .list-group-item:hover {
            background: #f8f9fa;
            color: #241e62;
            transform: translateX(5px);
        }

        .list-group-item.active {
            background: linear-gradient(135deg, #241e62, #1a1647);
            color: white;
        }

        .list-group-item i {
            margin-right: 12px;
            font-size: 20px;
        }

        .main-content {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(36, 30, 98, 0.08);
        }

        .form-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .form-label {
            font-weight: 500;
            color: #241e62;
            margin-bottom: 8px;
        }

        .form-control {
            padding: 12px 20px;
            border: 2px solid rgba(36, 30, 98, 0.1);
            border-radius: 12px;
            transition: all 0.3s ease;
            font-size: 15px;
        }

        .form-control:focus {
            border-color: #241e62;
            box-shadow: 0 0 0 0.2rem rgba(36, 30, 98, 0.1);
        }

        .form-select {
            padding: 12px 20px;
            border: 2px solid rgba(36, 30, 98, 0.1);
            border-radius: 12px;
            transition: all 0.3s ease;
            font-size: 15px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 12 12'%3E%3Cpath fill='%23241e62' d='M6 8.825L1.175 4 2.05 3.125 6 7.075 9.95 3.125 10.825 4z'/%3E%3C/svg%3E");
        }

        .btn-primary {
            background: linear-gradient(135deg, #241e62, #1a1647);
            border: none;
            padding: 12px 30px;
            border-radius: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(36, 30, 98, 0.2);
        }

        .alert {
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 20px;
            border: none;
        }

        .alert-success {
            background: rgba(76, 175, 80, 0.1);
            color: #2e7d32;
        }

        .alert-danger {
            background: rgba(244, 67, 54, 0.1);
            color: #c62828;
        }

        .form-text {
            color: #666;
            font-size: 13px;
            margin-top: 5px;
        }

        .action-buttons {
            margin-bottom: 30px;
            display: flex;
            gap: 15px;
        }
        .action-buttons .btn {
            padding: 12px 25px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .action-buttons .btn-primary {
            background: linear-gradient(135deg, #241e62, #1a1647);
            border: none;
            box-shadow: 0 4px 15px rgba(36, 30, 98, 0.2);
        }
        .action-buttons .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(36, 30, 98, 0.3);
        }
        .modal-content {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        .modal-header {
            background: linear-gradient(135deg, #241e62, #1a1647);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px 30px;
        }
        .modal-header .btn-close {
            color: white;
            opacity: 0.8;
            transition: all 0.3s ease;
            filter: brightness(0) invert(1);
        }
        .modal-header .btn-close:hover {
            opacity: 1;
            transform: rotate(90deg);
        }
        .modal-body {
            padding: 30px;
        }
        .form-label {
            font-weight: 600;
            color: #241e62;
            margin-bottom: 8px;
        }
        .form-control, .form-select {
            padding: 12px 15px;
            border: 2px solid #e4e8f0;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #241e62;
            box-shadow: 0 0 0 0.2rem rgba(36, 30, 98, 0.1);
        }
        .modal-footer {
            padding: 20px 30px;
            border-top: 1px solid #e4e8f0;
        }
        .modal-footer .btn {
            padding: 10px 25px;
            font-weight: 600;
            border-radius: 8px;
        }
        .modal-footer .btn-secondary {
            background: #e4e8f0;
            border: none;
            color: #241e62;
        }
        .modal-footer .btn-primary {
            background: linear-gradient(135deg, #241e62, #1a1647);
            border: none;
        }
        .form-text {
            color: #666;
            font-size: 0.85rem;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="welcome-header">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
            <p>Manage Users</p>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="sidebar">
                    <div class="list-group">
                        <a href="tryadmin.php" class="list-group-item list-group-item-action">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                        <a href="add_user.php" class="list-group-item list-group-item-action active">
                            <i class="bi bi-people"></i> Manage Users
                        </a>
                        <a href="manage_student.php" class="list-group-item list-group-item-action">
                            <i class="bi bi-person-video3"></i> Manage Students
                        </a>
                        <a href="admin_events.php" class="list-group-item list-group-item-action">
                            <i class="bi bi-calendar-event"></i> Manage Events
                        </a>
                        <a href="admin_teachers.php" class="list-group-item list-group-item-action">
                            <i class="bi bi-person-workspace"></i> Manage Teachers
                        </a>
                        <a href="manage_counts.php" class="list-group-item list-group-item-action">
                            <i class="bi bi-graph-up"></i> Manage Statistics
                        </a>
                        <a href="contact.php" class="list-group-item list-group-item-action">
                            <i class="bi bi-envelope"></i> Contact Us
                        </a>
                        <a href="logout.php" class="list-group-item list-group-item-action text-danger">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="main-content">
                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <?php echo htmlspecialchars($success); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <div class="action-buttons">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            <i class="bi bi-person-plus-fill"></i>
                            Add New User
                        </button>
                    </div>

                    <!-- Admin Users Table -->
                    <div class="mb-5">
                        <h3 class="mb-4">Admin Users</h3>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Fetch admin users
                                    $sql = "SELECT * FROM users WHERE role = 'admin' ORDER BY name ASC";
                                    $result = $conn->query($sql);
                                    
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                                            echo "<td>
                                                <a href='delete_user.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this admin user?\")'>
                                                    <i class='bi bi-trash'></i>
                                                </a>
                                            </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5' class='text-center'>No admin users found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ICreate Users Table -->
                    <div class="mb-5">
                        <h3 class="mb-4">ICreate Cafe Users</h3>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Fetch icc users
                                    $sql = "SELECT * FROM users WHERE role = 'student' ORDER BY name ASC";
                                    $result = $conn->query($sql);
                                    
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                                            echo "<td>
                                                <a href='delete_user.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this student user?\")'>
                                                    <i class='bi bi-trash'></i>
                                                </a>
                                            </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5' class='text-center'>No student users found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                                        
                    <!-- TechVoc Users Table -->
                         <div class="mb-5">
                        <h3 class="mb-4">TechVoc Users</h3>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Fetch TechVoc users
                                    $sql = "SELECT * FROM users WHERE role = 'techvoc' ORDER BY name ASC";
                                    $result = $conn->query($sql);
                                    
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                                            echo "<td>
                                                <a href='delete_user.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this student user?\")'>
                                                    <i class='bi bi-trash'></i>
                                                </a>
                                            </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5' class='text-center'>No student users found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">
                        <i class="bi bi-person-plus-fill me-2"></i>
                        Add New User
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter full name" required>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
                                <div class="form-text">Username must be unique and will be used for login.</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address" required>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="">Select a role</option>
                                    <option value="admin">Admin</option>
                                    <option value="student">ICreate Student</option>
                                    <option value="techvoc">TechVoc Student</option>
                                </select>
                                <div class="form-text">Select the appropriate role for the user. This will determine their access level and dashboard.</div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter any additional information about the user"></textarea>
                            <div class="form-text">Optional: Add any additional information about the user.</div>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal" style="min-width: 120px;">
                                <i class="bi bi-x-circle me-1"></i>
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary" style="min-width: 120px;">
                                <i class="bi bi-check-circle me-1"></i>
                                Add User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
