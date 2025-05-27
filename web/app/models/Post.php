<?php
require_once __DIR__ . '/DB.php';

class Post {
    // ✅ 게시글 생성
    public static function createPost($title, $content, $filePath, $userId) {
        $conn = DB::connect();
        $stmt = $conn->prepare("INSERT INTO posts (title, content, file_path, user_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $title, $content, $filePath, $userId);
        return $stmt->execute();
    }

    // ✅ 전체 게시글 검색
    public static function getAllPosts($search = '') {
        $conn = DB::connect();

        if ($search) {
            // LIKE 검색 허용 (vuln1에서 완화 적용)
            $search = "%{$search}%";
            $stmt = $conn->prepare("
                SELECT posts.*, users.nickname
                FROM posts
                JOIN users ON posts.user_id = users.id
                WHERE posts.title LIKE ?
                ORDER BY posts.id DESC
            ");
            $stmt->bind_param("s", $search);
        } else {
            $stmt = $conn->prepare("
                SELECT posts.*, users.nickname
                FROM posts
                JOIN users ON posts.user_id = users.id
                ORDER BY posts.id DESC
            ");
        }

        $stmt->execute();
        return $stmt->get_result();
    }

    // ✅ ID 기준 게시글 조회
    public static function getPostById($id) {
        $conn = DB::connect();
        $stmt = $conn->prepare("
            SELECT posts.*, users.nickname
            FROM posts
            JOIN users ON posts.user_id = users.id
            WHERE posts.id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // ✅ 게시글 수정
    public static function updatePost($id, $title, $content) {
        $conn = DB::connect();
        $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
        $stmt->bind_param("ssi", $title, $content, $id);
        return $stmt->execute();
    }

    // ✅ 게시글 삭제
    public static function deletePost($id) {
        $conn = DB::connect();
        $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
