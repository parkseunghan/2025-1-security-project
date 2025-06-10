<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/models/Post.php';
require_once __DIR__ . '/../app/models/Comment.php';

$id = $_GET['id'];
$post = Post::getPostById($id);
if (!$post) {
    echo "<script>alert('존재하지 않는 글입니다'); location.href='index.php';</script>";
    exit;
}

$commentResult = Comment::getCommentsByPostId($post['id']);
$comments = [];
while ($row = $commentResult->fetch_assoc()) {
    $comments[] = $row;
}

$title = htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8');
$nickname = htmlspecialchars($post['nickname'], ENT_QUOTES, 'UTF-8');
$createdAt = htmlspecialchars($post['created_at'], ENT_QUOTES, 'UTF-8');
$content = nl2br(htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8'));
?>

<!DOCTYPE html>
<html lang="ko">
<head><meta charset="UTF-8"><title><?= $title ?></title></head>
<body>
    <h1><?= $title ?></h1>
    <p>작성자: <?= $nickname ?> | 작성일: <?= $createdAt ?></p>
    <hr>
    <div><?= $content ?></div>
    <hr>

    <?php if ($post['file_path']): ?>
        <p>첨부파일: <a href="download.php?file=<?= urlencode($post['file_path']) ?>">다운로드</a></p>
    <?php endif; ?>

    <?php if (isset($_SESSION['id']) && $_SESSION['id'] == $post['user_id']): ?>
        <a href="edit.php?id=<?= $post['id'] ?>">수정</a>
        <a href="delete.php?id=<?= $post['id'] ?>">삭제</a>
    <?php endif; ?>

    <hr>

    <h3>댓글</h3>

    <?php
    function renderComments($comments, $parentId = null, $level = 0) {
        foreach ($comments as $comment) {
            if ($comment['parent_id'] == $parentId) {
                $margin = $level * 30;
                echo "<div style='margin-left: {$margin}px; margin-bottom: 10px;'>";

                echo "<strong>" . htmlspecialchars($comment['nickname']) . "</strong>: " . nl2br(htmlspecialchars($comment['content']));
                echo " <small>({$comment['created_at']})</small><br>";

                if (isset($_SESSION['id']) && $_SESSION['id'] == $comment['user_id']) {
                    echo "
                        <form action='comment_update.php' method='post' style='display:inline;'>
                            <input type='hidden' name='comment_id' value='{$comment['id']}'>
                            <input type='hidden' name='post_id' value='{$comment['post_id']}'>
                            <input type='text' name='content' value='" . htmlspecialchars($comment['content']) . "' required>
                            <button type='submit'>수정</button>
                        </form>
                        <form action='comment_delete.php' method='post' style='display:inline;'>
                            <input type='hidden' name='comment_id' value='{$comment['id']}'>
                            <input type='hidden' name='post_id' value='{$comment['post_id']}'>
                            <button type='submit' onclick='return confirm(\"댓글을 삭제하시겠습니까?\")'>삭제</button>
                        </form>
                    ";
                }

                if ($level === 0 && isset($_SESSION['id'])) {
                    $formId = "reply-form-" . $comment['id'];
                    echo "<button type='button' onclick=\"toggleReplyForm('$formId')\">답글</button>";

                    echo "
                        <div id='$formId' style='display: none; margin-top: 5px;'>
                            <form action='comment_create.php' method='post'>
                                <input type='hidden' name='post_id' value='{$comment['post_id']}'>
                                <input type='hidden' name='parent_id' value='{$comment['id']}'>
                                <textarea name='content' rows='2' cols='40' required></textarea><br>
                                <button type='submit'>답글 작성</button>
                            </form>
                        </div>
                    ";
                }

                echo "</div>";

                if ($level < 1) {
                    renderComments($comments, $comment['id'], $level + 1);
                }
            }
        }
    }

    renderComments($comments);
    ?>

    <?php if (isset($_SESSION['id'])): ?>
        <hr>
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

    <script>
    function toggleReplyForm(formId) {
        const form = document.getElementById(formId);
        if (form.style.display === "none") {
            form.style.display = "block";
        } else {
            form.style.display = "none";
        }
    }
    </script>
</body>
</html>
