<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

// 로그인 확인
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

// 검증 플래그 확인
if (!isset($_SESSION['verified'])) {
    echo "<script>alert('비밀번호 재확인이 필요합니다.'); location.href='mypage_check.php';</script>";
    exit;
}

// 입력값
$nickname = trim($_POST['nickname'] ?? '');
$password = $_POST['password'] ?? '';
$password2 = $_POST['password2'] ?? '';

$errors = [];

// 닉네임 유효성 검사
if (empty($nickname)) {
    $errors[] = '닉네임을 입력해주세요.';
}

// 비밀번호 일치 검사
if (!empty($password) && $password !== $password2) {
    $errors[] = '비밀번호가 일치하지 않습니다.';
}

// 오류 발생 시 되돌림
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: mypage.php");
    exit;
}

// 업데이트 실행
$id = $_SESSION['user_id'];
$result = AuthController::updateUser($id, $nickname, $password);

if ($result) {
    echo "<script>alert('정보가 수정되었습니다.'); location.href='mypage.php';</script>";
} else {
    echo "<script>alert('정보 수정에 실패했습니다.'); location.href='mypage.php';</script>";
}
