<?php
require_once '../config/config.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || (int)$_SESSION['is_admin'] !== 1) {
    echo "<script>alert('접근 권한이 없습니다.'); location.href='index.php';</script>";
    exit;
}

echo "<script>location.href='admin/index.php';</script>";
exit;
    