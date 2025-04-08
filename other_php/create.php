<?php
session_start();

if(!isset($_SESSION['user_id']) && !isset($_COOKIE['user_id_cookie'])){
    echo "<script>alert('로그인이 필요합니다'); window.location.href = 'login.html';</script>";
}
$userid = $_SESSION['user_id'];

// sql
$servername = "localhost";
$username = "root";
$password = "0000";
$dbname = "knockon";

// SQL connect
$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error){
    die("connection fail : " . $conn->connect_error);
}

$title = $_POST['title'];
$content = $_POST['content'];
$filepath = null;

if(isset($_FILES['file']) && $_FILES['file']['error'] == 0){
    $filename = $_FILES['file']['name'];
    $filetmp = $_FILES['file']['tmp_name'];
    $filepath = 'uploads/' . $filename;

    if(!move_uploaded_file($filetmp, $filepath)){
        echo "<script>alert('파일 업로드 실패'); history.back();</script>";
        exit();
    }
}

$sql = "INSERT INTO posts (title, content, username, file_path) VALUES ('$title', '$content', '$userid', '$filepath')";
if($conn->query($sql) == FALSE){
    echo "<script>alert('게시글 작성 실패'); history.back();</script>";
}else{
    echo "<script>window.location.href = 'index.html';</script>";
}

$conn->close();

?>