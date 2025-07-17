<?php
session_start();
require('db_connect.php');

// Handle teacher submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_teacher'])) {
    $name = $_POST['name'];
    $subject = $_POST['subject'];
    $bio = $_POST['bio'];
    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $query = "INSERT INTO teachers (name, subject, bio, image) VALUES ('$name', '$subject', '$bio', '$image')";
        mysqli_query($conn, $query);
    }
}

// Fetch teachers
$teachers = mysqli_query($conn, "SELECT * FROM teachers ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Teachers</title>
    <link href="../assets/pics/felta-logo (2).png" rel="icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
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
            font-weight: 600;
            font-size: 28px;
            position: relative;
            padding-bottom: 15px;
        }

        h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, #241e62, #3a2f8f);
            border-radius: 3px;
        }

        h3 {
            color: #241e62;
            font-weight: 600;
            margin: 40px 0 20px;
            font-size: 24px;
        }

        .teacher-form {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(36, 30, 98, 0.05);
            margin-bottom: 40px;
        }

        .form-label {
            color: #241e62;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #241e62;
            box-shadow: 0 0 0 3px rgba(36, 30, 98, 0.1);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .btn {
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-right: 10px;
        }

        .btn-primary {
            background: linear-gradient(90deg, #241e62, #3a2f8f);
            border: none;
            color: white;
        }

        .btn-secondary {
            background: linear-gradient(90deg, #6c757d, #495057);
            border: none;
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(36, 30, 98, 0.1);
        }

        .table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(36, 30, 98, 0.05);
        }

        .table thead {
            background: linear-gradient(90deg, #241e62, #3a2f8f);
            color: white;
        }

        .table th {
            font-weight: 500;
            padding: 15px;
            border: none;
            text-align: center;
        }

        .table td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
            text-align: center;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .teacher-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(36, 30, 98, 0.1);
            transition: transform 0.3s ease;
            margin: 0 auto;
            display: block;
        }

        .teacher-image:hover {
            transform: scale(1.05);
        }

        .btn-sm {
            padding: 8px 15px;
            font-size: 13px;
        }

        .btn-warning {
            background: linear-gradient(90deg, #ffc107, #ffb300);
            border: none;
            color: #000;
        }

        .btn-danger {
            background: linear-gradient(90deg, #dc3545, #c82333);
            border: none;
            color: white;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .back-btn i {
            font-size: 18px;
        }

        .bio-text {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            margin: 0 auto;
        }

        .subject-badge {
            background: linear-gradient(90deg, #241e62, #3a2f8f);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 8px;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <a href="tryadmin.php" class="btn btn-secondary back-btn">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>

        <h2>Manage Teachers</h2>
        
        <div class="teacher-form">
            <form action="admin_teachers.php" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Subject</label>
                        <input type="text" name="subject" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Bio</label>
                    <textarea name="bio" class="form-control" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Image</label>
                    <input type="file" name="image" class="form-control" required>
                </div>
                <button type="submit" name="add_teacher" class="btn btn-primary">
                    <i class="bi bi-person-plus"></i> Add Teacher
                </button>
            </form>
        </div>

        <h3>Existing Teachers</h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Subject</th>
                        <th>Bio</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($teachers)) { ?>
                        <tr>
                            <td>
                                <img src="uploads/<?php echo $row['image']; ?>" class="teacher-image" alt="<?php echo htmlspecialchars($row['name']); ?>">
                            </td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><span class="subject-badge"><?php echo htmlspecialchars($row['subject']); ?></span></td>
                            <td><div class="bio-text" title="<?php echo htmlspecialchars($row['bio']); ?>"><?php echo htmlspecialchars($row['bio']); ?></div></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="edit_teachers.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="delete_teachers.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this teacher?')">
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
