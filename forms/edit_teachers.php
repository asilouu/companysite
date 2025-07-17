<?php
session_start();
require('db_connect.php');

// Check if user is logged in and is admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Check if the 'id' parameter exists and is valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid teacher ID";
    header('Location: admin_teachers.php');
    exit();
}

$id = (int)$_GET['id'];

// Fetch teacher data using prepared statement
$query = "SELECT * FROM teachers WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$teacher = $result->fetch_assoc();

if (!$teacher) {
    $_SESSION['error'] = "Teacher not found";
    header('Location: admin_teachers.php');
    exit();
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_teacher'])) {
    // Sanitize and validate input
    $name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING));
    $subject = trim(filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING));
    $bio = trim(filter_input(INPUT_POST, 'bio', FILTER_SANITIZE_STRING));
    
    // Validate required fields
    if (empty($name)) $errors[] = "Name is required";
    if (empty($subject)) $errors[] = "Subject is required";
    if (empty($bio)) $errors[] = "Bio is required";
    
    // Handle image upload if a new image is provided
    $image = $teacher['image']; // Keep existing image by default
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($_FILES['image']['type'], $allowed_types)) {
            $errors[] = "Invalid file type. Only JPG, PNG and GIF are allowed.";
        } elseif ($_FILES['image']['size'] > $max_size) {
            $errors[] = "File size too large. Maximum size is 5MB.";
        } else {
            $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $new_filename = uniqid('teacher_') . '.' . $file_extension;
            $upload_path = "uploads/" . $new_filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                // Delete old image if it exists
                if ($teacher['image'] && file_exists("uploads/" . $teacher['image'])) {
                    unlink("uploads/" . $teacher['image']);
                }
                $image = $new_filename;
            } else {
                $errors[] = "Failed to upload image. Please try again.";
            }
        }
    }
    
    // If no errors, update the database
    if (empty($errors)) {
        $query = "UPDATE teachers SET name = ?, subject = ?, bio = ?, image = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $name, $subject, $bio, $image, $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Teacher updated successfully";
            header('Location: admin_teachers.php');
            exit();
        } else {
            $errors[] = "Database error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Teacher - Admin Dashboard</title>
    <link href="../assets/pics/felta-logo (2).png" rel="icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
            min-height: 100vh;
            padding: 30px;
        }
        .edit-container {
            max-width: 800px;
            background: #fff;
            margin: auto;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(36, 30, 98, 0.1);
        }
        .form-label {
            color: #241e62;
            font-weight: 500;
        }
        .btn-primary {
            background: linear-gradient(90deg, #241e62, #3a2f8f);
            border: none;
        }
        .btn-secondary {
            background: linear-gradient(90deg, #6c757d, #495057);
            border: none;
        }
        .current-image {
            max-width: 200px;
            border-radius: 10px;
            margin: 10px 0;
        }
        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <a href="admin_teachers.php" class="btn btn-secondary mb-4">
            <i class="bi bi-arrow-left"></i> Back to Teacher List
        </a>

        <h2 class="mb-4">Edit Teacher</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="edit_teachers.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($teacher['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Subject</label>
                <input type="text" name="subject" class="form-control" value="<?php echo htmlspecialchars($teacher['subject']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Bio</label>
                <textarea name="bio" class="form-control" rows="4" required><?php echo htmlspecialchars($teacher['bio']); ?></textarea>
            </div>
            <div class="mb-4">
                <label class="form-label">Current Image</label>
                <div>
                    <img src="uploads/<?php echo htmlspecialchars($teacher['image']); ?>" alt="Current teacher image" class="current-image">
                </div>
                <label class="form-label mt-2">New Image (Optional)</label>
                <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/gif">
                <small class="text-muted">Max file size: 5MB. Allowed formats: JPG, PNG, GIF</small>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" name="update_teacher" class="btn btn-primary">
                    <i class="bi bi-save"></i> Update Teacher
                </button>
                <a href="admin_teachers.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
