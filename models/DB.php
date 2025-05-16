<?php
class DB {
    public static function connect() {
        $conn = new mysqli("localhost", "guest", "guest", "board");
        if ($conn->connect_error) {
            die("DB 연결 실패: " . $conn->connect_error);
        }
        return $conn;
    }
}
