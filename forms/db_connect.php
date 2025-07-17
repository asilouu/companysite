<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "feltatechvoc";

// Create connection
$conn = @new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    $conn = false;
}
?>
