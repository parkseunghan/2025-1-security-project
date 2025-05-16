<?php
require_once '../config/config.php';
require_once '../controllers/PostController.php';

$id = $_GET['id'];
PostController::delete($id);
