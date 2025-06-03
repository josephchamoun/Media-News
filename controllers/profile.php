<?php

require_once '../config/db.php';
use MongoDB\BSON\ObjectId;

if (!isset($_SESSION['user_id'])) {
    header("Location: /?page=login");
    exit;
}

$userid=$_SESSION['user_id'];

if (preg_match('/^[a-f\d]{24}$/i', $userid)) {
    $UserObjectId = new MongoDB\BSON\ObjectId($userid);

    $user = $db->users->findOne(['_id' => $UserObjectId]);

    if ($user && isset($user['followers'])) {
        $followersCount = count($user['followers']);
    } else {
        $followersCount = 0;
    }
} else {
    $followersCount = 0; // Invalid user ID
}


include __DIR__ . '/../views/profile.php';
