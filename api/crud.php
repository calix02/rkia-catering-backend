<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include "../config.php";

$data = json_decode(file_get_contents("php://input"), true);

$id = $conn->real_escape_string($data["id"]);
$action = $conn->real_escape_string($data["action"]);
$role = $conn->real_escape_string($data["role"]);

if ($action === "complete") {
    $sql = "UPDATE bookings SET booking_status = 'Completed' WHERE booking_id = '$id'";
    echo $conn->query($sql)
        ? json_encode(["message" => "Booking Completed"])
        : json_encode(["error" => "Failed to complete"]);
    exit;
}

if ($action === "approve") {
    $sql = "UPDATE bookings SET booking_status = 'Approved' WHERE booking_id = '$id'";
    echo $conn->query($sql)
        ? json_encode(["message" => "Booking Approved"])
        : json_encode(["error" => "Failed to approve"]);
    exit;
}

if ($action === "cancel") {
    $sql = "UPDATE bookings SET booking_status = 'Cancelled' WHERE booking_id = '$id'";
    echo $conn->query($sql)
        ? json_encode(["message" => "Booking cancelled"])
        : json_encode(["error" => "Failed to cancel"]);
    exit;
}

if ($action === "delete") {
    $sql = "DELETE FROM bookings WHERE booking_id = '$id'";
    echo $conn->query($sql)
        ? json_encode(["message" => "Booking deleted"])
        : json_encode(["error" => "Failed to delete"]);
    exit;
}
if ($action === "delete-account") {
    $sql = "DELETE FROM users WHERE user_id = '$id'";
    echo $conn->query($sql)
        ? json_encode(["message" => "Booking deleted"])
        : json_encode(["error" => "Failed to delete"]);
    exit;
}
if ($action === "update-account") {
    $sql = "UPDATE users SET role = '$role' WHERE user_id = '$id'";
    echo $conn->query($sql)
        ? json_encode(["message" => "Update Role"])
        : json_encode(["error" => "Failed to update"]);
    exit;
}



echo json_encode(["error" => "Invalid action"]);
?>