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

if(isset($_SESSION['user_id'])){
    $com = $_SESSION['user_id'];
}


$post = [];

$sql = "SELECT title, content, username, date, file_path FROM posts WHERE id = $post_id";
$result = $conn->query($sql);

if($result && $result->num_rows > 0){
    $post = $result->fetch_assoc();
    $post['match'] = false;
    if($com == $post['username']){
        $post['match'] = true;
    }

}



$conn->close();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>게시글</title>
</head>
<body>
    <h1><a href="index.html">게시판 홈</a></h1>
    <h1>게시글</h1>
    <div id="post_list">
        <h2><?= $post['title'] ?></h2>
        <p>작성자: <?= $post['username'] ?></p>
        <p>작성일: <?= $post['date'] ?></p>
        <p><?= $post['content'] ?></p>
        <?php if ($post['file_path']): ?>
            <p><a href="<?= $post['file_path'] ?>" download>첨부파일 다운로드</a></p>
        <?php endif; ?>
    </div>

    <div id="edit_button">
        <?php if($post['match']): ?>
            <button onclick="location.href='edit.html?id=<?= $post_id ?>'">수정</button>
            <button onclick="location.href='delete.php?id=<?= $post_id ?>'">삭제</button>
        <?php endif; ?>
    </div>
    
    <a href="index.html">돌아가기</a>

    
</body>
</html>