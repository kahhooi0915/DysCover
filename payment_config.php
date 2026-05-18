<?php
// ToyyibPay Sandbox Configuration
// Register sandbox account at https://dev.toyyibpay.com
// Then create a category and copy your User Secret Key + Category Code.

define("TOYYIBPAY_MODE", "sandbox");

define("TOYYIBPAY_SECRET_KEY", "YOUR_SANDBOX_USER_SECRET_KEY");
define("TOYYIBPAY_CATEGORY_CODE", "YOUR_SANDBOX_CATEGORY_CODE");

// For sandbox:
define("TOYYIBPAY_CREATE_BILL_URL", "https://dev.toyyibpay.com/index.php/api/createBill");
define("TOYYIBPAY_PAYMENT_URL", "https://dev.toyyibpay.com/");

// Change dyscover if your project folder name is different.
define("BASE_URL", "http://localhost/dyscover/");
?>