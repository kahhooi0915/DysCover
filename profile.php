<?php
session_start();
header("Content-Type: application/json");

if(!isset($_SESSION["user_id"])){
    echo json_encode(["status"=>"not_logged_in"]);
    exit();
}

if($_SESSION["role"]!="customer"){
    echo json_encode(["status"=>"access_denied"]);
    exit();
}

// If POST request, update profile
if($_SERVER['REQUEST_METHOD']==='POST'){
    $_SESSION['full_name'] = $_POST['full_name'] ?? $_SESSION['full_name'];
    $_SESSION['email'] = $_POST['email'] ?? $_SESSION['email'];
    $_SESSION['title'] = $_POST['title'] ?? $_SESSION['title'];

    // TODO: save to database

    header("Location: profile.html");
    exit();
}

// Return profile info
echo json_encode([
    "status"=>"success",
    "full_name"=>$_SESSION["full_name"],
    "email"=>$_SESSION["email"],
    "title"=>$_SESSION["title"] ?? ""
]);
?>