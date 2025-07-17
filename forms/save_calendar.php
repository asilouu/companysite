<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);


// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Connect to DB
$conn = new mysqli("localhost", "root", "", "feltatechvoc");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Check if event_detail column exists, if not add it
$checkColumn = "SHOW COLUMNS FROM calendar LIKE 'event_detail'";
$result = $conn->query($checkColumn);
if ($result->num_rows == 0) {
    $addColumn = "ALTER TABLE calendar ADD COLUMN event_detail TEXT";
    if (!$conn->query($addColumn)) {
        echo json_encode(["success" => false, "error" => "Failed to add event_detail column: " . $conn->error]);
        exit;
    }
}

// Get raw POST data
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data["name"]) || !isset($data["date"]) || !isset($data["color"])) {
    echo json_encode(["success" => false, "error" => "Invalid input"]);
    exit;
}

$name = $conn->real_escape_string($data["name"]);
$date = $conn->real_escape_string($data["date"]);
$color = $conn->real_escape_string($data["color"]);
$details = isset($data["details"]) ? $conn->real_escape_string($data["details"]) : "";

$sql = "INSERT INTO calendar (event_name, event_date, event_color, event_detail) VALUES ('$name', '$date', '$color', '$details')";

if ($conn->query($sql)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $conn->error]);
}
?>
