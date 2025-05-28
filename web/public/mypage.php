<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

if (!isset($_SESSION['id'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

if (!isset($_SESSION['verified'])) {
    header("Location: mypage_check.php");
    exit;
}

$user = AuthController::getCurrentUser();
$errors = [];
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>마이페이지</title></head>
<body>
<h1>마이페이지</h1>
<?php if (!empty($errors)): ?>
    <ul style="color:red;"><?php foreach ($errors as $error): ?><li><?= $error ?></li><?php endforeach; ?></ul>
<?php endif; ?>
<form method="POST" action="mypage_update.php">
    <label>아이디: <?= htmlspecialchars($user['username']) ?></label><br>
    <label>닉네임: <input type="text" name="nickname" value="<?= htmlspecialchars($user['nickname']) ?>"></label><br>
    <label>새 비밀번호: <input type="password" name="password"></label><br>
    <label>새 비밀번호 확인: <input type="password" name="password2"></label><br>
    <button type="submit">정보 수정</button>
</form>
<a href="index.php">홈으로</a>
</body>
</html>
