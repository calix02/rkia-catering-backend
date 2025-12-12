<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Handle OPTIONS preflight
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}

include "../config.php";

$data = json_decode(file_get_contents("php://input"), true);

$id = $data["id"] ?? null;
$action = $data["action"] ?? null;
$role = $data["role"] ?? null;

if (!$id || !$action) {
    echo json_encode(["error" => "Invalid request"]);
    exit;
}

/* ---------------- COMPLETE ---------------- */
if ($action === "complete") {
    $sql = "UPDATE bookings SET booking_status='Completed' WHERE booking_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    echo $stmt->execute()
        ? json_encode(["message" => "Booking Completed"])
        : json_encode(["error" => "Failed to complete"]);
    exit;
}

/* ---------------- APPROVE ---------------- */
if ($action === "approve") {
    $sql = "UPDATE bookings SET booking_status='Approved' WHERE booking_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {

        // Insert payment (no extra JSON output!)
        $sql1 = "INSERT INTO payments (booking_id, amount, payment_status) VALUES (?, 0, 'Unpaid')";
        $stmt2 = $conn->prepare($sql1);
        $stmt2->bind_param("i", $id);
        $stmt2->execute();

        echo json_encode(["message" => "Booking Approved"]);
    } else {
        echo json_encode(["error" => "Failed to approve"]);
    }
    exit;
}

/* ---------------- CANCEL ---------------- */
if ($action === "cancel") {
    $sql = "UPDATE bookings SET booking_status='Cancelled' WHERE booking_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    echo $stmt->execute()
        ? json_encode(["message" => "Booking Cancelled"])
        : json_encode(["error" => "Failed to cancel"]);
    exit;
}

/* ---------------- DELETE BOOKING ---------------- */
if ($action === "delete") {
    $sql = "DELETE FROM bookings WHERE booking_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    echo $stmt->execute()
        ? json_encode(["message" => "Booking Deleted"])
        : json_encode(["error" => "Failed to delete"]);
    exit;
}

/* ---------------- DELETE ACCOUNT ---------------- */
if ($action === "delete-account") {
    $sql = "DELETE FROM users WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    echo $stmt->execute()
        ? json_encode(["message" => "Account Deleted"])
        : json_encode(["error" => "Failed to delete"]);
    exit;
}

/* ---------------- UPDATE ACCOUNT ---------------- */
if ($action === "update-account") {

    if (!$role) {
        echo json_encode(["error" => "Missing role"]);
        exit;
    }

    $sql = "UPDATE users SET role=? WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $role, $id);

    echo $stmt->execute()
        ? json_encode(["message" => "Role Updated"])
        : json_encode(["error" => "Failed to update"]);
    exit;
}

echo json_encode(["error" => "Invalid action"]);
?>
