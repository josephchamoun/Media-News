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
          <h3>
            <span onclick="window.location.href='?page=userprofile&user_id=<?= urlencode($post['user_id']) ?>'" style="cursor:pointer;">
              <?= htmlspecialchars($post['user_name'] ?? 'Anonymous') ?>
            </span>
          </h3>

          <p><?= nl2br(htmlspecialchars($post['content'] ?? '')) ?></p>

          <div class="comment-section">
            <button class="comment-button" onclick="saveScrollAndGoToComments('<?= urlencode($post['_id']) ?>')">
              View Comments
            </button>
          </div>
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


  <script>

          window.addEventListener('DOMContentLoaded', function() {
    const scroll = localStorage.getItem('homeScroll');
    if (scroll !== null) {
      window.scrollTo(0, parseInt(scroll, 10));
      localStorage.removeItem('homeScroll');
      localStorage.removeItem('homePage');
    }
  });




    function saveScrollAndGoToComments(postId) {
    localStorage.setItem('homeScroll', window.scrollY);
    localStorage.setItem('homePage', 'myposts');
    window.location.href = '?page=comments&post_id=' + postId;
  }

    let timer;

    document.getElementById("searchBar").addEventListener("input", function () {
      const query = this.value.trim();

      clearTimeout(timer);
      timer = setTimeout(() => {
        if (query.length > 0) {
          
          fetch("?action=search_people&name=" + encodeURIComponent(query))
            .then(res => {
              if (!res.ok) {
                throw new Error('Network response was not ok');
              }
              return res.json();
            })
            .then(users => {
              const resultsDiv = document.getElementById("results");
              if (users.length > 0) {
                resultsDiv.innerHTML = users
                  .map(u => `
                    <div class="search-result">
                      <a href="?page=userprofile&user_id=${u.id}" class="user-link">
                        ${u.name}
                      </a>
                    </div>
                  `)
                  .join("");
              } else {
                resultsDiv.innerHTML = "<p>No users found</p>";
              }
            })
            .catch(error => {
              console.error('Search error:', error);
              document.getElementById("results").innerHTML = "<p>Search error occurred</p>";
            });
        } else {
          document.getElementById("results").innerHTML = "";
        }
      }, 300); 
    });

    // Optional: Hide search results when clicking outside
    document.addEventListener('click', function(event) {
      const searchBar = document.getElementById('searchBar');
      const results = document.getElementById('results');
      
      if (!searchBar.contains(event.target) && !results.contains(event.target)) {
        results.innerHTML = '';
      }
    });



  </script>


</body>
</html>
