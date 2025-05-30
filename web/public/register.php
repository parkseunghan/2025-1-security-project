<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = AuthController::register();
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>회원가입</title>
</head>
<body>
    <h1>회원가입</h1>

    <?php if (!empty($errors)): ?>
        <ul style="color: red;">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" autocomplete="off">
        <label>아이디: <input type="text" name="username" required></label><br>
        <label>닉네임: <input type="text" name="nickname" required></label><br>
        <label>비밀번호: <input type="password" name="password" required></label><br>
        <label>비밀번호 확인: <input type="password" name="password2" required></label><br>
        <button type="submit">회원가입</button>
    </form>

    <a href="login.php">로그인</a>
</body>
</html>
