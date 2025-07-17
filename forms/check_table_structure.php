<?php
require 'db_connect.php';

try {
    // Check if events table exists
    $result = $conn->query("SHOW TABLES LIKE 'events'");
    if ($result->num_rows === 0) {
        echo "Events table does not exist! Please run update_events_table.php first.<br>";
        exit;
    }

    // Get table structure
    $result = $conn->query("DESCRIBE events");
    if (!$result) {
        throw new Exception("Error getting table structure: " . $conn->error);
    }

    echo "<h3>Current Events Table Structure:</h3>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Default'] ?? 'NULL') . "</td>";
        echo "<td>" . htmlspecialchars($row['Extra']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $conn->close();
}
?> 