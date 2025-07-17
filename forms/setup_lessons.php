<?php
include 'db_connect.php';

// Read the SQL file
$sql = file_get_contents('setup_lessons.sql');

// Execute multi query
if ($conn->multi_query($sql)) {
    do {
        // Store first result set
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->more_results() && $conn->next_result());
    
    echo "Lessons and progress tables created successfully!";
} else {
    echo "Error creating tables: " . $conn->error;
}

$conn->close();
?> 