<?php

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include "../config.php";

$data = json_decode(file_get_contents("php://input"), true);

// Make sure correct keys exist
$user_id     = isset($data["userID"]) ? intval($data["userID"]) : null;
$package_id  = isset($data["packageID"]) ? intval($data["packageID"]) : null;
$event_date  = $data["date"] ?? null;
$event_time  = $data["time"] ?? null;
$event_location = $data["eventLocation"] ?? null;

if (!$user_id) {
    echo json_encode(["error" => "Invalid or missing userID"]);
    exit;
}

// Check if user exists
$res = $conn->query("SELECT user_id FROM users WHERE user_id = $user_id");

if ($res->num_rows === 0) {
    echo json_encode(["error" => "User does not exist"]);
    exit;
}

$status = "Pending";

$sql = "
    INSERT INTO bookings (user_id, package_id, event_date, event_time, event_location, booking_status)
    VALUES ($user_id, $package_id, '$event_date', '$event_time', '$event_location', '$status')
";

if ($conn->query($sql)) {
    echo json_encode(["message" => "Booking added successfully"]);
} else {
    echo json_encode(["error" => $conn->error]);
}

$conn->close();

?>
