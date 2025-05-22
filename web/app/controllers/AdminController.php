<?php
require_once __DIR__ . '/../models/User.php';

class AdminController {
    // ✅ 전체 사용자 조회
    public static function getAllUsers() {
        return User::getAllUsers();
    }

    // ✅ 사용자 삭제
    public static function deleteUser($id) {
        return User::deleteUser($id);
    }
}