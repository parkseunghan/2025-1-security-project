<?php
require_once 'DB.php';

class User {
    // ✅ 회원가입
    public static function createUser($username, $nickname, $password) {
        $conn = DB::connect();
        $sql = "INSERT INTO users (username, nickname, password) 
                VALUES ('$username', '$nickname', '$password')";
        return $conn->query($sql);
    }

    // ✅ 로그인용 유저 조회
    public static function getUserByUsernameAndPassword($username, $password) {
        $conn = DB::connect();
        $sql = "SELECT id, username, nickname, is_admin FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }

    // ✅ 단일 유저 조회
    public static function getUserById($id) {
        $conn = DB::connect();
        $sql = "SELECT * FROM users WHERE id = $id";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }

    // ✅ 닉네임 변경
    public static function updateNickname($id, $nickname) {
        $conn = DB::connect();
        $stmt = $conn->prepare("UPDATE users SET nickname = '$nickname' WHERE id = $id");
        $stmt->execute();
    }

    // ✅ 비밀번호 변경
    public static function updatePassword($id, $password) {
        $conn = DB::connect();
        $stmt = $conn->prepare("UPDATE users SET password = '$password' WHERE id = $id");
        $stmt->execute();
    }

    // ✅ 전체 사용자 조회 (관리자 전용)
    public static function getAllUsers() {
        $conn = DB::connect();
        $res = $conn->query("SELECT id, username, nickname, is_admin FROM users ORDER BY id ASC");
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    // ✅ 사용자 삭제 (관리자 전용)
    public static function deleteUser($id) {
        $conn = DB::connect();
        return $conn->query("DELETE FROM users WHERE id = $id AND is_admin = 0");
    }

    // ✅ 관리자 여부 확인 (임시: id가 1번인 경우만 관리자)
    public static function isAdmin($id) {
        return $id == 1;
    }
}
