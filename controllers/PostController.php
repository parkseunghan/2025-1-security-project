<?php
require_once '../models/Post.php';

class PostController {
    public static function write() {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $content = $_POST['content'];
            $userId = $_SESSION['id'];
            $filePath = '';

            if (isset($_FILES['upload']) && $_FILES['upload']['error'] === UPLOAD_ERR_OK) {
                $filename = $_FILES['upload']['name'];
                $tmp = $_FILES['upload']['tmp_name'];
                $filePath = 'uploads/' . time() . '_' . $filename;
                move_uploaded_file($tmp, '../' . $filePath);
            }

            if (empty($title) || empty($content)) {
                $errors[] = "제목과 내용을 입력해주세요.";
            } else {
                $result = Post::createPost($title, $content, $filePath, $userId);
                if ($result) {
                    echo "<script>alert('글 작성 완료!'); location.href='index.php';</script>";
                    exit;
                } else {
                    $errors[] = "글 작성 실패!";
                }
            }
        }

        return $errors;
    }

    public static function update($id) {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $content = $_POST['content'];

            if (empty($title) || empty($content)) {
                $errors[] = "제목과 내용을 입력해주세요.";
            } else {
                $result = Post::updatePost($id, $title, $content);
                if ($result) {
                    echo "<script>alert('수정 완료!'); location.href='view.php?id=$id';</script>";
                    exit;
                } else {
                    $errors[] = "수정 실패!";
                }
            }
        }

        return $errors;
    }

    public static function delete($id) {
        $result = Post::deletePost($id);
        if ($result) {
            echo "<script>alert('삭제 완료!'); location.href='index.php';</script>";
        } else {
            echo "<script>alert('삭제 실패!'); history.back();</script>";
        }
        exit;
    }

    public static function download() {
        $file = $_GET['file'];
        $path = '../' . $file;

        if (file_exists($path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($path) . '"');
            header('Content-Length: ' . filesize($path));
            readfile($path);
            exit;
        } else {
            echo "파일이 존재하지 않습니다.";
        }
    }
}
