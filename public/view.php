<?php
require_once '../config/config.php';
require_once '../models/Post.php';

$id = $_GET['id'];
$post = Post::getPostById($id);
if (!$post) {
    echo "<script>alert('존재하지 않는 글입니다'); location.href='index.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head><meta charset="UTF-8"><title><?= $post['title'] ?></title></head>
<body>
    <h1><?= $post['title'] ?></h1>
    <p>작성자: <?= $post['nickname'] ?> | 작성일: <?= $post['created_at'] ?></p>
    <hr>
    <div><?= nl2br($post['content']) ?></div>
    <hr>

    <?php if ($post['file_path']): ?>
        <p>첨부파일: <a href="download.php?file=<?= $post['file_path'] ?>">다운로드</a></p>
    <?php endif; ?>

    <?php if (isset($_SESSION['id']) && $_SESSION['id'] == $post['user_id']): ?>
        <a href="edit.php?id=<?= $post['id'] ?>">수정</a>
        <a href="delete.php?id=<?= $post['id'] ?>">삭제</a>
    <?php endif; ?>
    <a href="index.php">목록</a>
</body>
</html>
