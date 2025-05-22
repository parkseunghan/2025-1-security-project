<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/controllers/PostController.php';

if (!isset($_SESSION['id'])) {
    echo "<script>alert('로그인 해주세요!'); location.href='login.php';</script>";
    exit;
}

$errors = PostController::write();
?>

<!DOCTYPE html>
<html lang="ko">
<head><meta charset="UTF-8"><title>글쓰기</title></head>
<body>
    <h1>글쓰기</h1>

    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?><li><?= $error ?></li><?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>제목: <input type="text" name="title"></label><br>
        <label>내용:<br><textarea name="content" rows="10" cols="50"></textarea></label><br>
        <label>첨부파일: <input type="file" name="upload"></label><br>
        <button type="submit">작성</button>
    </form>

    <a href="index.php">목록</a>
</body>
</html>
