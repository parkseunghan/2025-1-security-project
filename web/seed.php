<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/models/DB.php';

$conn = DB::connect();

// 관리자 계정 생성
$username = 'admin';
$nickname = 'admin';
$password = password_hash('1234', PASSWORD_DEFAULT);
$is_admin = 1;

// 중복 방지
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    $stmt = $conn->prepare("INSERT INTO users (username, nickname, password, is_admin) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $username, $nickname, $password, $is_admin);
    $stmt->execute();
    echo "✅ 관리자 계정이 생성되었습니다.\n";
} else {
    echo "ℹ️ 이미 관리자 계정이 존재합니다.\n";
}
