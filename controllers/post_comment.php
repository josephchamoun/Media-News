<?php
require_once '../config/db.php';

$collection = $db->comments;

$content = $_POST['content'];
$created_at = new MongoDB\BSON\UTCDateTime();
$user_id = $_SESSION['user_id'] ?? null;
$post_id = $_POST['post_id'] ?? null;

// Accept both 'parent_comment_id' and 'parent_id' for flexibility
$parent_comment_id = $_POST['parent_comment_id'] ?? ($_POST['parent_id'] ?? null);

$user_name = $_SESSION['name'] ?? 'Anonymous';

$insert = $collection->insertOne([
    'content' => $content,
    'created_at' => $created_at,
    'user_id' => $user_id,
    'user_name'=> $user_name,
    'post_id' => $post_id,
    'parent_comment_id' => $parent_comment_id,
]);

// Check if AJAX request (for replies)
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($isAjax && $parent_comment_id) {
    $replyId = (string)($insert->getInsertedId());
    ?>
    <li id="reply-<?= htmlspecialchars($replyId) ?>">
      <strong><?= htmlspecialchars($user_name) ?>:</strong>
      <span><?= nl2br(htmlspecialchars($content)) ?></span>
      <span class="timestamp">
        <?= htmlspecialchars((new DateTime())->format('Y-m-d H:i:s')) ?>
      </span>
    </li>
    <?php
    exit;
}

// For normal (non-AJAX) requests, redirect back
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;