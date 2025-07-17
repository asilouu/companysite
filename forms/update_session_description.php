<?php
session_start();
include 'db_connect.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['session_id']) && isset($_POST['description'])) {
    $session_id = $_POST['session_id'];
    $description = $_POST['description'];
    
    $update_query = "UPDATE student_progress SET description = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Error preparing statement: ' . $conn->error]);
        exit();
    }
    
    if (!$stmt->bind_param("si", $description, $session_id)) {
        echo json_encode(['success' => false, 'message' => 'Error binding parameters: ' . $stmt->error]);
        exit();
    }
    
    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'message' => 'Error executing statement: ' . $stmt->error]);
        exit();
    }
    
    $stmt->close();
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?> 