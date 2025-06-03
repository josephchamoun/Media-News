<?php include __DIR__ . '/navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>User Profile</title>
  <link rel="stylesheet" href="/CSS/userprofile.css" />
</head>
<body>
  <div class="container">

    <section class="profile">
      <div class="avatar">👤</div>
      <div class="user-info">
        <h2><?= htmlspecialchars($name) ?>'s Posts</h2>
        <h2>Followers: <?= htmlspecialchars($followersCount) ?></h2>

        <form method="POST" action="?action=follow">
          <input type="hidden" name="user_id" value="<?= htmlspecialchars($_GET['user_id'] ?? '') ?>">
          <button type="submit"><?= $isFollowing ? 'Unfollow' : 'Follow' ?></button>
        </form>

      </div>
    </section>


    <div id="postsContainer">
    <?php if (empty($posts)): ?>
      <p>No posts yet. Start by adding one above!</p>
    <?php else: ?>
      <?php foreach ($posts as $post): ?>
        <div class="post">
          <p><?= nl2br(htmlspecialchars($post['content'] ?? '')) ?></p>
          <div class="post-meta">
            Posted on 
            <?php
              if (isset($post['created_at']) && $post['created_at'] instanceof MongoDB\BSON\UTCDateTime) {
                echo htmlspecialchars($post['created_at']->toDateTime()->format('Y-m-d H:i:s'));
              } else {
                echo 'Unknown date';
              }
            ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>




</body>
</html>
