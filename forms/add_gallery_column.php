<?php
require('db_connect.php');

// Add gallery_images column to events table
$alter_table = "ALTER TABLE events ADD COLUMN gallery_images JSON NULL AFTER image";

if (mysqli_query($conn, $alter_table)) {
    echo "Gallery images column added successfully";
} else {
    echo "Error adding column: " . mysqli_error($conn);
}

mysqli_close($conn);
?> 