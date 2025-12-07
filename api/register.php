<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include "../config.php";

$data = json_decode(file_get_contents("php://input"), true);

$name = $data['name'] ?? '';
$phone = $data['phone'] ?? '';
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';
$role = $data['role'] ?? 'user';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);


$name = $conn->real_escape_string($name);
$phone = $conn->real_escape_string($phone);
$username = $conn->real_escape_string($username);
$role = $conn->real_escape_string($role);


$result = $conn->query("SELECT * FROM users WHERE username='$username'");
if ($result->num_rows > 0) {
    echo json_encode(["error" => "Username already exists"]);
    exit;
}

$sql = "INSERT INTO users (full_name, phone, username, password, role) 
        VALUES ('$name', '$phone', '$username', '$hashedPassword', '$role')";

if ($conn->query($sql)) {
    echo json_encode(["message" => "User registered successfully"]);
} else {
    echo json_encode(["error" => $conn->error]);
}
$conn->close();

?>
