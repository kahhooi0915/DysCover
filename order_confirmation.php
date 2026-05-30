<?php
session_start();
include("db_connect.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

if (!isset($_GET["order_id"])) {
    header("Location: product.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$order_id = intval($_GET["order_id"]);

$order_sql = "SELECT * FROM orders WHERE order_id = ? AND user_id = ?";
$order_stmt = mysqli_prepare($conn, $order_sql);
mysqli_stmt_bind_param($order_stmt, "ii", $order_id, $user_id);
mysqli_stmt_execute($order_stmt);
$order_result = mysqli_stmt_get_result($order_stmt);

if (mysqli_num_rows($order_result) !== 1) {
    die("Order not found.");
}

$order = mysqli_fetch_assoc($order_result);
mysqli_stmt_close($order_stmt);

$item_sql = "SELECT 
                order_items.quantity,
                order_items.price,
                order_items.subtotal,
                products.product_name
             FROM order_items
             INNER JOIN products ON order_items.product_id = products.product_id
             WHERE order_items.order_id = ?";

$item_stmt = mysqli_prepare($conn, $item_sql);
mysqli_stmt_bind_param($item_stmt, "i", $order_id);
mysqli_stmt_execute($item_stmt);
$item_result = mysqli_stmt_get_result($item_stmt);

$payment_sql = "SELECT * FROM payments WHERE order_id = ?";
$payment_stmt = mysqli_prepare($conn, $payment_sql);
mysqli_stmt_bind_param($payment_stmt, "i", $order_id);
mysqli_stmt_execute($payment_stmt);
$payment_result = mysqli_stmt_get_result($payment_stmt);
$payment = mysqli_fetch_assoc($payment_result);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DysCover | Order Confirmation</title>
    <link rel="stylesheet" href="css/cart.css?v=2">
</head>

<body>

<header class="navbar">
    <div class="logo">DysCover</div>

    <nav class="nav-links">
        <a href="product.php">Product</a>
        <a href="member/dashboard.php">Dashboard</a>
        <a href="logout.php" class="buy-btn">Logout</a>
    </nav>
</header>

<main class="cart-page">
    <section class="confirmation-card">
        <h1>Payment Successful!</h1>
        <p class="subtitle">Thank you for purchasing DysCover.</p>

        <div class="order-box">
            <h2>Order #<?php echo $order["order_id"]; ?></h2>

            <p><strong>Order Date:</strong> <?php echo $order["order_date"]; ?></p>
            <p><strong>Shipping Address:</strong> <?php echo htmlspecialchars($order["shipping_address"]); ?></p>
            <p><strong>Payment Status:</strong> <?php echo $order["payment_status"]; ?></p>
            <p><strong>Order Status:</strong> <?php echo $order["order_status"]; ?></p>

            <?php if ($payment): ?>
                <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($payment["payment_method"]); ?></p>
                <p><strong>Transaction ID:</strong> <?php echo htmlspecialchars($payment["transaction_id"]); ?></p>
            <?php endif; ?>
        </div>

        <h2>Purchased Items</h2>

        <table class="order-table">
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>

            <?php while ($item = mysqli_fetch_assoc($item_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item["product_name"]); ?></td>
                    <td><?php echo $item["quantity"]; ?></td>
                    <td>RM <?php echo number_format($item["price"], 2); ?></td>
                    <td>RM <?php echo number_format($item["subtotal"], 2); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>

        <div class="summary-total">
            <span>Total Paid</span>
            <strong>RM <?php echo number_format($order["total_amount"], 2); ?></strong>
        </div>

        <a href="product.php" class="primary-btn">Back to Product</a>
    </section>
</main>

</body>
</html>
