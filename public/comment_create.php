<?php
require_once '../config/config.php';
require_once '../models/Comment.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'];
    $postId = $_POST['post_id'];
    $parentId = $_POST['parent_id'] ?? null;
    $userId = $_SESSION['id'] ?? null;

    if ($userId && !empty($content)) {
        if ($parentId !== null) {
            $conn = DB::connect();
            $stmt = $conn->prepare("SELECT parent_id FROM comments WHERE id = ?");
            $stmt->bind_param("i", $parentId);
            $stmt->execute();
            $result = $stmt->get_result();
            $parentComment = $result->fetch_assoc();

            if (!$parentComment || $parentComment['parent_id'] !== null) {
                die("대댓글의 대댓글은 허용되지 않습니다.");
            }
        }

        Comment::createComment($content, $postId, $userId, $parentId);
    }

    header("Location: view.php?id=$postId");
    exit;
}
