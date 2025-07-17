<?php
session_start();
include 'db_connect.php'; // Include database connection

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch Admins
$sqlAdmins = "SELECT id, username, email, description FROM users WHERE role = 'admin'";
$resultAdmins = $conn->query($sqlAdmins);

// Fetch Users
$sqlUsers = "SELECT id, username, email, description FROM users WHERE role = 'student'";
$resultUsers = $conn->query($sqlUsers);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Felta Techvoc Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-container {
            padding: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .welcome-header {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .welcome-header h2 {
            color: #241e62;
            font-size: 24px;
            font-weight: 600;
            margin: 0;
        }

        .welcome-header p {
            color: #666;
            margin: 5px 0 0;
        }

        .sidebar {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        }

        .list-group-item {
            border: none;
            padding: 12px 20px;
            margin-bottom: 5px;
            border-radius: 10px !important;
            color: #555;
            transition: all 0.3s ease;
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
            background: #241e62;
            color: white;
        }

        .list-group-item i {
            margin-right: 10px;
            font-size: 18px;
        }

        .main-content {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-header h3 {
            color: #241e62;
            font-size: 20px;
            font-weight: 600;
            margin: 0;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #241e62;
            border-color: #241e62;
        }

        .btn-primary:hover {
            background: #1a1647;
            border-color: #1a1647;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background: #5a6268;
            border-color: #5a6268;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background: #c82333;
            border-color: #bd2130;
            transform: translateY(-2px);
        }

        .table {
            margin-bottom: 30px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        }

        .table thead th {
            background: #241e62;
            color: white;
            font-weight: 500;
            border: none;
            padding: 15px;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-color: #eee;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .btn-sm {
            padding: 5px 15px;
            font-size: 13px;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
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
                        <a href="users.php" class="list-group-item list-group-item-action active">
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
                    <div class="section-header">
                        <h3>Manage Users</h3>
                        <div class="action-buttons">
                            <a href="tryadmin.php" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Dashboard
                            </a>
                            <a href="add_user.php" class="btn btn-primary">
                                <i class="bi bi-person-plus"></i> Add User
                            </a>
                        </div>
                    </div>

                    <!-- Admins Table -->
                    <div class="section-header">
                        <h3>Administrators</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($resultAdmins->num_rows > 0) {
                                    while ($row = $resultAdmins->fetch_assoc()) {
                                        echo "<tr>
                                                <td>{$row['id']}</td>
                                                <td>{$row['username']}</td>
                                                <td>{$row['email']}</td>
                                                <td>{$row['description']}</td>
                                                <td>
                                                    <a href='delete_user.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this admin?\");'>
                                                        <i class='bi bi-trash'></i> Delete
                                                    </a>
                                                </td>
                                              </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='no-data'>No administrators found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Users Table -->
                    <div class="section-header">
                        <h3>Students</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($resultUsers->num_rows > 0) {
                                    while ($row = $resultUsers->fetch_assoc()) {
                                        echo "<tr>
                                                <td>{$row['id']}</td>
                                                <td>{$row['username']}</td>
                                                <td>{$row['email']}</td>
                                                <td>{$row['description']}</td>
                                                <td>
                                                    <a href='delete_user.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this student?\");'>
                                                        <i class='bi bi-trash'></i> Delete
                                                    </a>
                                                </td>
                                              </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='no-data'>No students found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
