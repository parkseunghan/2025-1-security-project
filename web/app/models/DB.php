<?php
require_once __DIR__ . '/../../config/config.php';

class DB {
    public static function connect() {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // 예외 기반 오류 처리 활성화

        try {
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            $conn->set_charset('utf8mb4'); // 문자셋 설정 (SQL 인젝션 대응 및 이모지 대응)
            return $conn;
        } catch (mysqli_sql_exception $e) {
            error_log("❌ DB 연결 오류: " . $e->getMessage());
            die("⚠️ 내부 서버 오류입니다. 관리자에게 문의하세요."); // 내부 정보 노출 방지
        }
    }
}
