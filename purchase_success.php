<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DysCover | Purchase Successful</title>
    <link rel="stylesheet" href="css/cart.css?v=2">
</head>

<body>

<header class="navbar">
    <div class="logo">DysCover</div>

    <nav class="nav-links">
        <a href="product.html">Product</a>
        <a href="member/dashboard.php">Dashboard</a>
        <a href="logout.php" class="buy-btn">Logout</a>
    </nav>
</header>

<main class="cart-page">
    <section class="confirmation-card">
        <h1>Purchase successful!</h1>
        <p class="subtitle">Purchase successful! Your order has been placed.</p>

        <a href="orders.php" class="primary-btn">View My Orders</a>
        <a href="member/dashboard.php" class="checkout-btn">Back to Dashboard</a>
    </section>
</main>

</body>
</html>
