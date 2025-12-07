<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// stop preflight OPTIONS request
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}

include "../config.php";

$targetDir = "../uploads/events/";

if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// Get text data
$eventName = $_POST["event_name"];
$description = $_POST["event_description"];

// Handle image
$imageName = null;

if (isset($_FILES["image"])) {
    $imageName = time() . "_" . basename($_FILES["image"]["name"]);
    $targetFile = $targetDir . $imageName;

    move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
}

// Insert into DB
$stmt = $conn->prepare(
    "INSERT INTO events (event_name, event_description, image) VALUES (?, ?, ?)"
);
$stmt->bind_param("sss", $eventName, $description, $imageName);
$stmt->execute();

echo "success";
?>
