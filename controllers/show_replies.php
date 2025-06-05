<?php

require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /?page=login");
    exit;
}

$collection = $db->comments;
$postId= $_GET['post_id'] ?? null;
$userId = $_SESSION['user_id'];
$parentCommentId = $_GET['parent_comment_id'] ?? null;

$cursor = $collection->find(
    [
        'post_id' => $postId,
        'parent_comment_id' => $parentCommentId
    ],
    ['sort' => ['created_at' => -1]]
);
$comments = iterator_to_array($cursor);

include __DIR__ . '/../views/comments.php';
