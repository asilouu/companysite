<?php
session_start();
include 'db_connect.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Add error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['student_id'])) {
        $student_id = $_POST['student_id'];
        
        // Handle new session
        if (isset($_POST['add_session'])) {
            $new_date = $_POST['new_session_date'];
            $add_session_query = "INSERT INTO student_progress (student_id, hours_completed, total_hours, description, enrollment_date) VALUES (?, 0, 100, 'New Session', ?)";
            $stmt = $conn->prepare($add_session_query);
            if ($stmt === false) {
                die("Error preparing add session statement: " . $conn->error);
            }
            $stmt->bind_param("is", $student_id, $new_date);
            if (!$stmt->execute()) {
                die("Error executing add session statement: " . $stmt->error);
            }
            $stmt->close();
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
        
        // Handle date update
        if (isset($_POST['update_date'])) {
            $session_id = $_POST['session_id'];
            $new_date = $_POST['edit_session_date'];
            $update_date_query = "UPDATE student_progress SET enrollment_date = ? WHERE id = ?";
            $stmt = $conn->prepare($update_date_query);
            if ($stmt === false) {
                die("Error preparing update date statement: " . $conn->error);
            }
            $stmt->bind_param("si", $new_date, $session_id);
            if (!$stmt->execute()) {
                die("Error executing update date statement: " . $stmt->error);
            }
            $stmt->close();
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
        
        // Handle renewal request
        if (isset($_POST['renew'])) {
            $renew_query = "UPDATE student_progress SET hours_completed = 0, enrollment_date = CURRENT_DATE WHERE student_id = ?";
            $stmt = $conn->prepare($renew_query);
            if ($stmt === false) {
                die("Error preparing renew statement: " . $conn->error);
            }
            $stmt->bind_param("i", $student_id);
            if (!$stmt->execute()) {
                die("Error executing renew statement: " . $stmt->error);
            }
            $stmt->close();
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
        
        // Handle session deletion
        if (isset($_POST['delete_session'])) {
            $session_id = $_POST['session_id'];
            $delete_query = "DELETE FROM student_progress WHERE id = ?";
            $stmt = $conn->prepare($delete_query);
            if ($stmt === false) {
                die("Error preparing delete statement: " . $conn->error);
            }
            $stmt->bind_param("i", $session_id);
            if (!$stmt->execute()) {
                die("Error executing delete statement: " . $stmt->error);
            }
            $stmt->close();
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
        
        // Handle regular update
        $hours_completed = $_POST['hours_completed'];
        $total_hours = $_POST['total_hours'];
        $description = $_POST['description'];
        
        // Check if record exists
        $check_query = "SELECT id FROM student_progress WHERE student_id = ?";
        $stmt = $conn->prepare($check_query);
        if ($stmt === false) {
            die("Error preparing check statement: " . $conn->error);
        }
        $stmt->bind_param("i", $student_id);
        if (!$stmt->execute()) {
            die("Error executing check statement: " . $stmt->error);
        }
        $result = $stmt->get_result();
        $stmt->close();
        
        if ($result->num_rows > 0) {
            // Update existing record
            $update_query = "UPDATE student_progress SET hours_completed = ?, total_hours = ?, description = ? WHERE student_id = ?";
            $stmt = $conn->prepare($update_query);
            if ($stmt === false) {
                die("Error preparing update statement: " . $conn->error);
            }
            $stmt->bind_param("iisi", $hours_completed, $total_hours, $description, $student_id);
        } else {
            // Insert new record
            $insert_query = "INSERT INTO student_progress (student_id, hours_completed, total_hours, description, enrollment_date) VALUES (?, ?, ?, ?, CURRENT_DATE)";
            $stmt = $conn->prepare($insert_query);
            if ($stmt === false) {
                die("Error preparing insert statement: " . $conn->error);
            }
            $stmt->bind_param("iiis", $student_id, $hours_completed, $total_hours, $description);
        }
        
        if (!$stmt->execute()) {
            die("Error executing statement: " . $stmt->error);
        }
        $stmt->close();
    }
}

// Fetch all students
$students_query = "SELECT id, username, email, name FROM users WHERE role = 'student'";
$students_result = $conn->query($students_query);
if ($students_result === false) {
    die("Error fetching students: " . $conn->error);
}

// Fetch student progress
$progress_query = "SELECT sp.*, u.name as student_name,
                  COALESCE(sp.enrollment_date, CURRENT_DATE) as enrollment_date,
                  DATEDIFF(CURRENT_DATE, COALESCE(sp.enrollment_date, CURRENT_DATE)) as days_enrolled,
                  CASE 
                    WHEN DATEDIFF(CURRENT_DATE, COALESCE(sp.enrollment_date, CURRENT_DATE)) >= 365 THEN 'Expired'
                    ELSE CONCAT(365 - DATEDIFF(CURRENT_DATE, COALESCE(sp.enrollment_date, CURRENT_DATE)), ' days remaining')
                  END as enrollment_status
                  FROM student_progress sp 
                  JOIN users u ON sp.student_id = u.id
                  WHERE sp.student_id = ?
                  ORDER BY sp.enrollment_date DESC";
$progress_result = $conn->query($progress_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Students</title>
    <link href="../assets/pics/felta-logo (2).png" rel="icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
            padding: 30px;
            min-height: 100vh;
        }

        .admin-container {
            max-width: 1200px;
            background: #fff;
            margin: auto;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(36, 30, 98, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 40px;
            color: #241e62;
            font-weight: 700;
            font-size: 32px;
            position: relative;
            padding-bottom: 15px;
            letter-spacing: 0.5px;
        }

        h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 4px;
            background: linear-gradient(90deg, #241e62, #3a2f8f);
            border-radius: 4px;
        }

        .progress-block {
            margin-bottom: 40px;
            background: #fff;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(36, 30, 98, 0.08);
            transition: all 0.3s ease;
            border: 1px solid rgba(36, 30, 98, 0.1);
        }

        .progress-block:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(36, 30, 98, 0.15);
            border-color: rgba(36, 30, 98, 0.2);
        }

        .student-info {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 25px;
            border: 1px solid rgba(36, 30, 98, 0.1);
        }

        .student-info h4 {
            color: #241e62;
            margin: 0 0 10px 0;
            font-size: 22px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .student-info p {
            color: #6c757d;
            margin: 0;
            font-size: 15px;
        }

        .form-label {
            color: #241e62;
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 15px;
            letter-spacing: 0.3px;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 15px;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }

        .form-control:focus {
            border-color: #241e62;
            box-shadow: 0 0 0 4px rgba(36, 30, 98, 0.1);
            background-color: #fff;
        }

        .progress {
            height: 30px;
            background-color: #e9ecef;
            border-radius: 15px;
            margin: 25px 0;
            overflow: hidden;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .progress-bar {
            background: linear-gradient(90deg, #241e62, #3a2f8f);
            border-radius: 15px;
            transition: width 0.8s ease;
            font-size: 14px;
            font-weight: 600;
            color: white;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            letter-spacing: 0.5px;
        }

        .save-btn {
            background: linear-gradient(90deg, #241e62, #3a2f8f);
            color: white;
            padding: 14px 30px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(36, 30, 98, 0.2);
            width: 100%;
            max-width: 200px;
            margin: 20px auto;
            display: block;
        }

        .save-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(36, 30, 98, 0.3);
            background: linear-gradient(90deg, #3a2f8f, #241e62);
        }

        .btn-secondary {
            background: linear-gradient(90deg, #6c757d, #495057);
            border: none;
            color: white;
            padding: 12px 25px;
            border-radius: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.2);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.3);
        }

        .search-container {
            max-width: 600px;
            margin: 0 auto 40px;
        }

        .input-group {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(36, 30, 98, 0.08);
            overflow: hidden;
            border: 1px solid rgba(36, 30, 98, 0.1);
        }

        .input-group-text {
            background: transparent;
            border: none;
            color: #241e62;
            padding-left: 25px;
            font-size: 20px;
        }

        .input-group .form-control {
            border: none;
            padding: 20px;
            font-size: 16px;
            box-shadow: none;
            color: #241e62;
            background-color: transparent;
        }

        .input-group .form-control:focus {
            box-shadow: none;
            background-color: transparent;
        }

        .input-group .form-control::placeholder {
            color: #6c757d;
            font-size: 16px;
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        input[type="number"].form-control {
            width: 100%;
            max-width: 200px;
        }

        .text-muted {
            color: #6c757d !important;
            font-size: 13px;
            margin-top: 8px;
            display: block;
        }

        .alert {
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            border: none;
            box-shadow: 0 5px 20px rgba(36, 30, 98, 0.08);
        }

        .alert-info {
            background: linear-gradient(135deg, #e8e9ff 0%, #d1d4ff 100%);
            color: #241e62;
            font-weight: 500;
        }

        .enrollment-info {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid rgba(36, 30, 98, 0.1);
        }
        
        .text-danger {
            color: #dc3545 !important;
        }
        
        .text-success {
            color: #198754 !important;
        }
        
        .alert-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeeba 100%);
            color: #856404;
            border: 1px solid #ffeeba;
        }
        
        .btn-warning {
            background: linear-gradient(90deg, #ffc107, #ffb300);
            border: none;
            color: #000;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-warning:hover {
            background: linear-gradient(90deg, #ffb300, #ffc107);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
        }

        .enrollment-sessions {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 20px;
            border-radius: 15px;
            margin: 20px 0;
            border: 1px solid rgba(36, 30, 98, 0.1);
        }

        .session-header {
            color: #241e62;
            font-size: 18px;
            font-weight: 600;
            margin: 30px 0 20px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid rgba(36, 30, 98, 0.1);
        }

        .session-item {
            background: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            box-shadow: 0 2px 10px rgba(36, 30, 98, 0.05);
            display: grid;
            grid-template-columns: 80px 1fr 150px 100px;
            gap: 15px;
            align-items: center;
        }

        .session-number {
            background: linear-gradient(90deg, #241e62, #3a2f8f);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            text-align: center;
            white-space: nowrap;
        }

        .session-description {
            color: #495057;
            font-size: 14px;
            padding: 0 10px;
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .session-description input {
            width: 100%;
            padding: 6px 10px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .session-description input:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
            outline: none;
        }

        .btn-save-description {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }

        .btn-save-description:hover {
            background: linear-gradient(135deg, #0a58ca, #084298);
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(13, 110, 253, 0.2);
        }

        .btn-save-description i {
            font-size: 14px;
        }

        .btn-save-description:disabled {
            background: #e9ecef;
            color: #6c757d;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .session-date {
            color: #6c757d;
            font-size: 14px;
            text-align: center;
        }

        .session-status {
            font-size: 13px;
            padding: 4px 12px;
            border-radius: 15px;
            font-weight: 500;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-expired {
            background: #f8d7da;
            color: #721c24;
        }

        .session-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }

        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .action-btn::after {
            display: none;
        }

        .edit-date-btn {
            color: #0d6efd;
            font-size: 16px;
        }

        .edit-date-btn:hover {
            color: #0a58ca;
            transform: translateY(-2px);
        }

        .delete-session-btn {
            color: #dc3545;
            font-size: 16px;
        }

        .delete-session-btn:hover {
            color: #bb2d3b;
            transform: translateY(-2px);
        }

        .action-btn i {
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .action-btn:hover i {
            transform: scale(1.1);
        }

        .action-btn[title]:hover::before {
            content: attr(title);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            padding: 4px 8px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            font-size: 11px;
            border-radius: 4px;
            white-space: nowrap;
            margin-bottom: 5px;
            z-index: 1;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .action-btn[title]:hover::after {
            content: '';
            position: absolute;
            bottom: calc(100% - 4px);
            left: 50%;
            transform: translateX(-50%);
            border-width: 4px;
            border-style: solid;
            border-color: rgba(0, 0, 0, 0.7) transparent transparent transparent;
            z-index: 1;
        }

        .add-session-form {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin: 20px 0 40px 0;
            box-shadow: 0 2px 10px rgba(36, 30, 98, 0.05);
            border: 1px solid rgba(36, 30, 98, 0.1);
        }

        .add-session-content {
            display: flex;
            gap: 15px;
            align-items: flex-end;
        }

        .date-input-group {
            flex: 1;
        }

        .date-input-group label {
            color: #495057;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 6px;
            display: block;
        }

        .date-input-group input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .date-input-group input:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
            outline: none;
        }

        .btn-add-session {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            height: 38px;
        }

        .btn-add-session:hover {
            background: linear-gradient(135deg, #0a58ca, #084298);
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(13, 110, 253, 0.2);
        }

        .btn-add-session i {
            font-size: 16px;
        }

        .date-edit-form {
            display: none;
            margin-top: 12px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .date-edit-form.active {
            display: block;
        }

        .form-actions {
            display: flex;
            gap: 8px;
            margin-top: 10px;
        }

        .btn-update {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            color: white;
            border: none;
            padding: 6px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-update:hover {
            background: linear-gradient(135deg, #0a58ca, #084298);
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(13, 110, 253, 0.2);
        }

        .btn-cancel {
            background: #f8f9fa;
            color: #6c757d;
            border: 1px solid #dee2e6;
            padding: 6px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-cancel:hover {
            background: #e9ecef;
            color: #495057;
            border-color: #ced4da;
            transform: translateY(-1px);
        }

        .btn-update i, .btn-cancel i {
            font-size: 14px;
        }

        @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css");
    </style>
</head>
<body>
    <div class="admin-container">
        <h2>Manage Student Progress</h2>
        
        <div class="row mb-4">
            <div class="col">
                <a href="tryadmin.php" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        </div>

        <!-- Add search box -->
        <div class="search-container mb-4">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" id="studentSearch" class="form-control" placeholder="Search students by name..." onkeyup="searchStudents()">
            </div>
        </div>

        <?php if ($students_result && $students_result->num_rows > 0): ?>
            <?php while ($student = $students_result->fetch_assoc()): 
                // Get progress for this student
                $progress_query = "SELECT * FROM student_progress WHERE student_id = " . $student['id'];
                $progress = $conn->query($progress_query)->fetch_assoc();
            ?>
                <div class="progress-block student-card">
                    <div class="student-info">
                        <h4><?php echo htmlspecialchars($student['name']); ?></h4>
                        <p>Email: <?php echo htmlspecialchars($student['email']); ?></p>
                    </div>

                    <div class="enrollment-sessions">
                        <div class="session-header">Enrollment Sessions</div>
                        
                        <!-- Add New Session Form -->
                        <form method="POST" action="" class="add-session-form">
                            <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                            <div class="add-session-content">
                                <div class="date-input-group">
                                    <label>Session Date</label>
                                    <input type="date" name="new_session_date" required 
                                           value="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <button type="submit" name="add_session" class="btn-add-session">
                                    <i class="bi bi-plus-lg"></i>
                                    Add Session
                                </button>
                            </div>
                        </form>

                        <?php
                        // Get sessions for this specific student
                        $sessions_query = "SELECT sp.*, u.name as student_name,
                            COALESCE(sp.enrollment_date, CURRENT_DATE) as enrollment_date,
                            sp.description
                            FROM student_progress sp 
                            JOIN users u ON sp.student_id = u.id
                            WHERE sp.student_id = ?
                            ORDER BY sp.enrollment_date DESC";

                        $stmt = $conn->prepare($sessions_query);
                        if ($stmt === false) {
                            echo '<div class="alert alert-danger">Error preparing sessions statement: ' . $conn->error . '</div>';
                        } else {
                            if (!$stmt->bind_param("i", $student['id'])) {
                                echo '<div class="alert alert-danger">Error binding parameter: ' . $stmt->error . '</div>';
                            } else {
                                if (!$stmt->execute()) {
                                    echo '<div class="alert alert-danger">Error executing statement: ' . $stmt->error . '</div>';
                                } else {
                                    $sessions_result = $stmt->get_result();
                                    $session_number = 1;

                                    if ($sessions_result && $sessions_result->num_rows > 0) {
                                        while ($session = $sessions_result->fetch_assoc()) {
                        ?>
                                            <div class="session-item">
                                                <div class="session-number">#<?php echo $session_number++; ?></div>
                                                <div class="session-description">
                                                    <input type="text" 
                                                           id="description-<?php echo $session['id']; ?>"
                                                           value="<?php echo htmlspecialchars($session['description'] ?? ''); ?>" 
                                                           placeholder="Enter session description"
                                                           onkeyup="enableSaveButton(<?php echo $session['id']; ?>)">
                                                    <button type="button" 
                                                            class="btn-save-description" 
                                                            id="save-btn-<?php echo $session['id']; ?>"
                                                            onclick="saveDescription(<?php echo $session['id']; ?>)"
                                                            disabled>
                                                        <i class="bi bi-check-lg"></i> Save
                                                    </button>
                                                </div>
                                                <div class="session-date">
                                                    <span class="date-display">
                                                        <?php echo date('F j, Y', strtotime($session['enrollment_date'])); ?>
                                                    </span>
                                                </div>
                                                <div class="session-actions">
                                                    <button class="action-btn edit-date-btn" onclick="toggleDateEdit(<?php echo $session['id']; ?>)" title="Edit Date">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <form method="POST" action="" class="delete-session-form" onsubmit="return confirm('Are you sure you want to delete this session?');">
                                                        <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                                                        <input type="hidden" name="session_id" value="<?php echo $session['id']; ?>">
                                                        <button type="submit" name="delete_session" class="action-btn delete-session-btn" title="Delete Session">
                                                            <i class="bi bi-trash3"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                            <form method="POST" action="" class="date-edit-form" id="edit-form-<?php echo $session['id']; ?>">
                                                <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                                                <input type="hidden" name="session_id" value="<?php echo $session['id']; ?>">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <input type="date" name="edit_session_date" class="form-control" 
                                                               value="<?php echo date('Y-m-d', strtotime($session['enrollment_date'])); ?>" required>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-actions">
                                                            <button type="submit" name="update_date" class="btn-update">
                                                                <i class="bi bi-check-lg"></i> Update
                                                            </button>
                                                            <button type="button" class="btn-cancel" 
                                                                    onclick="toggleDateEdit(<?php echo $session['id']; ?>)">
                                                                <i class="bi bi-x-lg"></i> Cancel
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                        <?php 
                                        }
                                    } else {
                        ?>
                                        <div class="session-item">
                                            <div class="session-number">#1</div>
                                            <div class="session-description">
                                                <input type="text" placeholder="Enter session description">
                                            </div>
                                            <div class="session-date">
                                                <?php echo date('F j, Y'); ?>
                                            </div>
                                            <div class="session-actions">
                                                <button class="action-btn edit-date-btn" disabled title="No sessions available">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <button class="action-btn delete-session-btn" disabled title="No sessions available">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </div>
                                        </div>
                        <?php 
                                    }
                                    $stmt->close();
                                }
                            }
                        }
                        ?>
                    </div>

                    <form method="POST" action="">
                        <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                        
                        <?php if ($progress && isset($progress['days_enrolled']) && $progress['days_enrolled'] >= 365): ?>
                            <div class="alert alert-warning mb-3">
                                <strong>Enrollment Expired!</strong> Click the Renew button to reset progress and extend enrollment.
                                <button type="submit" name="renew" class="btn btn-warning mt-2">Renew Enrollment</button>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label">Hours Completed:</label>
                            <input type="number" name="hours_completed" class="form-control" 
                                   value="<?php echo $progress ? $progress['hours_completed'] : 0; ?>" min="0" 
                                   max="<?php echo $progress ? $progress['total_hours'] : 100; ?>">
                            <small class="text-muted">Out of <?php echo $progress ? $progress['total_hours'] : 100; ?> total hours</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Total Hours:</label>
                            <input type="number" name="total_hours" class="form-control" 
                                   value="<?php echo $progress ? $progress['total_hours'] : 100; ?>" min="1">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Progress Description:</label>
                            <textarea name="description" class="form-control" rows="3" 
                                      placeholder="Enter progress description (e.g., '1-3 hours: Lecture, 4-7 hours: Practice')"><?php echo $progress ? htmlspecialchars($progress['description']) : ''; ?></textarea>
                        </div>

                        <?php if ($progress): ?>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: <?php echo ($progress['hours_completed'] / $progress['total_hours']) * 100; ?>%"
                                 aria-valuenow="<?php echo $progress['hours_completed']; ?>" 
                                 aria-valuemin="0" 
                                 aria-valuemax="<?php echo $progress['total_hours']; ?>">
                                <?php echo $progress['hours_completed']; ?>/<?php echo $progress['total_hours']; ?> hours
                            </div>
                        </div>
                        <?php endif; ?>

                        <button type="submit" class="save-btn">Save Progress</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-info">No students found.</div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function searchStudents() {
            const searchInput = document.getElementById('studentSearch');
            const searchText = searchInput.value.toLowerCase();
            const studentCards = document.getElementsByClassName('student-card');

            for (let card of studentCards) {
                const studentName = card.querySelector('h4').textContent.toLowerCase();
                if (studentName.includes(searchText)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            }
        }

        function toggleDateEdit(sessionId) {
            const editForm = document.getElementById('edit-form-' + sessionId);
            editForm.classList.toggle('active');
        }

        let originalDescriptions = {};

        function enableSaveButton(sessionId) {
            const input = document.getElementById(`description-${sessionId}`);
            const saveBtn = document.getElementById(`save-btn-${sessionId}`);
            const currentValue = input.value.trim();
            
            // Store original value if not already stored
            if (!originalDescriptions[sessionId]) {
                originalDescriptions[sessionId] = input.value.trim();
            }
            
            // Enable save button only if value has changed
            saveBtn.disabled = currentValue === originalDescriptions[sessionId];
        }

        function saveDescription(sessionId) {
            const input = document.getElementById(`description-${sessionId}`);
            const saveBtn = document.getElementById(`save-btn-${sessionId}`);
            const description = input.value.trim();
            
            // Show loading state
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="bi bi-arrow-repeat spin"></i> Saving...';
            
            fetch('update_session_description.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `session_id=${sessionId}&description=${encodeURIComponent(description)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update original value
                    originalDescriptions[sessionId] = description;
                    // Show success state
                    saveBtn.innerHTML = '<i class="bi bi-check-lg"></i> Saved!';
                    setTimeout(() => {
                        saveBtn.innerHTML = '<i class="bi bi-check-lg"></i> Save';
                        saveBtn.disabled = true;
                    }, 2000);
                } else {
                    throw new Error(data.message || 'Failed to save description');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                saveBtn.innerHTML = '<i class="bi bi-x-lg"></i> Error';
                saveBtn.disabled = false;
                setTimeout(() => {
                    saveBtn.innerHTML = '<i class="bi bi-check-lg"></i> Save';
                }, 2000);
                alert('Error saving description: ' + error.message);
            });
        }

        // Add CSS for loading spinner
        const style = document.createElement('style');
        style.textContent = `
            @keyframes spin {
                to { transform: rotate(360deg); }
            }
            .spin {
                animation: spin 1s linear infinite;
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>