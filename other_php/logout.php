<?php
session_start();

setcookie("user_id_cookie", '', time() - 3600, '/');
session_destroy();

header("Location: index.html");
exit();
?>