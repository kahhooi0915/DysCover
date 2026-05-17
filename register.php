<?php
include "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $role = "customer";

    if (
        empty($full_name) ||
        empty($email) ||
        empty($phone) ||
        empty($address) ||
        empty($password) ||
        empty($confirm_password)
    ) {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.'); window.history.back();</script>";
        exit();
    }

    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
        exit();
    }

    if (strlen($password) < 6) {
        echo "<script>alert('Password must be at least 6 characters.'); window.history.back();</script>";
        exit();
    }

    $check_sql = "SELECT user_id FROM users WHERE email = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "s", $email);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        echo "<script>alert('Email already exists.'); window.history.back();</script>";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users 
            (full_name, email, password, phone, address, role) 
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        "ssssss",
        $full_name,
        $email,
        $hashed_password,
        $phone,
        $address,
        $role
    );

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
                alert('Registration successful. Please login.');
                window.location.href = 'login.html';
              </script>";
    } else {
        echo "<script>alert('Registration failed.'); window.history.back();</script>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>