<?php
require 'db_connect.php';

if ($conn === false) {
    echo "Database connection failed. Please check your database configuration.";
} else {
    echo "Database connection successful!<br>";
    
    // Test if we can query the database
    $result = $conn->query("SELECT DATABASE()");
    if ($result) {
        $row = $result->fetch_row();
        echo "Connected to database: " . $row[0] . "<br>";
    }
    
    // Test if events table exists
    $result = $conn->query("SHOW TABLES LIKE 'events'");
    if ($result && $result->num_rows > 0) {
        echo "Events table exists.<br>";
    } else {
        echo "Events table does not exist.<br>";
    }
    
    $conn->close();
}
?> 