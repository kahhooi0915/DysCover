<?php
session_start();
include("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Basic validation
    if (empty($email) || empty($password)) {
        echo "<script>
                alert('Please enter both email and password.');
                window.history.back();
              </script>";
        exit();
    }

    // Check user by email
    $sql = "SELECT user_id, full_name, email, password, role
            FROM users
            WHERE email = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        // Verify hashed password
        if (password_verify($password, $user["password"])) {

            // Store session
            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["full_name"] = $user["full_name"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["role"] = $user["role"];

            // Redirect based on role
            switch ($user["role"]) {
                case "admin":
                    header("Location: admin/dashboard.php");
                    break;

               case "customer":
                default:
                    header("Location: member/dashboard.php");
                    break;
            }
            exit();
        } else {
            echo "<script>
                    alert('Invalid password.');
                    window.history.back();
                  </script>";
        }
    } else {
        echo "<script>
                alert('Email not found.');
                window.history.back();
              </script>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>