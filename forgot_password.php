<?php
date_default_timezone_set('Asia/Kuala_Lumpur');

include 'db_connect.php';
include 'includes/send_reset_email.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", time() + 3600);

        $stmt = $conn->prepare("
            INSERT INTO password_resets (email, token, expires_at, used)
            VALUES (?, ?, ?, 0)
        ");
        $stmt->bind_param("sss", $email, $token, $expires);
        $stmt->execute();

        $resetLink = "http://localhost/dyscover/reset_password.php?token=" . $token;

        if (sendResetEmail($email, $resetLink)) {
            header("Location: forgot_password.html?success=1");
            exit();
        } else {
            header("Location: forgot_password.html?error=1");
            exit();
        }

    } else {
        header("Location: forgot_password.html?success=1");
        exit();
    }

} else {
    header("Location: forgot_password.html");
    exit();
}
?>