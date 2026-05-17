<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.html");
    exit();
}

if ($_SESSION["role"] !== "customer") {
    header("Location: ../login.html");
    exit();
}

$full_name = $_SESSION["full_name"];
$email = $_SESSION["email"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DysCover | Member Dashboard</title>
    <link rel="stylesheet" href="../css/member_dashboard.css">
</head>
<body>

<header class="navbar">
    <div class="logo">DysCover</div>

    <nav>
        <a href="../product.php">Product</a>
        <a href="../cart.php">Cart</a>
        <a href="profile.php">My Profile</a>
        <a href="../logout.php" class="logout-btn">Logout</a>
    </nav>
</header>

<main class="dashboard">
    <section class="welcome-card">
        <h1>Welcome, <?php echo htmlspecialchars($full_name); ?></h1>
        <p>You are logged in as <?php echo htmlspecialchars($email); ?></p>
    </section>

    <section class="dashboard-grid">
        <div class="card">
            <h2>View Product</h2>
            <p>Explore DysCover: A Gamified Dyscalculia Screening Game.</p>
            <a href="../product.php">View Product</a>
        </div>

        <div class="card">
            <h2>Shopping Cart</h2>
            <p>Check your selected product before checkout.</p>
            <a href="../cart.php">Go to Cart</a>
        </div>

        <div class="card">
            <h2>My Orders</h2>
            <p>View your order history and payment status.</p>
            <a href="orders.php">View Orders</a>
        </div>

        <div class="card">
            <h2>My Profile</h2>
            <p>View, update, or delete your account information.</p>
            <a href="profile.php">Manage Profile</a>
        </div>
    </section>
</main>

</body>
</html>