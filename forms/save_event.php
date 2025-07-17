<?php
session_start();
require 'db_connect.php'; 

// Ensure only admins can access
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Function to validate and sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to handle image upload
function handle_image_upload($file, $event_id = null) {
    $target_dir = "../assets/uploads/events/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Generate unique filename
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $new_filename = ($event_id ? $event_id : uniqid()) . '_' . time() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;

    // Validate file
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($file_extension, $allowed_types)) {
        throw new Exception("Only JPG, JPEG, PNG & GIF files are allowed.");
    }

    if ($file["size"] > 5000000) { // 5MB limit
        throw new Exception("File is too large. Maximum size is 5MB.");
    }

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return "assets/uploads/events/" . $new_filename;
    } else {
        throw new Exception("Failed to upload image.");
    }
}

try {
    // Get and validate event details from form
    $id = $_POST['id'] ?? "";
    $title = sanitize_input($_POST['title']);
    $event_date = sanitize_input($_POST['event_date']);
    $event_time = sanitize_input($_POST['event_time']);
    $description = $_POST['description']; // Don't sanitize rich text content
    $location = sanitize_input($_POST['location']);
    $capacity = filter_var($_POST['capacity'], FILTER_VALIDATE_INT);
    $status = sanitize_input($_POST['status']);
    $category = sanitize_input($_POST['category']);

    // Validate required fields
    if (empty($title) || empty($event_date) || empty($event_time) || empty($description) || 
        empty($location) || empty($status) || empty($category)) {
        throw new Exception("All required fields must be filled out.");
    }

    // Validate date
    $event_datetime = strtotime($event_date . ' ' . $event_time);
    if ($event_datetime === false) {
        throw new Exception("Invalid date or time format.");
    }

    // Handle image upload if present
    $image_path = null;
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] === UPLOAD_ERR_OK) {
        $image_path = handle_image_upload($_FILES['event_image'], $id);
    }

    // Prepare the SQL statement based on whether we're updating or inserting
    if ($id) {
        // Update existing event
        $sql = "UPDATE events SET 
                title = ?, 
                event_date = ?, 
                event_time = ?,
                description = ?, 
                location = ?,
                capacity = ?,
                status = ?,
                category = ?";
        
        $params = [$title, $event_date, $event_time, $description, $location, $capacity, $status, $category];
        $types = "ssssssss";

        // Add image path to update if new image was uploaded
        if ($image_path) {
            $sql .= ", image_path = ?";
            $params[] = $image_path;
            $types .= "s";
        }

        $sql .= " WHERE id = ?";
        $params[] = $id;
        $types .= "i";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
    } else {
        // Insert new event
        $sql = "INSERT INTO events (title, event_date, event_time, description, location, capacity, status, category, image_path) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssss", 
            $title, $event_date, $event_time, $description, $location, $capacity, $status, $category, $image_path);
    }

    if (!$stmt->execute()) {
        throw new Exception("Database error: " . $stmt->error);
    }

    // If this was a new event and we uploaded an image, update the image path with the event ID
    if (!$id && $image_path) {
        $new_event_id = $conn->insert_id;
        $new_image_path = handle_image_upload($_FILES['event_image'], $new_event_id);
        $update_stmt = $conn->prepare("UPDATE events SET image_path = ? WHERE id = ?");
        $update_stmt->bind_param("si", $new_image_path, $new_event_id);
        $update_stmt->execute();
        $update_stmt->close();
    }

    $_SESSION['success'] = "Event " . ($id ? "updated" : "added") . " successfully!";
    header("Location: admin_events.php");
    exit();

} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: event_form.php" . ($id ? "?id=$id" : ""));
    exit();
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}
?>
