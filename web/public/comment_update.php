<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/models/Comment.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentId = $_POST['comment_id'];
    $postId = $_POST['post_id'];
    $content = $_POST['content'];
    $userId = $_SESSION['id'] ?? null;

    if ($userId && !empty($content)) {
        Comment::updateComment($commentId, $userId, $content);
    }

    header("Location: view.php?id=$postId");
    exit;
}
