<?php include __DIR__ . '/navbar.php'; ?>

<?php

$name = $_SESSION['name'];
$email = $_SESSION['email'] ?? '';



?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>User Profile</title>
  <link rel="stylesheet" href="/CSS/profile.css" />
</head>
<body>
  <div class="container">

    <!-- Profile Section -->
    <section class="profile">
      <div class="avatar">👤</div>
      <div class="user-info">
        <h2><?= htmlspecialchars($name) ?></h2>
        <p><?= htmlspecialchars($email) ?></p>
        <h2 class="followers-count">Followers: <?= htmlspecialchars($followersCount) ?></h2>
      </div>
    </section>




</body>
</html>
