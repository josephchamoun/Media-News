<?php

require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /?page=login");
    exit;
}

$collection = $db->posts;

$cursor = $collection->find(
    [],
    ['sort' => ['created_at' => -1]]
);
$posts = iterator_to_array($cursor);



include __DIR__ . '/../views/home.php';
