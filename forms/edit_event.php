<?php
session_start();
require 'db_connect.php';

// Ensure only admins can access
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Initialize variables
$event = null;
$error = null;
$success = null;

try {
    // Validate event ID
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        throw new Exception("Invalid event ID");
    }

    $event_id = (int)$_GET['id'];

    // Fetch event data with prepared statement
    $stmt = $conn->prepare("SELECT e.*, GROUP_CONCAT(ep.photo_name) as photos 
                           FROM events e 
                           LEFT JOIN event_photos ep ON e.event_id = ep.event_id 
                           WHERE e.event_id = ? 
                           GROUP BY e.event_id");
    
    if (!$stmt) {
        throw new Exception("Database prepare error: " . $conn->error);
    }

    if (!$stmt->bind_param("i", $event_id)) {
        throw new Exception("Parameter binding error: " . $stmt->error);
    }

    if (!$stmt->execute()) {
        throw new Exception("Query execution error: " . $stmt->error);
    }

    $result = $stmt->get_result();
    if (!$result) {
        throw new Exception("Error getting result: " . $stmt->error);
    }

    if ($result->num_rows === 0) {
        throw new Exception("Event not found");
    }

    $event = $result->fetch_assoc();
    
    // Convert photos string to array if it exists
    if ($event['photos']) {
        $event['photos'] = explode(',', $event['photos']);
    } else {
        $event['photos'] = [];
    }

    $stmt->close();

} catch (Exception $e) {
    error_log("Error in edit_event.php: " . $e->getMessage());
    $error = $e->getMessage();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate required fields
        $required_fields = ['title', 'date', 'description'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Please fill in all required fields");
            }
        }

        // Sanitize input
        $title = htmlspecialchars(trim($_POST['title']));
        $date = htmlspecialchars(trim($_POST['date']));
        $description = htmlspecialchars(trim($_POST['description']));
        $category = htmlspecialchars(trim($_POST['category'] ?? ''));
        $location = htmlspecialchars(trim($_POST['location'] ?? ''));
        $capacity = (int)($_POST['capacity'] ?? 0);
        $status = htmlspecialchars(trim($_POST['status'] ?? 'upcoming'));

        // Start transaction
        $conn->begin_transaction();

        // Update event details
        $update_stmt = $conn->prepare("UPDATE events SET 
            title = ?, 
            date = ?, 
            description = ?, 
            category = ?, 
            location = ?, 
            capacity = ?, 
            status = ? 
            WHERE event_id = ?");

        if (!$update_stmt) {
            throw new Exception("Error preparing update statement: " . $conn->error);
        }

        $update_stmt->bind_param("sssssisi", 
            $title, $date, $description, $category, 
            $location, $capacity, $status, $event_id);

        if (!$update_stmt->execute()) {
            throw new Exception("Error updating event: " . $update_stmt->error);
        }

        // Handle main image upload if new image is provided
        if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === 0) {
            $file = $_FILES['main_image'];
            $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (!in_array($file_ext, $allowed_types)) {
                throw new Exception("Invalid file type. Only JPG, JPEG, PNG & GIF files are allowed.");
            }

            // Delete old image if exists
            if ($event['image']) {
                $old_image_path = "uploads/" . $event['image'];
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }

            // Upload new image
            $new_filename = 'main_' . $event_id . '_' . time() . '.' . $file_ext;
            $target_path = "uploads/" . $new_filename;
            
            if (!move_uploaded_file($file['tmp_name'], $target_path)) {
                throw new Exception("Error uploading image");
            }

            // Update image in database
            $image_stmt = $conn->prepare("UPDATE events SET image = ? WHERE event_id = ?");
            $image_stmt->bind_param("si", $new_filename, $event_id);
            if (!$image_stmt->execute()) {
                throw new Exception("Error updating image: " . $image_stmt->error);
            }
            $image_stmt->close();
        }

        // Handle additional photos if provided
        if (isset($_FILES['additional_photos'])) {
            $total_files = count($_FILES['additional_photos']['name']);
            
            for ($i = 0; $i < $total_files; $i++) {
                if ($_FILES['additional_photos']['error'][$i] === 0) {
                    $file = [
                        'name' => $_FILES['additional_photos']['name'][$i],
                        'type' => $_FILES['additional_photos']['type'][$i],
                        'tmp_name' => $_FILES['additional_photos']['tmp_name'][$i],
                        'error' => $_FILES['additional_photos']['error'][$i],
                        'size' => $_FILES['additional_photos']['size'][$i]
                    ];

                    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    if (!in_array($file_ext, $allowed_types)) {
                        continue; // Skip invalid files
                    }

                    $new_filename = 'photo_' . $event_id . '_' . uniqid() . '.' . $file_ext;
                    $target_path = "uploads/" . $new_filename;
                    
                    if (move_uploaded_file($file['tmp_name'], $target_path)) {
                        $photo_stmt = $conn->prepare("INSERT INTO event_photos (event_id, photo_name) VALUES (?, ?)");
                        $photo_stmt->bind_param("is", $event_id, $new_filename);
                        if (!$photo_stmt->execute()) {
                            throw new Exception("Error adding photo: " . $photo_stmt->error);
                        }
                        $photo_stmt->close();
                    }
                }
            }
        }

        // Delete selected photos if any
        if (isset($_POST['delete_photos']) && is_array($_POST['delete_photos'])) {
            $delete_stmt = $conn->prepare("DELETE FROM event_photos WHERE event_id = ? AND photo_name = ?");
            foreach ($_POST['delete_photos'] as $photo_name) {
                $photo_name = htmlspecialchars(trim($photo_name));
                $delete_stmt->bind_param("is", $event_id, $photo_name);
                if (!$delete_stmt->execute()) {
                    throw new Exception("Error deleting photo: " . $delete_stmt->error);
                }
                
                // Delete file from server
                $file_path = "uploads/" . $photo_name;
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
            $delete_stmt->close();
        }

        // Commit transaction
        $conn->commit();
        $success = "Event updated successfully";
        
        // Refresh event data
        $stmt = $conn->prepare("SELECT e.*, GROUP_CONCAT(ep.photo_name) as photos 
                               FROM events e 
                               LEFT JOIN event_photos ep ON e.event_id = ep.event_id 
                               WHERE e.event_id = ? 
                               GROUP BY e.event_id");
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $event = $result->fetch_assoc();
        if ($event['photos']) {
            $event['photos'] = explode(',', $event['photos']);
        } else {
            $event['photos'] = [];
        }
        $stmt->close();

    } catch (Exception $e) {
        $conn->rollback();
        error_log("Error updating event: " . $e->getMessage());
        $error = $e->getMessage();
    }
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event - Admin Panel</title>
    <link href="../assets/pics/felta-logo (2).png" rel="icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            padding: 20px;
        }
        .edit-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .form-label {
            font-weight: 500;
            color: #241e62;
        }
        .required-field::after {
            content: " *";
            color: red;
        }
        .preview-image {
            max-width: 200px;
            margin-top: 10px;
            border-radius: 8px;
            display: none;
        }
        .photo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        .photo-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
        }
        .photo-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        .photo-item .delete-checkbox {
            position: absolute;
            top: 5px;
            right: 5px;
            z-index: 1;
        }
        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <h2 class="mb-4">Edit Event</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <?php if ($event): ?>
            <form action="edit_event.php?id=<?php echo $event_id; ?>" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label required-field">Event Title</label>
                        <input type="text" name="title" id="title" class="form-control" 
                               value="<?php echo htmlspecialchars($event['title']); ?>" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="date" class="form-label required-field">Event Date</label>
                        <input type="date" name="date" id="date" class="form-control" 
                               value="<?php echo htmlspecialchars($event['date']); ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select name="category" id="category" class="form-select">
                            <option value="">Select Category</option>
                            <option value="workshop" <?php echo $event['category'] === 'workshop' ? 'selected' : ''; ?>>Workshop</option>
                            <option value="seminar" <?php echo $event['category'] === 'seminar' ? 'selected' : ''; ?>>Seminar</option>
                            <option value="competition" <?php echo $event['category'] === 'competition' ? 'selected' : ''; ?>>Competition</option>
                            <option value="training" <?php echo $event['category'] === 'training' ? 'selected' : ''; ?>>Training</option>
                            <option value="other" <?php echo $event['category'] === 'other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="upcoming" <?php echo $event['status'] === 'upcoming' ? 'selected' : ''; ?>>Upcoming</option>
                            <option value="ongoing" <?php echo $event['status'] === 'ongoing' ? 'selected' : ''; ?>>Ongoing</option>
                            <option value="completed" <?php echo $event['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                            <option value="cancelled" <?php echo $event['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" name="location" id="location" class="form-control" 
                               value="<?php echo htmlspecialchars($event['location'] ?? ''); ?>">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="capacity" class="form-label">Capacity</label>
                        <input type="number" name="capacity" id="capacity" class="form-control" 
                               value="<?php echo htmlspecialchars($event['capacity'] ?? ''); ?>" min="0">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label required-field">Description</label>
                    <textarea name="description" id="description" class="form-control" required rows="5"><?php echo htmlspecialchars($event['description']); ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="main_image" class="form-label">Main Event Image</label>
                    <?php if ($event['image']): ?>
                        <div class="mb-2">
                            <p>Current image:</p>
                            <img src="uploads/<?php echo htmlspecialchars($event['image']); ?>" 
                                 alt="Current event image" style="max-width: 200px; border-radius: 8px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="main_image" id="main_image" class="form-control" accept="image/*">
                    <small class="text-muted">Leave empty to keep the current image</small>
                    <img id="imagePreview" class="preview-image" src="#" alt="Image preview">
                </div>

                <div class="mb-3">
                    <label class="form-label">Additional Photos</label>
                    <?php if (!empty($event['photos'])): ?>
                        <div class="photo-grid">
                            <?php foreach ($event['photos'] as $photo): ?>
                                <div class="photo-item">
                                    <input type="checkbox" name="delete_photos[]" value="<?php echo htmlspecialchars($photo); ?>" 
                                           class="delete-checkbox form-check-input" title="Delete this photo">
                                    <img src="uploads/<?php echo htmlspecialchars($photo); ?>" alt="Event photo">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <small class="text-muted">Check photos you want to delete</small>
                    <?php endif; ?>
                    <input type="file" name="additional_photos[]" class="form-control mt-2" multiple accept="image/*">
                    <small class="text-muted">Select new photos to add</small>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Save Changes
                    </button>
                    <a href="admin_events.php" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-danger">Event not found</div>
            <a href="admin_events.php" class="btn btn-primary">Back to Events</a>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Image preview
        document.getElementById('main_image').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            const file = e.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });

        // Confirm before submitting if photos are selected for deletion
        document.querySelector('form').addEventListener('submit', function(e) {
            const deleteCheckboxes = document.querySelectorAll('input[name="delete_photos[]"]:checked');
            if (deleteCheckboxes.length > 0) {
                if (!confirm('Are you sure you want to delete the selected photos?')) {
                    e.preventDefault();
                }
            }
        });
    </script>
</body>
</html> 