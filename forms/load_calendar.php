<?php
$conn = new mysqli("localhost", "root", "", "feltatechvoc");

$sql = "SELECT event_name, event_date, color FROM calendar";
$result = $conn->query($sql);

$events = [];
while ($row = $result->fetch_assoc()) {
  $date = $row["event_date"];
  if (!isset($events[$date])) $events[$date] = [];
  $events[$date][] = [
    "name" => $row["event_name"],
    "color" => $row["color"]
  ];
}

echo json_encode($events);
?>
