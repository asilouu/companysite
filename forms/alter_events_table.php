<?php
require 'db_connect.php';

try {
    // Add new columns if they don't exist
    $alter_queries = [
        "ALTER TABLE events 
         ADD COLUMN IF NOT EXISTS event_time TIME AFTER event_date,
         ADD COLUMN IF NOT EXISTS location VARCHAR(255) AFTER description,
         ADD COLUMN IF NOT EXISTS capacity INT AFTER location,
         ADD COLUMN IF NOT EXISTS status ENUM('upcoming', 'ongoing', 'completed', 'cancelled') 
             DEFAULT 'upcoming' AFTER capacity,
         ADD COLUMN IF NOT EXISTS category ENUM('workshop', 'seminar', 'competition', 'training', 'other') 
             DEFAULT 'other' AFTER status,
         ADD COLUMN IF NOT EXISTS image_path VARCHAR(255) AFTER category,
         MODIFY COLUMN title VARCHAR(100) NOT NULL,
         MODIFY COLUMN event_date DATE NOT NULL,
         MODIFY COLUMN description TEXT NOT NULL"
    ];

    foreach ($alter_queries as $query) {
        if (!$conn->query($query)) {
            throw new Exception("Error altering table: " . $conn->error);
        }
    }

    echo "Events table updated successfully!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $conn->close();
}
?> 