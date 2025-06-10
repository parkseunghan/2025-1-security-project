<?php
require_once __DIR__ . '/../models/Post.php';

class PostController {
    // ✅ 글 작성
    public static function write() {
        $errors = [];
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $userId = $_SESSION['user_id'] ?? null;
            $filePath = '';
    
            // ✅ 파일 업로드
            if (isset($_FILES['upload']) && $_FILES['upload']['error'] === UPLOAD_ERR_OK) {
                $filename = basename($_FILES['upload']['name']);
                $tmp = $_FILES['upload']['tmp_name'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
                // ✅ 파일 확장자 제한
                $allowed = ['jpg', 'png', 'jpeg', 'gif', 'pdf'];
                if (!in_array($ext, $allowed)) {
                    $errors[] = "허용되지 않는 파일 형식입니다.";
                } else {
                    $newName = time() . '_' . preg_replace("/[^a-zA-Z0-9_.]/", "", $filename);
                    $filePath = 'uploads/' . $newName;
                    $uploadPath = '/var/www/html/public/' . $filePath; // 실제 저장 경로
                    move_uploaded_file($tmp, $uploadPath);
                }
            }
    
            if (!$title || !$content) {
                $errors[] = "제목과 내용을 입력해주세요.";
            }
    
            if (empty($errors)) {
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
    

    // ✅ 글 수정
    public static function update($id) {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title']);
            $content = trim($_POST['content']);

            if (!$title || !$content) {
                $errors[] = "제목과 내용을 입력해주세요.";
            } else {
                // ⚠️ 작성자 본인 확인은 view/edit.php 등에서 별도 검증
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

    // ✅ 글 삭제
    public static function delete($id) {
        // ⚠️ 작성자 검증은 삭제 버튼 노출 단계에서 제한
        $result = Post::deletePost($id);
        if ($result) {
            echo "<script>alert('삭제 완료!'); location.href='index.php';</script>";
        } else {
            echo "<script>alert('삭제 실패!'); history.back();</script>";
        }
        exit;
    }

    // ✅ 첨부파일 다운로드
    public static function download() {
        $file = $_GET['file'] ?? '';
        $basePath = realpath(__DIR__ . '/../'); // /app 기준 루트
        $targetPath = realpath($basePath . '/../public/' . $file);

        // ✅ 디렉토리 접근 제한
        if (!$targetPath || strpos($targetPath, realpath($basePath . '/../public/uploads')) !== 0 || !is_file($targetPath)) {
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
