<?php
require_once '../config/db.php';

$collection = $db->users;

$email = $_POST['email'];
$password = $_POST['password'];

$user = $collection->findOne(['email' => $email]);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = (string)$user['_id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['name'] = $user['name'];
    header("Location: /?page=profile");
} else {
    die("Invalid credentials.");
}
