<?php
require_once '../config/config.php';
require_once '../models/Comment.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentId = $_POST['comment_id'];
    $postId = $_POST['post_id'];
    $userId = $_SESSION['id'] ?? null;

    if ($userId) {
        Comment::deleteComment($commentId, $userId);
    }

    header("Location: view.php?id=$postId");
    exit;
}
