<?php
include 'db_connect.php';

$token = $_GET['token'] ?? '';

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
    die("Invalid or expired reset link.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DysCover | Reset Password</title>
    <link rel="stylesheet" href="css/reset_password.css">
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
        <div class="icon">🔒</div>

        <h1>Create New<br>Password</h1>

        <p>
            Enter your new password below. Make sure it is secure and easy for you to remember.
        </p>

        <form action="update_password.php" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

            <label>New Password</label>
            <div class="input-box">
                <span>●</span>
                <input type="password" name="password" placeholder="Enter new password" required>
            </div>

            <label>Confirm Password</label>
            <div class="input-box">
                <span>●</span>
                <input type="password" name="confirm_password" placeholder="Confirm new password" required>
            </div>

            <button type="submit">Update Password</button>
        </form>

        <div class="divider"></div>

        <a href="login.html" class="back-link">← Back to Login</a>
    </section>
</main>

<footer>
    <span>© DysCover Lab</span>
    <span>Secure Password Reset</span>
</footer>

</body>
</html>