<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");
session_start();


if(isset($_SESSION['username'])){
    echo json_encode([
        "loggedIn" => true,
        "username" => $_SESSION['username']
    ]);
} else {
    echo json_encode(["loggedIn" => false]);
}
