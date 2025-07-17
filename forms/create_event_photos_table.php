<?php
require('db_connect.php');

// Create event_photos table
$query = "CREATE TABLE IF NOT EXISTS event_photos (
    photo_id INT PRIMARY KEY AUTO_INCREMENT,
    event_id INT NOT NULL,
    photo_name VARCHAR(255) NOT NULL,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(event_id) ON DELETE CASCADE
)";

if (mysqli_query($conn, $query)) {
    echo "Event photos table created successfully";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}

mysqli_close($conn);
?> 