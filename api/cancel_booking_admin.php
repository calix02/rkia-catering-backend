<?php

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include "../config.php";

$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
if (!isset($data["booking_id"]) || !isset($data["user_id"])) {
    echo json_encode(["error" => "Required fields missing"]);
    exit;
}

$booking_id = $conn->real_escape_string($data["booking_id"]);
$user_id    = $conn->real_escape_string($data["user_id"]);

// Check if booking exists & belongs to the user
$sql = "
    SELECT booking_status 
    FROM bookings 
    WHERE booking_id = '$booking_id' 
      AND user_id = '$user_id'
";

$result = $conn->query($sql);

if ($result->num_rows === 0) {
    echo json_encode(["error" => "Booking not found or not associated with this user."]);
    exit;
}

$row = $result->fetch_assoc();

// Only Pending bookings can be cancelled
if ($row["booking_status"] !== "Pending") {
    echo json_encode(["error" => "Only pending bookings can be cancelled."]);
    exit;
}

// Cancel booking
$sqlCancel = "
    UPDATE bookings 
    SET booking_status = 'Cancelled' 
    WHERE booking_id = '$booking_id'
";

if ($conn->query($sqlCancel)) {
    echo json_encode(["message" => "Your booking has been cancelled."]);
} else {
    echo json_encode(["error" => "Failed to cancel booking."]);
}

?>