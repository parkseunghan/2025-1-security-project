<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

// 로그인 상태 확인
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

// 비밀번호 재확인 여부 확인
if (!isset($_SESSION['verified'])) {
    header("Location: mypage_check.php");
    exit;
}

// 사용자 정보 조회
$user = AuthController::getCurrentUser();
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']); // 한 번만 표시되도록
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>마이페이지</title>
</head>
<body>
<h1>마이페이지</h1>

<?php if (!empty($errors)): ?>
    <ul style="color:red;">
        <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST" action="mypage_update.php" autocomplete="off">
    <label>아이디: <?= htmlspecialchars($user['username']) ?></label><br>
    <label>닉네임: <input type="text" name="nickname" value="<?= htmlspecialchars($user['nickname']) ?>" required></label><br>
    <label>새 비밀번호: <input type="password" name="password"></label><br>
    <label>새 비밀번호 확인: <input type="password" name="password2"></label><br>
    <button type="submit">정보 수정</button>
</form>

<a href="index.php">홈으로</a>
</body>
</html>
