<?php
require_once 'DB.php';

class User {
    public static function createUser($username, $nickname, $password) {
        $conn = DB::connect();
        $sql = "INSERT INTO users (username, nickname, password) 
                VALUES ('$username', '$nickname', '$password')";
        return $conn->query($sql);
    }

    public static function getUserByUsernameAndPassword($username, $password) {
        $conn = DB::connect();
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }

    public static function getUserById($id) {
        $conn = DB::connect();
        $sql = "SELECT * FROM users WHERE id = $id";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }
}
