<?php
require_once '../config/db.php';
use MongoDB\BSON\ObjectId;
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /?page=login");
    exit;
}

$userId = $_SESSION['user_id'];
$collection = $db->users;

// Get the user to follow from POST
$usertofollow = trim($_POST['user_id'] ?? '');

if (!preg_match('/^[a-f\d]{24}$/i', $usertofollow)) {
    header("Location: /?page=error&msg=InvalidUserId");
    exit;
}

$objectId = new ObjectId($usertofollow);

// Prevent following yourself
if ($userId === $usertofollow) {
    header("Location: /?page=error&msg=CannotFollowYourself");
    exit;
}

// Check if user exists
$targetUser = $collection->findOne(['_id' => $objectId]);

if (!$targetUser) {
    header("Location: /?page=error&msg=UserNotFound");
    exit;
}

// Check if already following
$isFollowing = $collection->findOne([
    '_id' => $objectId,
    'followers' => $userId
]);

if ($isFollowing) {
    // Unfollow
    $collection->updateOne(
        ['_id' => $objectId],
        ['$pull' => ['followers' => $userId]]
    );
} else {
    // Follow
    $collection->updateOne(
        ['_id' => $objectId],
        ['$addToSet' => ['followers' => $userId]]
    );
}

// Go back to previous page
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
