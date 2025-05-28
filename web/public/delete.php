<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/controllers/PostController.php';

$id = $_GET['id'];
PostController::delete($id);
