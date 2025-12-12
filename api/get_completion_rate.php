<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}
include "../config.php";

$totalQuery = $conn->query("SELECT COUNT(*) AS total FROM bookings");
$totalRow = $totalQuery->fetch_assoc();
$total = $totalRow["total"];

$completedQuery = $conn->query("SELECT COUNT(*) AS completed FROM bookings WHERE booking_status = 'Completed'");
$completedRow = $completedQuery->fetch_assoc();
$completed = $completedRow["completed"];

$pendingQuery = $conn->query("SELECT COUNT(*) AS pending FROM bookings WHERE booking_status = 'Pending'");
$pendingRow = $pendingQuery->fetch_assoc();
$pending = $pendingRow["pending"];

$cancelQuery = $conn->query("SELECT COUNT(*) AS cancelled FROM bookings WHERE booking_status = 'Cancelled'");
$cancelRow = $cancelQuery->fetch_assoc();
$cancelled = $cancelRow["cancelled"];

$completion_rate = ($total > 0) 
    ? round(($completed / $total) * 100, 2)
    : 0;

echo json_encode([
    "total_bookings" => $total,
    "completed" => $completed,
    "pending" => $pending,
    "cancelled" => $cancelled,
    "completion_rate" => $completion_rate
]);

?>
