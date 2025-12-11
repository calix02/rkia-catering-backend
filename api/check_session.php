<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

session_start();

if (isset($_SESSION['user'])) {
    echo json_encode([
        "logged_in" => true,
        "user" => $_SESSION['user']
    ]);
} else {
    echo json_encode([
        "logged_in" => false
    ]);
}
?>
