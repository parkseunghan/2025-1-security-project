<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/DB.php';

class AuthController {
    // ✅ 회원가입
    public static function register() {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $nickname = trim($_POST['nickname']);
            $password = $_POST['password'];
            $password2 = $_POST['password2'];

            if (!$username || !$nickname || !$password || !$password2) {
                $errors[] = "모든 항목을 입력해주세요.";
            }

            if ($password !== $password2) {
                $errors[] = "비밀번호가 일치하지 않습니다.";
            }

            $conn = DB::connect();

            // ID 중복 검사
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                $errors[] = "이미 존재하는 아이디입니다.";
            }

            // 닉네임 중복 검사
            $stmt = $conn->prepare("SELECT id FROM users WHERE nickname = ?");
            $stmt->bind_param("s", $nickname);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                $errors[] = "이미 존재하는 닉네임입니다.";
            }

            // 계정 생성
            if (empty($errors)) {
                $result = User::createUser($username, $nickname, $password);
                if ($result) {
                    echo "<script>alert('회원가입 성공!'); location.href='login.php';</script>";
                    exit;
                } else {
                    $errors[] = "회원가입 실패.";
                }
            }
        }

        return $errors;
    }

    // ✅ 로그인
    public static function login() {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            if (!$username || !$password) {
                $errors[] = "아이디와 비밀번호를 입력해주세요.";
            } else {
                $user = User::getUserByUsernameAndPassword($username, $password);
                if ($user) {
                    if (session_status() === PHP_SESSION_NONE) session_start();
                    session_regenerate_id(true); // 세션 고정 방지

                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['nickname'] = $user['nickname'];
                    $_SESSION['is_admin'] = $user['is_admin'];

                    if (isset($_POST['remember_me'])) {
                        setcookie('remember_me', $user['id'], time() + (86400 * 7), "/");
                    }

                    // 로그인 로그 기록
                    $log = sprintf(
                        "[%s] LOGIN: user_id=%s | user_name=%s | IP=%s | UA=%s\n",
                        date("Y-m-d H:i:s"),
                        $user['id'],
                        $user['username'],
                        $_SERVER['REMOTE_ADDR'],
                        $_SERVER['HTTP_USER_AGENT']
                    );
                    file_put_contents(__DIR__ . '/../logs/login.log', $log, FILE_APPEND);

                    echo "<script>alert('로그인 성공!'); location.href='index.php';</script>";
                    exit;
                } else {
                    $errors[] = "아이디 또는 비밀번호가 올바르지 않습니다.";
                }
            }
        }

        return $errors;
    }

    // ✅ 로그아웃
    public static function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_unset();
        session_destroy();
        setcookie('remember_me', '', time() - 3600, "/");

        echo "<script>alert('로그아웃 완료!'); location.href='index.php';</script>";
        exit;
    }

    // ✅ 현재 사용자 조회
    public static function getCurrentUser() {
        $id = $_SESSION['user_id'] ?? null;
        return $id ? User::getUserById($id) : null;
    }

    // ✅ 마이페이지 수정
    public static function updateUser() {
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nickname = trim($_POST['nickname']);
        $password = $_POST['password'] ?? '';
        $password2 = $_POST['password2'] ?? '';

        if (!$nickname) {
            $errors[] = "닉네임을 입력해주세요.";
        }

        if ($password !== $password2) {
            $errors[] = "비밀번호가 일치하지 않습니다.";
        }

        if (empty($errors)) {
            $id = $_SESSION['user_id'];

            $conn = DB::connect();
            $stmt = $conn->prepare("SELECT id FROM users WHERE nickname = ? AND id != ?");
            $stmt->bind_param("si", $nickname, $id);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                $errors[] = "이미 사용 중인 닉네임입니다.";
            } else {
                $result = User::updateUser($id, $nickname, $password);
                if ($result) {
                    $_SESSION['nickname'] = $nickname;
                    unset($_SESSION['verified']);
                    echo "<script>alert('정보 수정 완료!'); location.href = 'index.php';</script>";
                    exit;
                } else {
                    $errors[] = "업데이트 실패.";
                }
            }
        }
    }

    return $errors;
}


    // ✅ 비밀번호 확인 단계 (mypage 보호)
    public static function checkPasswordAndRedirect() {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'];
            $id = $_SESSION['user_id'];
            $user = User::getUserById($id);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['verified'] = true;
                header("Location: mypage.php");
                exit;
            } else {
                $errors[] = "비밀번호가 일치하지 않습니다.";
            }
        }

        return $errors;
    }

    // ✅ 인증 요구 (mypage 강제 보호)
    public static function requirePasswordCheck() {
        if (!isset($_SESSION['verified']) || $_SESSION['verified'] !== true) {
            echo "<script>alert('비밀번호 확인이 필요합니다.'); location.href='mypage_check.php';</script>";
            exit;
        }
    }
}
