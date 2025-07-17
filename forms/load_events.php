<?php
header('Content-Type: application/json');
require_once 'db_connect.php';

$sql = "SELECT event_name, event_date, color, event_detail FROM calendar";
$result = $conn->query($sql);

$events = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $date = $row["event_date"];
        if (!isset($events[$date])) {
            $events[$date] = [];
        }
        $events[$date][] = [
            "name" => $row["event_name"],
            "color" => $row["color"],
            "details" => $row["event_detail"]
        ];
    }
}

echo json_encode($events);
$conn->close();
?> 