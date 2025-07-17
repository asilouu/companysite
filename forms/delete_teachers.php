<?php
session_start();
require('db_connect.php');

if (!isset($_GET['id'])) {
    header('Location: admin_teachers.php'); // Redirect if no ID is provided
    exit();
}

$id = $_GET['id'];

// Fetch the teacher's image to delete it from the server as well
$query = "SELECT image FROM teachers WHERE id = $id";
$result = mysqli_query($conn, $query);
$teacher = mysqli_fetch_assoc($result);

// Delete teacher record from the database
$query = "DELETE FROM teachers WHERE id = $id";
if (mysqli_query($conn, $query)) {
    // Delete the image file from the server
    unlink("uploads/" . $teacher['image']);
    header('Location: admin_teachers.php'); // Redirect back to teacher list after successful deletion
    exit();
} else {
    echo "Error deleting teacher: " . mysqli_error($conn);
}
?>
