<?php
session_start();

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

//$post_id = isset($_GET['id']);
//$post_id = intval($_GET['id']);
if(isset($_GET['id'])){
    $post_id = intval($_GET['id']);
}



if($_SERVER['REQUEST_METHOD'] == 'GET'){
    echo "<p>정말로 이 게시물을 삭제하시겠습니까?</p>";
    echo "<form method='post'>";
    echo "<button type='submit' name='confirm_delete'>삭제</button>";
    echo "<a href='index.html'>취소</a>";
    echo "</form>";
} else if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $file_sql = "SELECT file_path FROM posts WHERE id = $post_id";
    $result2 = $conn->query($file_sql);
    $filepath = null;

    if($result2 && $result2->num_rows > 0){
        $row = $result2->fetch_assoc();
        $filepath = $row['file_path'];
    }


    // posts db delete
    $delete_sql = "DELETE FROM posts WHERE id = $post_id";
    if($conn->query($delete_sql) == TRUE){
        if($filepath){
            unlink($filepath);
        }
        echo "<script>alert('게시물이 삭제되었습니다'); window.location.href = 'index.html';</script>";
    }else{
        echo "<script>alert('게시물 삭제에 실패했습니다'); window.location.href = 'index.html';</script>";
    }
    header("Location: index.html");
    exit();
}
$conn->close();


?>