<?php
session_start();
include("db_connect.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

if (isset($_GET["cart_id"])) {
    $user_id = $_SESSION["user_id"];
    $cart_id = intval($_GET["cart_id"]);

    $sql = "DELETE FROM cart WHERE cart_id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $cart_id, $user_id);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

header("Location: cart.php");
exit();
?>