<?php
// sql
$servername = "localhost";
$username = "root";
$password = "0000";
$dbname = "knockon";

// SQL connect
$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error){
    die("connection fail : " . $conn->connect_error);
}

$word = '';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['query'])){
        $word = $_POST['query'];
    }
}
//$word = trim($word);

$sql = "SELECT id, title, username, date FROM posts WHERE title LIKE '%$word%' OR content LIKE '%$word%' ORDER BY date DESC";
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