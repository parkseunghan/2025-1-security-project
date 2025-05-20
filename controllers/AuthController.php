<?php
require_once '../models/User.php';

class AuthController {
    public static function register() {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $nickname = $_POST['nickname'];
            $password = $_POST['password']; // ❌ 평문 저장

            if (empty($username) || empty($nickname) || empty($password)) {
                $errors[] = "모든 항목을 입력해주세요.";
            } else {
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
                    session_start();
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['nickname'] = $user['nickname'];

                    if (isset($_POST['remember_me'])) {
                        setcookie('remember_me', $user['id'], time() + (86400 * 7), "/");
                    }

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

    public static function logout() {
        session_start();
        session_unset();
        session_destroy();

        setcookie('remember_me', '', time() - 3600, "/");

        echo "<script>alert('로그아웃 완료!'); location.href='index.php';</script>";
        exit;
    }
}
