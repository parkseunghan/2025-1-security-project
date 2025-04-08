<?php
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

$post_id = intval($_POST['post_id']);
$title = $_POST['title'];
$content = $_POST['content'];
$filepath = null;

//기존 파일 
$sql = "SELECT file_path FROM posts WHERE id = $post_id";
$result = $conn->query($sql);
$oldpath = null;

if($result && $result->num_rows > 0){
    $row = $result->fetch_assoc();
    $oldpath = $row['file_path'];
}


if(isset($_FILES['file']) && $_FILES['file']['error'] ==0){
    $filename = $_FILES['file']['name'];
    $filetmp = $_FILES['file']['tmp_name'];
    $filepath = 'uploads/' . $filename;

    if(!move_uploaded_file($filetmp, $filepath)){
        echo "<script>alert('파일 업로드 실패'); history.back();</script>";
        exit();
    }

    if($oldpath){
        unlink($oldpath);
    }
}else{
    $filepath = $oldpath;
}

$sql = "UPDATE posts SET title = '$title', content = '$content', file_path = '$filepath' WHERE id = $post_id";
if($conn->query($sql) == FALSE){
    echo "<script>alert('게시글 수정 실패'); history.back();</script>";
}else{
    echo "<script>window.location.href = 'index.html';</script>";
}

$conn->close();
?>