<?php
// config/config.php
$mysqli = new mysqli("localhost", "guest", "guest", "board");
if ($mysqli->connect_error) {
    die("DB 연결 실패: " . $mysqli->connect_error);
}
session_start();
?>
