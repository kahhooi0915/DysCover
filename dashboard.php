<?php

session_start();

header("Content-Type: application/json");

if(!isset($_SESSION["user_id"])){

    echo json_encode([
        "status"=>"not_logged_in"
    ]);

    exit();
}

if($_SESSION["role"]!="customer"){

    echo json_encode([
        "status"=>"access_denied"
    ]);

    exit();
}

echo json_encode([

    "status"=>"success",
    "full_name"=>$_SESSION["full_name"],
    "email"=>$_SESSION["email"]

]);

?>