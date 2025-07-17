<?php
include 'db_connect.php';

// Read the SQL file
$sql = file_get_contents('setup_student_progress.sql');

// Execute multi query
if ($conn->multi_query($sql)) {
    do {
        // Store first result set
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->more_results() && $conn->next_result());
    
    echo "Student progress table created successfully!";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?> 