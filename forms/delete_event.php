<?php
session_start();
require 'db_connect.php'; 

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $event_id = $_GET['id'];
    
    // First, get all photos associated with this event
    $photos_query = "SELECT photo_name FROM event_photos WHERE event_id = ?";
    $photos_stmt = mysqli_prepare($conn, $photos_query);
    mysqli_stmt_bind_param($photos_stmt, "i", $event_id);
    mysqli_stmt_execute($photos_stmt);
    $photos_result = mysqli_stmt_get_result($photos_stmt);
    
    // Delete the photo files
    while ($photo = mysqli_fetch_assoc($photos_result)) {
        $file_path = "uploads/" . $photo['photo_name'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    
    // Get and delete the main image
    $main_image_query = "SELECT image FROM events WHERE event_id = ?";
    $main_image_stmt = mysqli_prepare($conn, $main_image_query);
    mysqli_stmt_bind_param($main_image_stmt, "i", $event_id);
    mysqli_stmt_execute($main_image_stmt);
    $main_image_result = mysqli_stmt_get_result($main_image_stmt);
    $main_image = mysqli_fetch_assoc($main_image_result);
    
    if ($main_image && $main_image['image']) {
        $file_path = "uploads/" . $main_image['image'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    
    // Delete the event (this will cascade delete the photos from event_photos table)
    $delete_query = "DELETE FROM events WHERE event_id = ?";
    $delete_stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($delete_stmt, "i", $event_id);
    
    if (mysqli_stmt_execute($delete_stmt)) {
        header("Location: admin_events.php");
    } else {
        echo "Error deleting event: " . mysqli_error($conn);
    }
    
    mysqli_stmt_close($delete_stmt);
} else {
    header("Location: admin_events.php");
}

mysqli_close($conn);
?>
