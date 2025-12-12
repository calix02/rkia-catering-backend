<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}

include "../config.php"; // your DB connection

$year = isset($_GET["year"]) ? intval($_GET["year"]) : date("Y");

// Prepare an array for all 12 months (default zero)
$monthlyTotals = array_fill(0, 12, 0);

$sql = "
    SELECT 
        MONTH(event_date) AS month,
        COUNT(*) AS total
    FROM bookings
    WHERE YEAR(event_date) = ?
      AND booking_status = 'Completed'
    GROUP BY MONTH(event_date)
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $year);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $monthIndex = intval($row["month"]) - 1; // convert MySQL month (1-12) to array index (0-11)
    $monthlyTotals[$monthIndex] = intval($row["total"]);
}

echo json_encode([
    "year" => $year,
    "monthly_totals" => $monthlyTotals
]);
?>
