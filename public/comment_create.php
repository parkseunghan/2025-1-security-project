<?php
require_once '../config/config.php';
require_once '../models/Comment.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'];
    $postId = $_POST['post_id'];
    $userId = $_SESSION['id'] ?? null;

    if ($userId && !empty($content)) {
        Comment::createComment($content, $postId, $userId);
    }

    header("Location: view.php?id=$postId");
    exit;
}