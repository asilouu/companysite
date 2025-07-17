<?php
require 'db_connect.php';

try {
    // Check if events table exists
    $result = $conn->query("SHOW TABLES LIKE 'events'");
    if ($result->num_rows === 0) {
        // Create events table if it doesn't exist
        $create_table = "CREATE TABLE events (
            event_id INT PRIMARY KEY AUTO_INCREMENT,
            title VARCHAR(100) NOT NULL,
            date DATE NOT NULL,
            event_time TIME,
            description TEXT NOT NULL,
            location VARCHAR(255),
            capacity INT,
            status ENUM('upcoming', 'ongoing', 'completed', 'cancelled') DEFAULT 'upcoming',
            category ENUM('workshop', 'seminar', 'competition', 'training', 'other') DEFAULT 'other',
            image VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        
        if (!$conn->query($create_table)) {
            throw new Exception("Error creating events table: " . $conn->error);
        }
        echo "Events table created successfully.<br>";
    }

    // Add or modify columns
    $alter_queries = [
        // Add event_time if it doesn't exist
        "ALTER TABLE events 
         ADD COLUMN IF NOT EXISTS event_time TIME AFTER date",
        
        // Add location if it doesn't exist
        "ALTER TABLE events 
         ADD COLUMN IF NOT EXISTS location VARCHAR(255) AFTER description",
        
        // Add capacity if it doesn't exist
        "ALTER TABLE events 
         ADD COLUMN IF NOT EXISTS capacity INT AFTER location",
        
        // Add status if it doesn't exist
        "ALTER TABLE events 
         ADD COLUMN IF NOT EXISTS status ENUM('upcoming', 'ongoing', 'completed', 'cancelled') 
         DEFAULT 'upcoming' AFTER capacity",
        
        // Add category if it doesn't exist
        "ALTER TABLE events 
         ADD COLUMN IF NOT EXISTS category ENUM('workshop', 'seminar', 'competition', 'training', 'other') 
         DEFAULT 'other' AFTER status",
        
        // Add created_at and updated_at if they don't exist
        "ALTER TABLE events 
         ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
         ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
        
        // Modify existing columns to ensure correct types
        "ALTER TABLE events 
         MODIFY COLUMN title VARCHAR(100) NOT NULL,
         MODIFY COLUMN date DATE NOT NULL,
         MODIFY COLUMN description TEXT NOT NULL"
    ];

    foreach ($alter_queries as $query) {
        if (!$conn->query($query)) {
            throw new Exception("Error executing query: " . $conn->error . "<br>Query: " . $query);
        }
    }

    // Create event_photos table if it doesn't exist
    $create_photos_table = "CREATE TABLE IF NOT EXISTS event_photos (
        photo_id INT PRIMARY KEY AUTO_INCREMENT,
        event_id INT NOT NULL,
        photo_name VARCHAR(255) NOT NULL,
        upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (event_id) REFERENCES events(event_id) ON DELETE CASCADE
    )";
    
    if (!$conn->query($create_photos_table)) {
        throw new Exception("Error creating event_photos table: " . $conn->error);
    }

    echo "Database tables updated successfully!";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $conn->close();
}
?> 