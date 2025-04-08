<?php // 메인에서 클릭한 포스트를 그에 맞는 포스트 원형으로 제공
// SQL
$servername = "localhost";
$username = "root";
$password = "0000";
$dbname = "knockon";

// SQL connect
$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error){
    die("connection fail : " . $conn->connect_error);
}

$sql = "SELECT id, title, username, date FROM posts ORDER BY date DESC";
$result = $conn->query($sql);

$posts = [];

if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $posts[] = $row;
    }
}

header('Content-Type: application/json');

echo json_encode($posts);

$conn->close();
?>