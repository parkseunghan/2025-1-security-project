<?php
require_once 'DB.php';

class Comment {
    public static function createComment($content, $postId, $userId, $parentId = null) {
        $conn = DB::connect();
        $stmt = $conn->prepare("INSERT INTO comments (content, post_id, user_id, parent_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siii", $content, $postId, $userId, $parentId);
        return $stmt->execute();
    }

    public static function getCommentsByPostId($postId) {
        $conn = DB::connect();
        $stmt = $conn->prepare("SELECT comments.*, users.nickname FROM comments JOIN users ON comments.user_id = users.id WHERE post_id = ? ORDER BY comments.created_at ASC");
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public static function updateComment($id, $userId, $content) {
        $conn = DB::connect();
        $stmt = $conn->prepare("UPDATE comments SET content = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("sii", $content, $id, $userId);
        return $stmt->execute();
    }

    public static function deleteComment($id, $userId) {
        $conn = DB::connect();
        $stmt = $conn->prepare("DELETE FROM comments WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $userId);
        return $stmt->execute();
    }
}
