<?php

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include "../config.php";

$sql = "
    SELECT 
        b.booking_id,
        b.event_date,
        b.event_time,
        b.event_location,
        b.booking_status,
        u.full_name,

        p.package_name,
        e.event_name

    FROM bookings b
    INNER JOIN users u ON b.user_id = u.user_id
    INNER JOIN packages p ON b.package_id = p.package_id
    INNER JOIN events e ON p.event_id = e.event_id
    ORDER BY b.booking_id DESC
";

$result = $conn->query($sql);
$bookings = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
}

echo json_encode($bookings);

?>
