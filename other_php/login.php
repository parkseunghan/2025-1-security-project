<?php
//SQL
$servername = "localhost";
$username = "root";
$password = "0000";
$dbname = "knockon";

// SQL connect
$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error){
    die("connection fail : " . $conn->connect_error);
}

session_start();

if(isset($_POST['login'])){
    $id = $_POST['id'];
    $password = $_POST['password'];
    
    $sql = "SELECT password FROM users WHERE username = '$id' and password = '$password'";
    $result = $conn->query($sql);
    if($result && $result->num_rows > 0){
        $row = $result->fetch_assoc(); # fetch_assoc 첫번째 행을 연관 배열로 가져옴
        $com_password = $row['password'];
        
        // 로그인 성공
        setcookie("user_id_cookie", $id, time() + (86400 * 30), "/"); // 쿠키
        $_SESSION['user_id'] = $id;
        echo "<script>alert('환영합니다'); window.location.href = 'index.html';</script>";
        
        
    }
    else{
        echo "<script>alert('아이디가 존재하지 않습니다.'); history.back();</script>";
    }
}
$conn->close();
?>