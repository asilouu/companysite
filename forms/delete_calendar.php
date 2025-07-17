<?php
header('Content-Type: application/json');
require_once 'db_connect.php';

// Get the data from the POST request
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['date']) || !isset($data['eventName'])) {
    echo json_encode(["success" => false, "error" => "Invalid input"]);
    exit;
}

$date = $conn->real_escape_string($data['date']);
$eventName = $conn->real_escape_string($data['eventName']);

// Delete the event
$sql = "DELETE FROM calendar WHERE event_date = '$date' AND event_name = '$eventName'";

if ($conn->query($sql)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $conn->error]);
}

$conn->close();
?>
