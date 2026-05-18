<?php
session_start();
include("db_connect.php");

if (!isset($_SESSION["user_id"])) {
    echo "<script>
            alert('Please login first before adding product to cart.');
            window.location.href = 'login.html';
          </script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: product.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$product_id = isset($_POST["product_id"]) ? intval($_POST["product_id"]) : 0;
$quantity = isset($_POST["quantity"]) ? intval($_POST["quantity"]) : 1;

if ($product_id <= 0) {
    echo "<script>
            alert('Invalid product.');
            window.location.href = 'product.php';
          </script>";
    exit();
}

if ($quantity < 1) {
    $quantity = 1;
}

/*
    Step 1: Check whether this product already exists in cart
*/
$check_sql = "SELECT cart_id, quantity 
              FROM cart 
              WHERE user_id = ? AND product_id = ? 
              LIMIT 1";

$check_stmt = mysqli_prepare($conn, $check_sql);

if (!$check_stmt) {
    die("Prepare failed: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($check_stmt, "ii", $user_id, $product_id);
mysqli_stmt_execute($check_stmt);

/*
    Important:
    Use bind_result instead of mysqli_stmt_get_result
    to avoid 'Commands out of sync' issue.
*/
mysqli_stmt_store_result($check_stmt);
mysqli_stmt_bind_result($check_stmt, $cart_id, $existing_quantity);

if (mysqli_stmt_num_rows($check_stmt) > 0) {
    mysqli_stmt_fetch($check_stmt);
    mysqli_stmt_close($check_stmt);

    /*
        Product already exists, update quantity
    */
    $new_quantity = $existing_quantity + $quantity;

    $update_sql = "UPDATE cart 
                   SET quantity = ? 
                   WHERE cart_id = ? AND user_id = ?";

    $update_stmt = mysqli_prepare($conn, $update_sql);

    if (!$update_stmt) {
        die("Prepare failed: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($update_stmt, "iii", $new_quantity, $cart_id, $user_id);
    mysqli_stmt_execute($update_stmt);
    mysqli_stmt_close($update_stmt);

} else {
    mysqli_stmt_close($check_stmt);

    /*
        Product not in cart yet, insert new cart item
    */
    $insert_sql = "INSERT INTO cart (user_id, product_id, quantity) 
                   VALUES (?, ?, ?)";

    $insert_stmt = mysqli_prepare($conn, $insert_sql);

    if (!$insert_stmt) {
        die("Prepare failed: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($insert_stmt, "iii", $user_id, $product_id, $quantity);
    mysqli_stmt_execute($insert_stmt);
    mysqli_stmt_close($insert_stmt);
}

mysqli_close($conn);

echo "<script>
        alert('Product added to cart successfully.');
        window.location.href = 'cart.php';
      </script>";
exit();
?>