<?php
require_once __DIR__ . '/../models/User.php';

class AdminController {
    // ✅ 관리자 인증 검사
    private static function isAdminSession(): bool {
        return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
    }

    // ✅ 전체 사용자 조회 (관리자 전용)
    public static function getAllUsers() {
        if (!self::isAdminSession()) {
            die("⛔ 접근 권한이 없습니다.");
        }
        return User::getAllUsers();
    }

    // ✅ 사용자 삭제 (관리자 전용)
    public static function deleteUser($id) {
        if (!self::isAdminSession()) {
            die("⛔ 관리자만 삭제할 수 있습니다.");
        }

        // 자신을 삭제하는 행위 방지
        if ($_SESSION['user_id'] == $id) {
            die("⚠️ 본인 계정은 삭제할 수 없습니다.");
        }

        return User::deleteUser($id);
    }
}
