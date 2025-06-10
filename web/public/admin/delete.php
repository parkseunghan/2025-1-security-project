<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../app/controllers/AdminController.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

if (!isset($_SESSION['user_id']) || (int)$_SESSION['is_admin'] !== 1) {
    echo "<script>alert('접근 권한이 없습니다.'); location.href='../index.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['id'];
    AdminController::deleteUser($userId);
}

echo "<script>alert('삭제 완료!'); location.href='index.php';</script>";
exit;
