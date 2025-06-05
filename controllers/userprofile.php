<?php

require_once '../config/db.php';
use MongoDB\BSON\ObjectId;

if (!isset($_SESSION['user_id'])) {
    header("Location: /?page=login");
    exit;
}

$collection = $db->users;
$userId = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);


$objectId = new ObjectId($userId);

    
$user = $db->users->findOne(['_id' => $objectId]);


$email = $user['email'] ?? 'No email provided';
$name = $user['name'] ?? 'User';

$collection2 = $db->posts;


$cursor = $collection2->find(
    ['user_id' => $userId],
    ['sort' => ['created_at' => -1]]
);
$posts = iterator_to_array($cursor);



//Check if the user is following the other user

$loggedInUserId = $_SESSION['user_id'];
$profileUserId = $_GET['user_id'] ?? '';

$isFollowing = false;

if (preg_match('/^[a-f\d]{24}$/i', $profileUserId)) {
    $profileUserObjectId = new MongoDB\BSON\ObjectId($profileUserId);

    $userDoc = $db->users->findOne([
        '_id' => $profileUserObjectId,
        'followers' => $loggedInUserId
    ]);

    if ($userDoc) {
        $isFollowing = true;
    }
}





//Count User Followers


if (preg_match('/^[a-f\d]{24}$/i', $profileUserId)) {
    $profileUserObjectId = new MongoDB\BSON\ObjectId($profileUserId);

    $user = $db->users->findOne(['_id' => $profileUserObjectId]);

    if ($user && isset($user['followers'])) {
        $followersCount = count($user['followers']);
    } else {
        $followersCount = 0;
    }
} else {
    $followersCount = 0; 
}









include __DIR__ . '/../views/userprofile.php';
