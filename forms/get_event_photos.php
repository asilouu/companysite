<?php
session_start();
require('db_connect.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

if (!isset($_GET['event_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Event ID is required']);
    exit();
}

$event_id = $_GET['event_id'];

$query = "SELECT photo_id, photo_name FROM event_photos WHERE event_id = ? ORDER BY upload_date DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $event_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$photos = [];
while ($row = mysqli_fetch_assoc($result)) {
    $photos[] = [
        'photo_id' => $row['photo_id'],
        'photo_name' => $row['photo_name']
    ];
}

header('Content-Type: application/json');
echo json_encode($photos);

mysqli_stmt_close($stmt);
mysqli_close($conn);
?> 