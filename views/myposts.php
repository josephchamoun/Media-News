<?php include __DIR__ . '/navbar.php'; ?>
<?php
$editingPostId = $_GET['edit_id'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= htmlspecialchars($name) ?>'s Posts</title>
  <link rel="stylesheet" href="/CSS/myposts.css" />
</head>
<body>

  <h1><?= htmlspecialchars($name) ?>'s Posts</h1>

  <form method="POST" action="?action=create_post" id="postForm">
    <textarea name="content" placeholder="Write your new post here..." required></textarea>
    <button type="submit">Add Post</button>
  </form>

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
          <div class="post-actions">
            <a href="?page=edit_post&post_id=<?= urlencode((string)$post['_id']) ?>">Edit</a>
            <a href="?action=delete_post&post_id=<?= urlencode((string)$post['_id']) ?>" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
        </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

</body>
</html>
