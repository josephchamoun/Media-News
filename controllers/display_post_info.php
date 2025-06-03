<?php

require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /?page=login");
    exit;
}

$collection = $db->posts;
$postId= $_GET['post_id'] ?? null;

$post= $collection->findOne(
    ['_id' => new MongoDB\BSON\ObjectId($postId), 'user_id' => $_SESSION['user_id']]
);



include __DIR__ . '/../views/edit_post.php';
