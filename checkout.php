<?php
session_start();
include("db_connect.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION["user_id"];

$sql = "SELECT 
            cart.quantity,
            products.product_name,
            products.price
        FROM cart
        INNER JOIN products ON cart.product_id = products.product_id
        WHERE cart.user_id = ?";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Prepare failed: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt, $quantity, $product_name, $price);

$total = 0;
$cart_count = 0;

while (mysqli_stmt_fetch($stmt)) {
    $total += $price * $quantity;
    $cart_count++;
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

/*
    Important:
    If cart is empty, go to cart.php.
    Do NOT redirect to checkout.php.
*/
if ($cart_count === 0) {
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DysCover | Checkout</title>
    <link rel="stylesheet" href="css/cart.css?v=2">
</head>

<body>

<header class="navbar">
    <div class="logo">DysCover</div>

    <nav class="nav-links">
        <a href="product.html">Product</a>
        <a href="cart.php">Cart</a>
        <a href="member/dashboard.php">Dashboard</a>
        <a href="logout.php" class="buy-btn">Logout</a>
    </nav>
</header>

<main class="cart-page">
    <section class="checkout-container">
        <h1>Checkout</h1>
        <p class="subtitle">Enter your shipping information before payment.</p>

        <form action="process_checkout.php" method="POST" class="checkout-form">
            <div class="form-group">
                <label>Full Name</label>
                <input 
                    type="text" 
                    name="full_name" 
                    value="<?php echo htmlspecialchars($_SESSION["full_name"] ?? ""); ?>" 
                    required
                >
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <input 
                    type="text" 
                    name="phone" 
                    placeholder="0123456789" 
                    required
                >
            </div>

            <div class="form-group">
                <label>Shipping Address</label>
                <textarea 
                    name="shipping_address" 
                    rows="5" 
                    required 
                    placeholder="Enter your full shipping address"
                ></textarea>
            </div>

            <div class="payment-box">
                <h2>Total Payment</h2>
                <p>RM <?php echo number_format($total, 2); ?></p>
                <small>You will be redirected to Stripe test checkout.</small>
            </div>

            <button type="submit" class="checkout-btn">Pay with Stripe</button>
        </form>
    </section>
</main>

</body>
</html>
