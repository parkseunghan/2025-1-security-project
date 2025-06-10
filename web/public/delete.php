<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/controllers/PostController.php';
require_once __DIR__ . '/../app/models/Post.php';

// ✅ 로그인 확인
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

// ✅ id 확인
$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script>alert('잘못된 요청입니다.'); location.href='index.php';</script>";
    exit;
}

// ✅ 게시글 조회
$post = Post::getPostById($id);
if (!$post) {
    echo "<script>alert('게시글이 존재하지 않습니다.'); location.href='index.php';</script>";
    exit;
}

// ✅ 권한 확인
if ($post['user_id'] != $_SESSION['user_id']) {
    echo "<script>alert('본인만 삭제할 수 있습니다.'); location.href='view.php?id=$id';</script>";
    exit;
}

// ✅ 삭제 수행
PostController::delete($id);
echo "<script>alert('삭제되었습니다.'); location.href='index.php';</script>";
exit;
