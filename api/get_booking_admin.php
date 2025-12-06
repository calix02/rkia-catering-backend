
<?php

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include "../config.php";

$data = json_decode(file_get_contents("php://input"), true);
$user_id = $conn->real_escape_string($data["user_id"]);

$sql = "SELECT b.booking_id, b.event_date, b.event_time, b.status, b.notes, p.package_name, e.event_name
    FROM bookings b
    JOIN packages p ON b.package_id = p.package_id
    JOIN events e ON p.event_id = e.event_id
    WHERE b.user_id = '$user_id'
    ORDER BY b.booking_id DESC
";

$result = $conn->query($sql);
$bookings = [];

while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

echo json_encode($bookings);

?>