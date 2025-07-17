<?php
require 'db_connect.php';

try {
    // Check if events table exists
    $result = $conn->query("SHOW TABLES LIKE 'events'");
    if ($result->num_rows === 0) {
        throw new Exception("Events table does not exist. Please run alter_events_table.php first.");
    }

    // Check table structure
    $result = $conn->query("DESCRIBE events");
    if ($result === false) {
        throw new Exception("Error checking table structure: " . $conn->error);
    }

    $required_columns = [
        'id' => 'int',
        'title' => 'varchar(100)',
        'event_date' => 'date',
        'event_time' => 'time',
        'description' => 'text',
        'location' => 'varchar(255)',
        'capacity' => 'int',
        'status' => "enum('upcoming','ongoing','completed','cancelled')",
        'category' => "enum('workshop','seminar','competition','training','other')",
        'image_path' => 'varchar(255)'
    ];

    $existing_columns = [];
    while ($row = $result->fetch_assoc()) {
        $existing_columns[$row['Field']] = $row['Type'];
    }

    $missing_columns = [];
    foreach ($required_columns as $column => $type) {
        if (!isset($existing_columns[$column])) {
            $missing_columns[] = $column;
        }
    }

    if (!empty($missing_columns)) {
        throw new Exception("Missing columns in events table: " . implode(', ', $missing_columns) . 
                          ". Please run alter_events_table.php to update the table structure.");
    }

    echo "Events table exists and has the correct structure.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $conn->close();
}
?> 