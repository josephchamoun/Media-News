<?php

require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /?page=login");
    exit;
}

$collection = $db->comments;
$postId = $_GET['post_id'] ?? null;
$userId = $_SESSION['user_id'];

// Get all comments for this post (both parent comments and replies)
$cursor = $collection->find(
    ['post_id' => $postId],
    ['sort' => ['created_at' => -1]]
);
$allComments = iterator_to_array($cursor);

// Separate parent comments and replies
$comments = [];
$allReplies = [];

foreach ($allComments as $comment) {
    if ($comment['parent_comment_id'] === null) {
        $comments[] = $comment;
    } else {
        $allReplies[] = $comment;
    }
}

include __DIR__ . '/../views/comments.php';