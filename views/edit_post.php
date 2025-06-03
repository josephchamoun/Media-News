<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Post</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/CSS/edit_post.css">
</head>
<body>
    <div class="popup">
        <button class="close-btn" onclick="window.history.back();">&times;</button>
        <h2>Edit Post</h2>
        <form action="?action=edit_post" method="POST">
            <input type="hidden" name="post_id" value="<?= htmlspecialchars($_GET['post_id'] ?? '') ?>">
            <textarea name="content" required><?php echo htmlspecialchars($post['content'] ?? ''); ?></textarea>
            <div class="actions">
                <button type="button" class="cancel-btn" onclick="window.history.back();">Cancel</button>
                <button type="submit" class="save-btn">Save</button>
            </div>
        </form>
    </div>
</body>
</html>