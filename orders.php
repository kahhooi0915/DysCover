<?php
session_start();
include("db_connect.php");

header("Content-Type: application/json");

if (!isset($_SESSION["user_id"])) {
    echo json_encode([
        "status" => "not_logged_in"
    ]);
    exit();
}

$user_id = $_SESSION["user_id"];

$sql = "SELECT order_id, order_date, total_amount, shipping_address, payment_status, order_status
        FROM orders
        WHERE user_id = ?
        ORDER BY order_date DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$orders = [];

while ($row = mysqli_fetch_assoc($result)) {
    $orders[] = $row;
}

echo json_encode([
    "status" => "success",
    "orders" => $orders
]);

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>