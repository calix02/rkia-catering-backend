<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}

include "../config.php";

// Query to count approved bookings
$sql = "SELECT COUNT(*) AS total FROM bookings WHERE booking_status = 'Pending'";
$result = $conn->query($sql);

$row = $result->fetch_assoc();

echo json_encode($row);
?>
