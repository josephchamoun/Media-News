<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /?page=login");
    exit;
}

$userId = $_SESSION['user_id'];
$content = $_POST['content'];
$postId = $_POST['post_id'] ?? null;

$collection = $db->posts;

$collection->updateOne(
    ['_id' => new MongoDB\BSON\ObjectId($postId), 'user_id' => $userId],
    ['$set' => [
        'content' => $content,
        'updated_at' => new MongoDB\BSON\UTCDateTime()
    ]]
);

header("Location: /?page=myposts");
exit;
