<?php
require_once 'DB.php';

class Post {
    public static function createPost($title, $content, $filePath, $userId) {
        $conn = DB::connect();
        $sql = "INSERT INTO posts (title, content, file_path, user_id) 
                VALUES ('$title', '$content', '$filePath', $userId)";
        return $conn->query($sql);
    }

    public static function getAllPosts($search = '') {
        $conn = DB::connect();
        $where = $search ? "WHERE title LIKE '%$search%'" : "";
        $sql = "SELECT posts.*, users.nickname 
                FROM posts 
                JOIN users ON posts.user_id = users.id 
                $where 
                ORDER BY posts.id DESC";
        return $conn->query($sql);
    }

    public static function getPostById($id) {
        $conn = DB::connect();
        $sql = "SELECT posts.*, users.nickname 
                FROM posts 
                JOIN users ON posts.user_id = users.id 
                WHERE posts.id = $id";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }

    public static function updatePost($id, $title, $content) {
        $conn = DB::connect();
        $sql = "UPDATE posts SET title = '$title', content = '$content' WHERE id = $id";
        return $conn->query($sql);
    }

    public static function deletePost($id) {
        $conn = DB::connect();
        $sql = "DELETE FROM posts WHERE id = $id";
        return $conn->query($sql);
    }
}
