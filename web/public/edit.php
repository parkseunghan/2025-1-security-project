<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/controllers/PostController.php';
require_once __DIR__ . '/../app/models/Post.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

$id = $_GET['id'];
$post = Post::getPostById($id);
if (!$post) {
    echo "<script>alert('게시글 없음'); location.href='index.php';</script>";
    exit;
}

if ($post['user_id'] != $_SESSION['user_id']) {
    echo "<script>alert('본인만 수정 가능합니다.'); location.href='view.php?id=$id';</script>";
    exit;
}

$errors = PostController::update($id);
?>

<!DOCTYPE html>
<html lang="ko">
<head><meta charset="UTF-8"><title>글 수정</title></head>
<body>
    <h1>글 수정</h1>

    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?><li><?= $error ?></li><?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST">
        <label>제목: <input type="text" name="title" value="<?= $post['title'] ?>"></label><br>
        <label>내용:<br><textarea name="content" rows="10" cols="50"><?= $post['content'] ?></textarea></label><br>
        <button type="submit">수정</button>
    </form>

    <a href="view.php?id=<?= $id ?>">취소</a>
</body>
</html>
