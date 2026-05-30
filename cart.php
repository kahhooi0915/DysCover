<?php
session_start();

include("db_connect.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION["user_id"];

$sql = "SELECT 
            cart.cart_id,
            cart.quantity,
            products.product_id,
            products.product_name,
            products.price,
            products.image,
            products.stock
        FROM cart
        INNER JOIN products ON cart.product_id = products.product_id
        WHERE cart.user_id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$total = 0;
$items = [];

while ($row = mysqli_fetch_assoc($result)) {
    $row["subtotal"] = $row["price"] * $row["quantity"];
    $total += $row["subtotal"];
    $items[] = $row;
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DysCover | Cart</title>
    <link rel="stylesheet" href="css/cart.css?v=2">
</head>

<body>

<header class="navbar">
    <div class="logo">DysCover</div>

    <nav class="nav-links">
        <a href="product.php">Product</a>
        <a href="cart.php" class="active">Cart</a>
        <a href="member/dashboard.php">Dashboard</a>
        <a href="logout.php" class="buy-btn">Logout</a>
    </nav>
</header>

<main class="cart-page">
    <section class="cart-container">
        <h1>Your Shopping Cart</h1>
        <p class="subtitle">Review your DysCover purchase before checkout.</p>

        <?php if (count($items) === 0): ?>
            <div class="empty-cart">
                <h2>Your cart is empty.</h2>
                <p>Add the DysCover product to continue.</p>
                <a href="product.php" class="primary-btn">View Product</a>
            </div>
        <?php else: ?>

            <div class="cart-layout">
                <div class="cart-items">
                    <?php foreach ($items as $item): ?>
                        <div class="cart-card">
                            <img src="<?php echo htmlspecialchars($item["image"]); ?>" alt="Product Image">

                            <div class="cart-info">
                                <h3><?php echo htmlspecialchars($item["product_name"]); ?></h3>
                                <p>Price: RM <?php echo number_format($item["price"], 2); ?></p>

                                <form action="update_cart.php" method="POST" class="quantity-form">
                                    <input type="hidden" name="cart_id" value="<?php echo $item["cart_id"]; ?>">

                                    <label>Quantity</label>
                                    <input 
                                        type="number" 
                                        name="quantity" 
                                        value="<?php echo $item["quantity"]; ?>" 
                                        min="1" 
                                        max="<?php echo $item["stock"]; ?>"
                                    >

                                    <button type="submit">Update</button>
                                </form>

                                <a 
                                    href="remove_cart_item.php?cart_id=<?php echo $item["cart_id"]; ?>" 
                                    class="remove-link"
                                    onclick="return confirm('Remove this item from cart?');"
                                >
                                    Remove
                                </a>
                            </div>

                            <div class="subtotal">
                                <span>Subtotal</span>
                                <strong>RM <?php echo number_format($item["subtotal"], 2); ?></strong>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <aside class="cart-summary">
                    <h2>Order Summary</h2>

                    <div class="summary-row">
                        <span>Subtotal</span>
                        <strong>RM <?php echo number_format($total, 2); ?></strong>
                    </div>

                    <div class="summary-row">
                        <span>Shipping</span>
                        <strong>Free</strong>
                    </div>

                    <div class="summary-total">
                        <span>Total</span>
                        <strong>RM <?php echo number_format($total, 2); ?></strong>
                    </div>

                    <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
                    <a href="product.php" class="continue-link">Continue Shopping</a>
                </aside>
            </div>

        <?php endif; ?>
    </section>
</main>

</body>
</html>
