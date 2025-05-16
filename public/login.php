<?php
require_once '../config/config.php';
require_once '../controllers/AuthController.php';

$errors = AuthController::login();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>로그인</title>
</head>
<body>
    <h1>로그인</h1>

    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST">
        <label>아이디: <input type="text" name="username"></label><br>
        <label>비밀번호: <input type="password" name="password"></label><br>
        <label><input type="checkbox" name="remember_me"> 로그인 상태 유지</label><br>
        <button type="submit">로그인</button>
    </form>

    <a href="register.php">회원가입</a>
</body>
</html>
