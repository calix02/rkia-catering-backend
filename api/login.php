<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include "../config.php";

$data = json_decode(file_get_contents("php://input"), true);

$username = $conn->real_escape_string($data["username"]);
$password = $data["password"];

$sql = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo json_encode(["error" => "User not found"]);
    exit;
}

$user = $result->fetch_assoc();

if (password_verify($password, $user["password"])) {
    echo json_encode([
        "message" => "Login successful",
        "user_id" => $user["id"],
        "username" => $user["username"]
    ]);
} else {
    echo json_encode(["error" => "Incorrect password"]);
}
?>
