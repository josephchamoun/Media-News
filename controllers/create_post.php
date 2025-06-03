<?php
require_once '../config/db.php';

$collection = $db->posts;

$content = $_POST['content'];
$created_at = new MongoDB\BSON\UTCDateTime();
$user_id = $_SESSION['user_id'] ?? null;

$insert = $collection->insertOne([
    'content' => $content,
    'created_at' => $created_at,
    'user_id' => $user_id,
    'user_name'=> $_SESSION['name'] ?? 'Anonymous',
]);



header("Location: /?page=myposts");
exit;

