<?php
session_start();
include("db_connect.php");

require_once __DIR__ . "/vendor/autoload.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: checkout.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$full_name = trim($_POST["full_name"]);
$phone = trim($_POST["phone"]);
$shipping_address = trim($_POST["shipping_address"]);

if (empty($full_name) || empty($phone) || empty($shipping_address)) {
    echo "<script>
            alert('Please fill in all checkout details.');
            window.location.href = 'checkout.php';
          </script>";
    exit();
}

// Store shipping details in session temporarily until payment success
$_SESSION["checkout_full_name"] = $full_name;
$_SESSION["checkout_phone"] = $phone;
$_SESSION["checkout_shipping_address"] = $shipping_address;

// Get cart items
$sql = "SELECT 
            cart.quantity,
            products.product_id,
            products.product_name,
            products.price
        FROM cart
        INNER JOIN products ON cart.product_id = products.product_id
        WHERE cart.user_id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$line_items = [];
$total_amount = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $subtotal = $row["price"] * $row["quantity"];
    $total_amount += $subtotal;

    $line_items[] = [
        "price_data" => [
            "currency" => "myr",
            "product_data" => [
                "name" => $row["product_name"],
            ],
            "unit_amount" => intval($row["price"] * 100),
        ],
        "quantity" => intval($row["quantity"]),
    ];
}

mysqli_stmt_close($stmt);

if (count($line_items) === 0) {
    mysqli_close($conn);
    header("Location: cart.php");
    exit();
}

$_SESSION["checkout_total_amount"] = $total_amount;

// IMPORTANT: Put your Stripe TEST secret key here
$stripe_secret_key = "sk_test_51TY8cNB47FV6kjln2SFg8uf9orRd9fNhtgH9iN4j90SdxqWBJGkdR3F4LnNI26BRTc9ZLCL1YiHpd4CLufKhN0Hq00Z93Rjl9o";

\Stripe\Stripe::setApiKey($stripe_secret_key);

// Change this if your project folder name is different
$base_url = "http://localhost/dyscover";

try {
    $checkout_session = \Stripe\Checkout\Session::create([
        "payment_method_types" => ["card"],
        "mode" => "payment",
        "customer_email" => $_SESSION["email"],
        "line_items" => $line_items,
        "success_url" => $base_url . "/payment_success.php?session_id={CHECKOUT_SESSION_ID}",
        "cancel_url" => $base_url . "/checkout.php",
        "metadata" => [
            "user_id" => $user_id,
            "full_name" => $full_name,
            "phone" => $phone
        ]
    ]);

    mysqli_close($conn);

    header("Location: " . $checkout_session->url);
    exit();

} catch (Exception $e) {
    mysqli_close($conn);

    echo "<h2>Stripe Error</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<a href='checkout.php'>Back to checkout</a>";
    exit();
}
?>