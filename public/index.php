<?php
require_once '../config/config.php';
require_once '../models/Post.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$result = Post::getAllPosts($search);
?>

<!DOCTYPE html>
<html lang="ko">
<head><meta charset="UTF-8"><title>게시판</title></head>
<body>
    <h1>연약한 아이</h1>

    <?php if (isset($_SESSION['id'])): ?>
        <p><?= $_SESSION['nickname'] ?>님 환영합니다!</p>
        <a href="write.php">글쓰기</a> | <a href="logout.php">로그아웃</a>
    <?php else: ?>
        <a href="login.php">로그인</a> | <a href="register.php">회원가입</a>
    <?php endif; ?>

    <form method="GET" style="margin-top:10px;">
        <input type="text" name="search" value="<?= $search ?>" placeholder="제목 검색">
        <button type="submit">검색</button>
    </form>

    <table border="1" width="100%" style="margin-top:10px;">
        <thead><tr><th>번호</th><th>제목</th><th>작성자</th><th>작성일</th></tr></thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><a href="view.php?id=<?= $row['id'] ?>"><?= $row['title'] ?></a></td>
                    <td><?= $row['nickname'] ?></td>
                    <td><?= $row['created_at'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
