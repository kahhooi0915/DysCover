<?php
session_start();
include("db_connect.php");

require_once __DIR__ . "/vendor/autoload.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

if (!isset($_GET["session_id"])) {
    header("Location: cart.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$session_id = $_GET["session_id"];

$stripe_secret_key = "sk_test_51TY8cNB47FV6kjln2SFg8uf9orRd9fNhtgH9iN4j90SdxqWBJGkdR3F4LnNI26BRTc9ZLCL1YiHpd4CLufKhN0Hq00Z93Rjl9o";
\Stripe\Stripe::setApiKey($stripe_secret_key);

try {
    $stripe_session = \Stripe\Checkout\Session::retrieve($session_id);

    if ($stripe_session->payment_status !== "paid") {
        echo "<script>
                alert('Payment was not completed.');
                window.location.href = 'checkout.php';
              </script>";
        exit();
    }

    $shipping_address = $_SESSION["checkout_shipping_address"];
    $total_amount = $_SESSION["checkout_total_amount"];

    mysqli_begin_transaction($conn);

    // Create order
    $order_sql = "INSERT INTO orders 
                    (user_id, total_amount, shipping_address, payment_status, order_status)
                  VALUES 
                    (?, ?, ?, 'PAID', 'PROCESSING')";

    $order_stmt = mysqli_prepare($conn, $order_sql);
    mysqli_stmt_bind_param($order_stmt, "ids", $user_id, $total_amount, $shipping_address);
    mysqli_stmt_execute($order_stmt);

    $order_id = mysqli_insert_id($conn);
    mysqli_stmt_close($order_stmt);

    // Get cart items
    $cart_sql = "SELECT 
                    cart.quantity,
                    products.product_id,
                    products.price
                 FROM cart
                 INNER JOIN products ON cart.product_id = products.product_id
                 WHERE cart.user_id = ?";

    $cart_stmt = mysqli_prepare($conn, $cart_sql);
    mysqli_stmt_bind_param($cart_stmt, "i", $user_id);
    mysqli_stmt_execute($cart_stmt);
    $cart_result = mysqli_stmt_get_result($cart_stmt);

    while ($item = mysqli_fetch_assoc($cart_result)) {
        $subtotal = $item["price"] * $item["quantity"];

        $item_sql = "INSERT INTO order_items 
                        (order_id, product_id, quantity, price, subtotal)
                     VALUES 
                        (?, ?, ?, ?, ?)";

        $item_stmt = mysqli_prepare($conn, $item_sql);
        mysqli_stmt_bind_param(
            $item_stmt,
            "iiidd",
            $order_id,
            $item["product_id"],
            $item["quantity"],
            $item["price"],
            $subtotal
        );
        mysqli_stmt_execute($item_stmt);
        mysqli_stmt_close($item_stmt);
    }

    mysqli_stmt_close($cart_stmt);

    // Create payment record
    $transaction_id = $stripe_session->payment_intent;

    $payment_sql = "INSERT INTO payments 
                        (order_id, payment_method, transaction_id, amount, status)
                    VALUES 
                        (?, 'Stripe Test Card', ?, ?, 'SUCCESS')";

    $payment_stmt = mysqli_prepare($conn, $payment_sql);
    mysqli_stmt_bind_param($payment_stmt, "isd", $order_id, $transaction_id, $total_amount);
    mysqli_stmt_execute($payment_stmt);
    mysqli_stmt_close($payment_stmt);

    // Clear cart
    $clear_sql = "DELETE FROM cart WHERE user_id = ?";
    $clear_stmt = mysqli_prepare($conn, $clear_sql);
    mysqli_stmt_bind_param($clear_stmt, "i", $user_id);
    mysqli_stmt_execute($clear_stmt);
    mysqli_stmt_close($clear_stmt);

    mysqli_commit($conn);
    mysqli_close($conn);

    unset($_SESSION["checkout_full_name"]);
    unset($_SESSION["checkout_phone"]);
    unset($_SESSION["checkout_shipping_address"]);
    unset($_SESSION["checkout_total_amount"]);

    $_SESSION["last_order_id"] = $order_id;

    header("Location: purchase_success.php");
    exit();

} catch (Exception $e) {
    mysqli_rollback($conn);
    mysqli_close($conn);

    echo "<h2>Payment Confirmation Error</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<a href='checkout.php'>Back to checkout</a>";
    exit();
}
?>
