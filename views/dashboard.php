<?php include __DIR__ . '/navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/CSS/dashboard.css">
    <title>Dashboard</title>
</head>
<body>
    
    <div class="dashboard-container">
        <h1>Top 5 Most Commented Posts</h1>
        <ul>
            <?php if (empty($mostCommentedPosts)): ?>
                <li>No posts found.</li>
            <?php else: ?>
                <?php foreach ($mostCommentedPosts as $post): ?>
                    <li>
                        <strong>Content:</strong> <?= htmlspecialchars($post['content'] ?? 'Untitled') ?><br>
                        <strong>Comments:</strong> <?= $post['comment_count'] ?? 0 ?>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <h1>Top 10 Commenters</h1>
        <ul>
            <?php if (empty($topCommenters)): ?>
                <li>No commenters found.</li>
            <?php else: ?>
                <?php foreach ($topCommenters as $index => $commenter): ?>
                    <li>
                        <strong>Rank:</strong> #<?= $index + 1 ?><br>
                        <strong>User:</strong> <?= htmlspecialchars($commenter['userName'] ?? 'Unknown User') ?><br>
                        <strong>Comments Count:</strong> <?= $commenter['totalComments'] ?? 0 ?>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <!-- Summary Statistics -->
        <h2>Summary</h2>
        <div class="summary-section">
            <p><strong>Total Active Commenters:</strong> <?= $topCommentersSummary['totalCommenters'] ?? 0 ?></p>
            <?php if (!empty($topCommentersSummary['topCommenter'])): ?>
                <p><strong>Most Active Commenter:</strong> <?= htmlspecialchars($topCommentersSummary['topCommenter']['userName']) ?> 
                   (<?= $topCommentersSummary['topCommenter']['totalComments'] ?> comments)</p>
            <?php endif; ?>
            <p><strong>Total Comments:</strong> <?= $topCommentersSummary['totalComments'] ?? 0 ?></p>
            <p><strong>Average Comments per User:</strong> <?= number_format($topCommentersSummary['averageComments'] ?? 0, 1) ?></p>
        </div>
    </div>

</body>
</html>