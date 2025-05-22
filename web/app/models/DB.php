<?php
require_once __DIR__ . '/../../config/config.php';

class DB {
    public static function connect() {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            die("DB 연결 실패: " . $conn->connect_error);
        }
        return $conn;
    }
}
