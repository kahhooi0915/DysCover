<?php
include 'db_connect.php';

$status = "error";
$message = "Something went wrong.";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        $message = "Passwords do not match.";
    } else {
        $stmt = $conn->prepare("
            SELECT * FROM password_resets 
            WHERE token = ? 
            AND used = 0 
            AND expires_at > NOW()
        ");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows !== 1) {
            $message = "Invalid or expired reset link.";
        } else {
            $reset = $result->fetch_assoc();
            $email = $reset['email'];

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $hashedPassword, $email);

            if ($stmt->execute()) {
                $stmt = $conn->prepare("UPDATE password_resets SET used = 1 WHERE token = ?");
                $stmt->bind_param("s", $token);
                $stmt->execute();

                $status = "success";
                $message = "Your password has been updated successfully.";
            } else {
                $message = "Failed to update password.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DysCover | Password Updated</title>
    <link rel="stylesheet" href="css/reset_password.css?v=2">
</head>
<body>

<header class="navbar">
    <div class="logo">DysCover</div>
    <nav>
        <a href="product.php">Product</a>
        <a href="login.html" class="active">Login</a>
    </nav>
</header>

<main class="page">
    <div class="overlay"></div>

    <section class="reset-card">
        <div class="icon <?php echo $status === 'success' ? 'success-icon' : 'error-icon'; ?>">
            <?php echo $status === 'success' ? '✓' : '!'; ?>
        </div>

        <h1>
            <?php echo $status === 'success' ? 'Password<br>Updated' : 'Update<br>Failed'; ?>
        </h1>

        <p><?php echo htmlspecialchars($message); ?></p>

        <?php if ($status === 'success'): ?>
            <a href="login.html" class="button-link">Back to Login</a>
        <?php else: ?>
            <a href="forgot_password.html" class="button-link">Request New Link</a>
        <?php endif; ?>
    </section>
</main>

<footer>
    <span>© DysCover Lab</span>
    <span>Secure Password Reset</span>
</footer>

</body>
</html>
