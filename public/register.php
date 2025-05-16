<?php
require_once '../config/config.php';
require_once '../controllers/AuthController.php';

$errors = AuthController::register();
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
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST">
        <label>아이디: <input type="text" name="username"></label><br>
        <label>닉네임: <input type="text" name="nickname"></label><br>
        <label>비밀번호: <input type="password" name="password"></label><br>
        <button type="submit">회원가입</button>
    </form>

    <a href="login.php">로그인</a>
</body>
</html>
