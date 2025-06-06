<?php
require_once '../config/db.php';

$collection = $db->users;

$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$name = $_POST['name'];

$existing = $collection->findOne(['email' => $email]);
if ($existing) {
    die("User already exists.");
}

$insert = $collection->insertOne([
    'name' => $name,
    'email' => $email,
    'password' => $password
]);

$_SESSION['user_id'] = (string)$insert->getInsertedId();
$_SESSION['email'] = $email;
$_SESSION['name'] = $name;

header("Location: /?page=home");
exit;

