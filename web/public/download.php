<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/controllers/PostController.php';
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}
PostController::download();
