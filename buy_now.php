<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: checkout.php");
    exit();
} else {
    $_SESSION["redirect_after_login"] = "checkout.php";
    header("Location: login.html");
    exit();
}
?>
