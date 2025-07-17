<?php
session_start();
require 'db_connect.php'; 

// Ensure only admins access this page
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$title = $description = $event_date = $event_time = $location = $capacity = $status = $category = "";
$event_id = "";

// If editing, fetch existing event data
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];
    $result = $conn->query("SELECT * FROM events WHERE id = $event_id");
    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
        $title = $event['title'];
        $description = $event['description'];
        $event_date = $event['event_date'];
        $event_time = $event['event_time'];
        $location = $event['location'];
        $capacity = $event['capacity'];
        $status = $event['status'];
        $category = $event['category'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $event_id ? "Edit" : "Add"; ?> Event</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .form-title {
            color: #241e62;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #241e62;
        }
        .required-field::after {
            content: " *";
            color: red;
        }
        .preview-image {
            max-width: 200px;
            margin-top: 10px;
            display: none;
        }
    </style>
</head>
<body class="bg-light">

<div class="container">
    <div class="form-container">
        <h2 class="form-title"><?php echo $event_id ? "Edit" : "Add"; ?> Event</h2>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php 
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <form action="save_event.php" method="POST" enctype="multipart/form-data" id="eventForm">
            <input type="hidden" name="id" value="<?php echo $event_id; ?>">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="title" class="form-label required-field">Event Title</label>
                    <input type="text" name="title" id="title" class="form-control" 
                           value="<?php echo htmlspecialchars($title); ?>" required
                           minlength="3" maxlength="100">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="category" class="form-label required-field">Event Category</label>
                    <select name="category" id="category" class="form-select" required>
                        <option value="">Select Category</option>
                        <option value="workshop" <?php echo $category === 'workshop' ? 'selected' : ''; ?>>Workshop</option>
                        <option value="seminar" <?php echo $category === 'seminar' ? 'selected' : ''; ?>>Seminar</option>
                        <option value="competition" <?php echo $category === 'competition' ? 'selected' : ''; ?>>Competition</option>
                        <option value="training" <?php echo $category === 'training' ? 'selected' : ''; ?>>Training</option>
                        <option value="other" <?php echo $category === 'other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="event_date" class="form-label required-field">Event Date</label>
                    <input type="date" name="event_date" id="event_date" class="form-control" 
                           value="<?php echo $event_date; ?>" required
                           min="<?php echo date('Y-m-d'); ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="event_time" class="form-label required-field">Event Time</label>
                    <input type="time" name="event_time" id="event_time" class="form-control" 
                           value="<?php echo $event_time; ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="location" class="form-label required-field">Location</label>
                    <input type="text" name="location" id="location" class="form-control" 
                           value="<?php echo htmlspecialchars($location); ?>" required
                           placeholder="Enter event location">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="capacity" class="form-label">Capacity</label>
                    <input type="number" name="capacity" id="capacity" class="form-control" 
                           value="<?php echo htmlspecialchars($capacity); ?>"
                           min="1" placeholder="Enter maximum participants">
                </div>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label required-field">Event Status</label>
                <select name="status" id="status" class="form-select" required>
                    <option value="upcoming" <?php echo $status === 'upcoming' ? 'selected' : ''; ?>>Upcoming</option>
                    <option value="ongoing" <?php echo $status === 'ongoing' ? 'selected' : ''; ?>>Ongoing</option>
                    <option value="completed" <?php echo $status === 'completed' ? 'selected' : ''; ?>>Completed</option>
                    <option value="cancelled" <?php echo $status === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label required-field">Description</label>
                <textarea name="description" id="description" class="form-control" required><?php echo htmlspecialchars($description); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="event_image" class="form-label">Event Image</label>
                <input type="file" name="event_image" id="event_image" class="form-control" 
                       accept="image/*" onchange="previewImage(this)">
                <img id="imagePreview" class="preview-image" src="#" alt="Event image preview">
                <?php if ($event_id && isset($event['image_path'])): ?>
                    <div class="mt-2">
                        <p>Current image:</p>
                        <img src="<?php echo htmlspecialchars($event['image_path']); ?>" 
                             alt="Current event image" style="max-width: 200px;">
                    </div>
                <?php endif; ?>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><?php echo $event_id ? "Update" : "Add"; ?> Event</button>
                <a href="admin_events.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
$(document).ready(function() {
    $('#description').summernote({
        height: 200,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    // Form validation
    $('#eventForm').on('submit', function(e) {
        const title = $('#title').val().trim();
        const description = $('#description').val().trim();
        
        if (title.length < 3) {
            e.preventDefault();
            alert('Event title must be at least 3 characters long');
            return false;
        }
        
        if (description.length < 10) {
            e.preventDefault();
            alert('Event description must be at least 10 characters long');
            return false;
        }
    });
});

function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

</body>
</html>
