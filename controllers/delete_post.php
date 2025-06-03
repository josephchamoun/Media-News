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


if ($post) {
    $collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($postId)]);
    header("Location: /?page=myposts");
    exit;
} else {
    
    header("Location: /?page=myposts&error=Post not found or unauthorized");
    exit;
}


include __DIR__ . '/../views/myposts.php';
