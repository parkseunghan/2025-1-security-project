<?php
session_start();

header('Content-Type: application/json');


if(isset($_SESSION['user_id']) && isset($_COOKIE['user_id_cookie'])){
    $userid = $_SESSION['user_id'];
}

if($userid != null){
    echo json_encode([
        'loggedIn' => true,
        'username' => $userid
    ]);
}else{
    echo json_encode([
        'loggedIn' => false
    ]);
}
?>