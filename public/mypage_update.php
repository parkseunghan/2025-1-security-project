<?php
require_once '../config/config.php';
require_once '../controllers/AuthController.php';

session_start();
if (!isset($_SESSION['id'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

// ✅ POST 값 처리 위임
$errors = AuthController::mypage($_POST);

// ✅ 실패 시만 처리
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<script>alert('$error'); history.back();</script>";
    }
}
