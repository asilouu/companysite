<?php
session_start();
include 'db_connect.php';

// Check if the user is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied");
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Convert to integer for security

    // Check if ID is valid
    if ($id <= 0) {
        die("Invalid User ID");
    }

    // Prepare DELETE query
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: users.php?message=User deleted successfully");
        exit();
    } else {
        die("User not deleted. Either user does not exist or query failed.");
    }

    $stmt->close();
    $conn->close();
} else {
    die("No ID provided");
}
?>
