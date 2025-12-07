<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}

include "../config.php";

$data = json_decode(file_get_contents("php://input"), true);
$event_id = $data["eventId"];


$sql = "
    SELECT 
        e.event_name,
        e.image,
        p.package_name,
        p.description,
        p.price
    FROM packages p
    INNER JOIN events e
        ON p.event_id = e.event_id
    WHERE p.event_id = '$event_id'
";

$result = $conn->query($sql);
$packages = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $packages[] = $row;
    }
}

echo json_encode($packages);
?>
