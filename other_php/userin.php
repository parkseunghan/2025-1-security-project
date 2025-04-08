<?php
// SQL
$servername = "localhost";
$username = "root";
$password = "0000";
$dbname = "knockon";

// SQL connect
$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error){
    die("connection fail : " . $conn->connect_error);
}
// ID 중복 확인 코드
if(isset($_POST['user_in'])){
    $id = $_POST['id'];
    $sql = "SELECT id FROM users WHERE username = '$id'";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        echo "<script>alert('이미 사용 중인 아이디입니다.'); history.back();</script>";
    }
    else{
        echo "<script>alert('사용 가능한 아이디입니다.'); history.back();</script>";
    }
}

// 회원가입 시작
if(isset($_POST['user_in'])){
    $id = $_POST['id'];
    $password = $_POST['password'];
    $password_2 = $_POST['password_2'];

    $sql = "SELECT id FROM users WHERE username = '$id'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        echo "<script>alert('이미 사용 중인 아이디입니다.'); history.back();</script>";
        exit();
    }

    if($password != $password_2){
        echo "<script>alert('비밀번호가 틀립니다.'); history.back();</script>";
    }
    else{
        $sql = "INSERT INTO users (username, password) VALUES ('$id', '$password')";
        if($conn->query($sql) == TRUE){
            echo "<script>alert('회원가입 성공'); window.location.href = 'login.html';</script>";
            header("Location: index.html");
            exit();
        }
        else{
            echo "<script>alert('회원가입 실패'); history.back();</script>";
        }
    }
}
$conn->close();
?>