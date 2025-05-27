<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/DB.php';

class AuthController {
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

            $userRes = $conn->query("SELECT id FROM users WHERE username = '$username'");
            $nickRes = $conn->query("SELECT id FROM users WHERE nickname = '$nickname'");

            if ($userRes && $userRes->num_rows > 0) {
                $errors[] = "이미 존재하는 아이디입니다.";
            }
            if ($nickRes && $nickRes->num_rows > 0) {
                $errors[] = "이미 존재하는 닉네임입니다.";
            }

            if (empty($errors)) {
                $result = User::createUser($username, $nickname, $password);
                if ($result) {
                    echo "<script>alert('회원가입 성공!'); location.href='login.php';</script>";
                    exit;
                } else {
                    $errors[] = "회원가입 실패. 이미 존재하는 아이디일 수 있습니다.";
                }
            }
        }

        return $errors;
    }

    public static function login() {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            if (empty($username) || empty($password)) {
                $errors[] = "아이디와 비밀번호를 입력해주세요.";
            } else {
                $user = User::getUserByUsernameAndPassword($username, $password);
                if ($user) {
                    if (session_status() === PHP_SESSION_NONE) session_start();
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['nickname'] = $user['nickname'];
                    $_SESSION['is_admin'] = $user['is_admin'];

                    if (isset($_POST['remember_me'])) {
                        setcookie('remember_me', $user['id'], time() + (86400 * 7), "/");
                    }

                    $log = sprintf(
                        "[%s] LOGIN: user_id=%s | user_name=%s | user_nickname=%s | IP=%s | UA=%s\n",
                        date("Y-m-d H:i:s"),
                        $user['id'],
                        $user['username'],
                        $user['nickname'],
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

    public static function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_unset();
        session_destroy();
        setcookie('remember_me', '', time() - 3600, "/");

        echo "<script>alert('로그아웃 완료!'); location.href='index.php';</script>";
        exit;
    }

    public static function getCurrentUser() {
        $id = $_SESSION['id'];
        $conn = DB::connect();
        $res = $conn->query("SELECT username, nickname FROM users WHERE id = $id");
        return $res->fetch_assoc();
    }

    public static function mypage() {
        $errors = [];
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nickname = $_POST['nickname'];
            $password = $_POST['password'];
            $password2 = $_POST['password2'];
    
            if (empty($nickname)) {
                $errors[] = "닉네임을 입력해주세요.";
            }
            if ($password !== $password2) {
                $errors[] = "비밀번호가 일치하지 않습니다.";
            }
    
            if (empty($errors)) {
                $id = $_SESSION['id'];
                $conn = DB::connect();
                $check = $conn->query("SELECT id FROM users WHERE nickname = '$nickname' AND id != $id");
                if ($check && $check->num_rows > 0) {
                    $errors[] = "이미 사용 중인 닉네임입니다.";
                } else {
                    $sql = "UPDATE users SET nickname='$nickname'";
                    if (!empty($password)) {
                        $sql .= ", password='$password'";
                    }
                    $sql .= " WHERE id=$id";
                    $conn->query($sql);
                    $_SESSION['nickname'] = $nickname;
                    unset($_SESSION['verified']);
                    echo "<script>
                        alert('정보 수정 완료!');
                        location.href = 'index.php';
                    </script>";
                    exit;
                }
            }
        }
    
        return $errors;
    }

    public static function checkPasswordAndRedirect() {
        $errors = [];
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'];
            $id = $_SESSION['id'];
            $conn = DB::connect();
    
            $res = $conn->query("SELECT password FROM users WHERE id = $id");
            $user = $res->fetch_assoc();
    
            if ($user && $user['password'] === $password) { // ❌ 해시 안 씀 (취약한 버전)
                $_SESSION['verified'] = true;
                header("Location: mypage.php");
                exit;
            } else {
                $errors[] = "비밀번호가 일치하지 않습니다.";
            }
        }
    
        return $errors;
    }
    

    public static function requirePasswordCheck() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['verified']) || $_SESSION['verified'] !== true) {
            echo "<script>alert('비밀번호 확인이 필요합니다.'); location.href='mypage_check.php';</script>";
            exit;
        }
    }
    
    public static function verifyPassword($inputPassword) {
        $id = $_SESSION['id'];
        $conn = DB::connect();
        $res = $conn->query("SELECT password FROM users WHERE id = $id");
        $user = $res->fetch_assoc();
    
        if (!$user || $user['password'] !== $inputPassword) {
            return false;
        }
    
        $_SESSION['verified'] = true;
        return true;
    }
    
    
}
