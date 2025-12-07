<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

session_start();
include "../config.php";

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);
$username = $data["username"] ?? "";
$password = $data["password"] ?? "";

// Query user
$sql = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo json_encode(["error" => "User not found"]);
    exit;
}

$user = $result->fetch_assoc();

// Check password
if (password_verify($password, $user["password"])) {
    $_SESSION['user'] = [
        "user_id" => $user['user_id'],
        "full_name" => $user['full_name'],
        "role" => $user['role'],
    ];

    echo json_encode([
        "success" => true,
        "user" => $_SESSION["user"]
    ]);
} else {
    echo json_encode(["error" => "Incorrect password"]);
}
?>
