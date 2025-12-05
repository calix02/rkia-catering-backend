<?php

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include "../config.php";

$data = json_decode(file_get_contents("php://input"), true);

$package_id = $data["packageID"] ?? '';
$user_id = $data["userID"] ?? '';
$event_date = $data["date"] ?? '';
$event_location = $data["location"] ?? '';
$event_time = $data["time"] ?? '';

$package_id   = $conn->real_escape_string($package_id);
$user_id      = $conn->real_escape_string($user_id);
$event_date   = $conn->real_escape_string($event_date);
$event_location   = $conn->real_escape_string($event_location);
$event_time = $conn->real_escape_string($event_time);

$status = "Pending";

$sql = "
    INSERT INTO bookings (package_id, user_id, event_date, event_time, event_location, booking_status)
    VALUES ('$package_id', '$user_id', '$event_date', '$event_time', '$event_location', '$status')
";

if ($conn->query($sql)) {
    echo json_encode(["message" => "Booking added successfully"]);
} else {
    echo json_encode(["error" => $conn->error]);
}
$conn->close();

?>