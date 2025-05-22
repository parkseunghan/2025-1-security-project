<?php
require_once '../config/config.php';
require_once '../models/Post.php';
require_once '../models/Comment.php';

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
    <hr>
    <h3>댓글</h3>

    <?php
    $comments = Comment::getCommentsByPostId($post['id']);
    while ($comment = $comments->fetch_assoc()):
    ?>
        <div style="margin-bottom:10px;">
            <strong><?= htmlspecialchars($comment['nickname']) ?></strong>:
            <?= nl2br(htmlspecialchars($comment['content'])) ?>
            <small>(<?= $comment['created_at'] ?>)</small>

            <?php if (isset($_SESSION['id']) && $_SESSION['id'] == $comment['user_id']): ?>
                <!-- 댓글 수정 폼 -->
                <form action="comment_update.php" method="post" style="display:inline;">
                    <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                    <input type="text" name="content" value="<?= htmlspecialchars($comment['content']) ?>" required>
                    <button type="submit">수정</button>
                </form>

                <!-- 댓글 삭제 폼 -->
                <form action="comment_delete.php" method="post" style="display:inline;">
                    <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                    <button type="submit" onclick="return confirm('댓글을 삭제하시겠습니까?')">삭제</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>

    <!-- 댓글 작성 폼 -->
    <?php if (isset($_SESSION['id'])): ?>
        <form action="comment_create.php" method="post">
            <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
            <textarea name="content" rows="3" cols="50" required></textarea><br>
            <button type="submit">댓글 작성</button>
        </form>
    <?php else: ?>
        <p><a href="login.php">로그인</a> 후 댓글을 작성할 수 있습니다.</p>
    <?php endif; ?>
    <hr>
    <a href="index.php">목록</a>
</body>
</html>
