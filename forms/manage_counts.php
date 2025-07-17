<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once "db_connect.php";

$success = $error = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $students = $_POST['students'];
    $courses = $_POST['courses'];
    $events = $_POST['events'];
    $trainers = $_POST['trainers'];

    // Update the counts in the database
    $sql = "UPDATE site_stats SET 
            students_count = ?, 
            courses_count = ?, 
            events_count = ?, 
            trainers_count = ? 
            WHERE id = 1";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $students, $courses, $events, $trainers);
    
    if ($stmt->execute()) {
        $success = "Statistics updated successfully!";
    } else {
        $error = "Error updating statistics: " . $conn->error;
    }
}

// Fetch current statistics
$sql = "SELECT * FROM site_stats WHERE id = 1";
$result = $conn->query($sql);
$stats = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Statistics - Admin Dashboard</title>
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

        .list-group-item:hover {
            background: #f8f9fa;
            color: #241e62;
            transform: translateX(5px);
        }

        .list-group-item.active {
            background: linear-gradient(135deg, #241e62, #1a1647);
            color: white;
        }

        .main-content {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(36, 30, 98, 0.08);
        }

        .form-label {
            font-weight: 600;
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

        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(36, 30, 98, 0.08);
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(36, 30, 98, 0.15);
        }

        .stats-card h3 {
            color: #241e62;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .stats-card .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="welcome-header">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
            <p>Manage Landing Page Statistics</p>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="sidebar">
                    <div class="list-group">
                        <a href="tryadmin.php" class="list-group-item list-group-item-action">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                        <a href="add_user.php" class="list-group-item list-group-item-action">
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
                        <a href="manage_counts.php" class="list-group-item list-group-item-action active">
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
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <?php echo htmlspecialchars($success); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="stats-card">
                                    <h3><i class="bi bi-people-fill me-2"></i>Students Count</h3>
                                    <div class="form-group">
                                        <label for="students" class="form-label">Number of Students</label>
                                        <input type="number" class="form-control" id="students" name="students" 
                                               value="<?php echo htmlspecialchars($stats['students_count'] ?? 0); ?>" 
                                               min="0" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="stats-card">
                                    <h3><i class="bi bi-book-fill me-2"></i>Courses Count</h3>
                                    <div class="form-group">
                                        <label for="courses" class="form-label">Number of Courses</label>
                                        <input type="number" class="form-control" id="courses" name="courses" 
                                               value="<?php echo htmlspecialchars($stats['courses_count'] ?? 0); ?>" 
                                               min="0" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="stats-card">
                                    <h3><i class="bi bi-calendar-event-fill me-2"></i>Events Count</h3>
                                    <div class="form-group">
                                        <label for="events" class="form-label">Number of Events</label>
                                        <input type="number" class="form-control" id="events" name="events" 
                                               value="<?php echo htmlspecialchars($stats['events_count'] ?? 0); ?>" 
                                               min="0" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="stats-card">
                                    <h3><i class="bi bi-person-badge-fill me-2"></i>Trainers Count</h3>
                                    <div class="form-group">
                                        <label for="trainers" class="form-label">Number of Trainers</label>
                                        <input type="number" class="form-control" id="trainers" name="trainers" 
                                               value="<?php echo htmlspecialchars($stats['trainers_count'] ?? 0); ?>" 
                                               min="0" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save-fill me-2"></i>
                                Update Statistics
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