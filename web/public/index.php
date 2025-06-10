<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/models/Post.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$result = Post::getAllPosts($search);
?>

<!DOCTYPE html>
<html lang="ko">
<head><meta charset="UTF-8"><title>게시판</title></head>
<body>
    <h1>연약한 아이</h1>

    <?php if (isset($_SESSION['user_id'])): ?>
        <p><?= $_SESSION['nickname'] ?>님 환영합니다!</p>
        <a href="write.php">글쓰기</a> |
        <a href="mypage.php">마이페이지</a> |
        <a href="logout.php">로그아웃</a> |
        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
            <a href="admin.php">관리자</a>
        <?php endif; ?>
    <?php else: ?>
        <a href="login.php">로그인</a> | <a href="register.php">회원가입</a>
    <?php endif; ?>

    <form method="GET" style="margin-top:10px;">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="제목 검색">
        <button type="submit">검색</button>
    </form>

    <table border="1" width="100%" style="margin-top:10px;">
        <thead><tr><th>번호</th><th>제목</th><th>작성자</th><th>작성일</th></tr></thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><a href="view.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['title']) ?></a></td>
                    <td><?= htmlspecialchars($row['nickname']) ?></td>
                    <td><?= $row['created_at'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
