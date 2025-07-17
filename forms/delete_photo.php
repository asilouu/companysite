<?php
session_start();
require('db_connect.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

if (!isset($_POST['photo_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Photo ID is required']);
    exit();
}

$photo_id = $_POST['photo_id'];

// First get the photo filename
$query = "SELECT photo_name FROM event_photos WHERE photo_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $photo_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $photo_name = $row['photo_name'];
    $file_path = "uploads/" . $photo_name;
    
    // Delete the file if it exists
    if (file_exists($file_path)) {
        unlink($file_path);
    }
    
    // Delete the database record
    $delete_query = "DELETE FROM event_photos WHERE photo_id = ?";
    $delete_stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($delete_stmt, "i", $photo_id);
    
    if (mysqli_stmt_execute($delete_stmt)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to delete photo record']);
    }
    
    mysqli_stmt_close($delete_stmt);
} else {
    echo json_encode(['success' => false, 'error' => 'Photo not found']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?> 