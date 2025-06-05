<?php
require_once __DIR__ . '/../models/Post.php';

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
                
                $newName = time() . '_' . $filename;
		            $filePath = 'public/uploads/' . $newName;
         
			          $uploadPath = '/var/www/html/' . $filePath;
	              move_uploaded_file($tmp, $uploadPath);
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
        $file = $_GET['file'] ?? '';
        $basePath = realpath(__DIR__ . '/../'); // web/ 전체 열람 허용
        $targetPath = realpath($basePath . '/' . $file);
    
        // web 내부가 아닐 경우 차단
        if (!$targetPath || strpos($targetPath, $basePath) !== 0 || !is_file($targetPath)) {
            echo "❌ 파일이 존재하지 않거나 접근할 수 없습니다.";
            return;
        }
    
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($targetPath) . '"');
        header('Content-Length: ' . filesize($targetPath));
        readfile($targetPath);
        exit;
    }
}
