<?php

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include "../config.php";

// SQL to join bookings with users, packages, events
$sql = "
    SELECT 
        b.booking_id,
        b.event_date,
        b.event_time,
        b.booking_status,
        b.event_location,
        u.full_name,
        p.package_name,
        e.event_id,
        e.event_name

    FROM bookings b
    INNER JOIN users u ON b.user_id = u.user_id
    INNER JOIN packages p ON b.package_id = p.package_id
    INNER JOIN events e ON p.event_id = e.event_id
    WHERE b.booking_status = 'Completed' 
    OR b.booking_status = 'Cancelled'
   
    ORDER BY b.event_date DESC
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