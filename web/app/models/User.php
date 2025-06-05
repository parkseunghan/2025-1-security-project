<?php
require_once __DIR__ . '/DB.php';

class User {
    // ✅ 회원가입
    public static function createUser($username, $nickname, $password) {
        $conn = DB::connect();
        $stmt = $conn->prepare("INSERT INTO users (username, nickname, password) VALUES (?, ?, ?)");
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("sss", $username, $nickname, $hashed);
        return $stmt->execute();
    }

    // ✅ 로그인용 유저 조회
    public static function getUserByUsernameAndPassword($username, $password) {
        $conn = DB::connect();
        $stmt = $conn->prepare("SELECT id, username, nickname, password, is_admin FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']); // 보안상 응답에서 제거
            return $user;
        }

        return null;
    }

    // ✅ 단일 유저 조회
    public static function getUserById($id) {
        $conn = DB::connect();
        $stmt = $conn->prepare("SELECT id, username, nickname, password, is_admin FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // ✅ 닉네임 변경
    public static function updateNickname($id, $nickname) {
        $conn = DB::connect();
        $stmt = $conn->prepare("UPDATE users SET nickname = ? WHERE id = ?");
        $stmt->bind_param("si", $nickname, $id);
        return $stmt->execute();
    }

    // ✅ 비밀번호 변경
    public static function updatePassword($id, $password) {
        $conn = DB::connect();
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("si", $hashed, $id);
        return $stmt->execute();
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
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND is_admin = 0");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // ✅ 관리자 여부 확인
    public static function isAdmin($id) {
        $user = self::getUserById($id);
        return $user && $user['is_admin'] == 1;
    }

    // ✅ 닉네임 및 비밀번호 동시 수정 함수
    public static function updateUser($id, $nickname, $password = '') {
        $conn = DB::connect();

        if ($password) {
            $stmt = $conn->prepare("UPDATE users SET nickname = ?, password = ? WHERE id = ?");
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bind_param("ssi", $nickname, $hashed, $id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET nickname = ? WHERE id = ?");
            $stmt->bind_param("si", $nickname, $id);
        }

        return $stmt->execute();
    }

}
