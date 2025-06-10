<?php
require_once '../models/Comment.php';

class CommentController {
    public static function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $content = $_POST['content'];
            $postId = $_POST['post_id'];
            $userId = $_SESSION['id'];

            if (!empty($content)) {
                Comment::createComment($content, $postId, $userId);
            }
            header("Location: view.php?id=$postId");
            exit;
        }
    }

    public static function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $commentId = $_POST['comment_id'];
            $postId = $_POST['post_id'];
            $content = $_POST['content'];
            $userId = $_SESSION['id'];

            Comment::updateComment($commentId, $userId, $content);
            header("Location: view.php?id=$postId");
            exit;
        }
    }

    public static function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $commentId = $_POST['comment_id'];
            $postId = $_POST['post_id'];
            $userId = $_SESSION['id'];

            Comment::deleteComment($commentId, $userId);
            header("Location: view.php?id=$postId");
            exit;
        }
    }
}
