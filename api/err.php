<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include "../config.php";

$data = json_decode(file_get_contents("php://input"), true);

$booking_id = $conn->real_escape_string($data["booking_id"]);
$action = $conn->real_escape_string($data["action"]);


if ($action === "cancel") {
    $sql = "UPDATE bookings SET booking_status = 'Cancelled' WHERE booking_id = '$booking_id'";
    echo $conn->query($sql)
        ? json_encode(["message" => "Booking cancelled"])
        : json_encode(["error" => "Failed to cancel"]);
    exit;
}

if ($action === "delete") {
    $sql = "DELETE FROM bookings WHERE booking_id = '$booking_id'";
    echo $conn->query($sql)
        ? json_encode(["message" => "Booking deleted"])
        : json_encode(["error" => "Failed to delete"]);
    exit;
}

echo json_encode(["error" => "Invalid action"]);
?>