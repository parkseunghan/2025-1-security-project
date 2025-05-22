<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../app/controllers/AdminController.php';

$users = AdminController::getAllUsers();
?>

<!DOCTYPE html>
<html lang="ko">
<head><meta charset="UTF-8"><title>관리자 페이지</title></head>
<body>
<h1>관리자 페이지</h1>
<p>총 사용자 수: <?= count($users) ?></p>

<table border="1">
    <thead>
        <tr><th>ID</th><th>아이디</th><th>닉네임</th><th>관리자 여부</th><th>삭제</th></tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['nickname']) ?></td>
                <td><?= $user['is_admin'] ? '관리자' : '일반 사용자' ?></td>
                <td>
                    <?php if (!$user['is_admin']): ?>
                        <form method="POST" action="delete.php" onsubmit="return confirm('정말 삭제할까요?');">
                            <input type="hidden" name="id" value="<?= $user['id'] ?>">
                            <button type="submit">삭제</button>
                        </form>
                    <?php else: ?>
                        <span>-</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p><a href="../index.php">홈으로</a></p>
</body>
</html>
