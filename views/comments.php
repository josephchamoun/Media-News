<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Post with Comments</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/CSS/comments.css">
</head>
<body>

  
<div class="go-back">
  <button onclick="window.location.href='?page=home'" style="background:none;border:none;cursor:pointer;font-size:1.2em;">
    &#8592; Go Back
  </button>
</div>

  <div id="comments"></div>

  <form id="commentForm" method="POST" action="?action=post_comment">
    <input type="hidden" id="parentCommentId" value="">
    <input type="hidden" name="post_id" value="<?= htmlspecialchars($_GET['post_id'] ?? '') ?>">
    <textarea name="content" id="commentText" placeholder="Write a comment..."></textarea>
    <button class="submit-btn" type="submit">Post Comment</button>
  </form>
  <div class="comment-list">
    <h3>Comments</h3>
    <ul id="commentList">
    <?php if (empty($comments)): ?>
    <p>No comments yet. Be the first to comment!</p>
    <?php else: ?>
      <?php foreach ($comments as $comment): ?>
        <li id="comment-<?= htmlspecialchars($comment['_id']) ?>">
          <strong><?= htmlspecialchars($comment['user_name'] ?? 'Anonymous') ?>:</strong>
          <span><?= nl2br(htmlspecialchars($comment['content'] ?? '')) ?></span>
          <span class="timestamp">
            <?php
              if (isset($comment['created_at']) && $comment['created_at'] instanceof MongoDB\BSON\UTCDateTime) {
                echo htmlspecialchars($comment['created_at']->toDateTime()->format('Y-m-d H:i:s'));
              } else {
                echo 'Unknown date';
              }
            ?>
          </span>
          
          <button type="button" onclick="showReplyForm('<?= $comment['_id'] ?>')">Reply</button>

          <!-- Reply Form (hidden by default) -->
          <form class="reply-form" id="reply-form-<?= $comment['_id'] ?>" style="display:none; margin-top:10px;" onsubmit="return submitReply(event, '<?= $comment['_id'] ?>')">
            <input type="hidden" name="post_id" value="<?= htmlspecialchars($_GET['post_id'] ?? '') ?>">
            <input type="hidden" name="parent_comment_id" value="<?= $comment['_id'] ?>">
            <textarea name="content" placeholder="Write a reply..."></textarea>
            <button type="submit">Post Reply</button>
            <button type="button" onclick="hideReplyForm('<?= $comment['_id'] ?>')">Cancel</button>
          </form>

          <!-- Show Replies Button -->
          <?php 
          // Count replies for this comment
          $replyCount = 0;
          if (isset($allReplies)) {
            foreach ($allReplies as $reply) {
              if ($reply['parent_comment_id'] == $comment['_id']) {
                $replyCount++;
              }
            }
          }
          if ($replyCount > 0): ?>
            <button type="button" onclick="toggleReplies('<?= $comment['_id'] ?>')" id="toggle-btn-<?= $comment['_id'] ?>">Show Replies (<?= $replyCount ?>)</button>
          <?php endif; ?>

          <!--display replies if any-->
          <ul class="replies" id="replies-<?= htmlspecialchars($comment['_id']) ?>" style="display:none; margin-left: 20px;">
            <?php 
            if (isset($allReplies)) {
              foreach ($allReplies as $reply) {
                if ($reply['parent_comment_id'] == $comment['_id']) {
            ?>
              <li id="reply-<?= htmlspecialchars($reply['_id']) ?>">
                <strong><?= htmlspecialchars($reply['user_name'] ?? 'Anonymous') ?>:</strong>
                <span><?= nl2br(htmlspecialchars($reply['content'] ?? '')) ?></span>
                <span class="timestamp">
                  <?php
                    if (isset($reply['created_at']) && $reply['created_at'] instanceof MongoDB\BSON\UTCDateTime) {
                      echo htmlspecialchars($reply['created_at']->toDateTime()->format('Y-m-d H:i:s'));
                    } else {
                      echo 'Unknown date';
                    }
                  ?>
                </span>
                
                <!-- Reply to reply button -->
                <button type="button" onclick="showReplyForm('<?= $reply['_id'] ?>')">Reply</button>
                
                <!-- Reply Form for this reply -->
                <form class="reply-form" id="reply-form-<?= $reply['_id'] ?>" style="display:none; margin-top:10px;" onsubmit="return submitReply(event, '<?= $reply['_id'] ?>')">
                  <input type="hidden" name="post_id" value="<?= htmlspecialchars($_GET['post_id'] ?? '') ?>">
                  <input type="hidden" name="parent_comment_id" value="<?= $reply['_id'] ?>">
                  <textarea name="content" placeholder="Write a reply..."></textarea>
                  <button type="submit">Post Reply</button>
                  <button type="button" onclick="hideReplyForm('<?= $reply['_id'] ?>')">Cancel</button>
                </form>

                <!-- Nested replies container -->
                <?php 
                // Count nested replies for this reply
                $nestedReplyCount = 0;
                foreach ($allReplies as $nestedReply) {
                  if ($nestedReply['parent_comment_id'] == $reply['_id']) {
                    $nestedReplyCount++;
                  }
                }
                if ($nestedReplyCount > 0): ?>
                  <button type="button" onclick="toggleReplies('<?= $reply['_id'] ?>')" id="toggle-btn-<?= $reply['_id'] ?>">Show Replies (<?= $nestedReplyCount ?>)</button>
                <?php endif; ?>

                <!-- Display nested replies -->
                <ul class="replies" id="replies-<?= htmlspecialchars($reply['_id']) ?>" style="display:none; margin-left: 20px;">
                  <?php 
                  foreach ($allReplies as $nestedReply) {
                    if ($nestedReply['parent_comment_id'] == $reply['_id']) {
                  ?>
                    <li id="reply-<?= htmlspecialchars($nestedReply['_id']) ?>">
                      <strong><?= htmlspecialchars($nestedReply['user_name'] ?? 'Anonymous') ?>:</strong>
                      <span><?= nl2br(htmlspecialchars($nestedReply['content'] ?? '')) ?></span>
                      <span class="timestamp">
                        <?php
                          if (isset($nestedReply['created_at']) && $nestedReply['created_at'] instanceof MongoDB\BSON\UTCDateTime) {
                            echo htmlspecialchars($nestedReply['created_at']->toDateTime()->format('Y-m-d H:i:s'));
                          } else {
                            echo 'Unknown date';
                          }
                        ?>
                      </span>
                    </li>
                  <?php 
                    }
                  }
                  ?>
                </ul>
              </li>
            <?php 
                }
              }
            }
            ?>
          </ul>
        </li>
      <?php endforeach; ?>
    <?php endif; ?>
    </ul>
  </div>
<script>
function showReplyForm(commentId) {
  document.getElementById('reply-form-' + commentId).style.display = 'block';
}

function hideReplyForm(commentId) {
  document.getElementById('reply-form-' + commentId).style.display = 'none';
}

function toggleReplies(commentId) {
  const replies = document.getElementById('replies-' + commentId);
  const btn = document.getElementById('toggle-btn-' + commentId);
  if (replies.style.display === 'none') {
    replies.style.display = 'block';
    btn.textContent = btn.textContent.replace('Show', 'Hide');
  } else {
    replies.style.display = 'none';
    btn.textContent = btn.textContent.replace('Hide', 'Show');
  }
}

function submitReply(event, parentId) {
  event.preventDefault();
  const form = document.getElementById('reply-form-' + parentId);
  const formData = new FormData(form);

  fetch('?action=post_comment', {
    method: 'POST',
    body: formData,
    headers: {
      'X-Requested-With': 'XMLHttpRequest'
    }
  })
  .then(response => response.text())
  .then(html => {
    const repliesContainer = document.getElementById('replies-' + parentId);
    
    // Create the new reply element with reply functionality
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = html;
    const newReply = tempDiv.firstElementChild;
    
    // Add reply button and form to the new reply
    const replyId = newReply.id.replace('reply-', '');
    const replyButton = document.createElement('button');
    replyButton.type = 'button';
    replyButton.textContent = 'Reply';
    replyButton.onclick = function() { showReplyForm(replyId); };
    
    const replyForm = document.createElement('form');
    replyForm.className = 'reply-form';
    replyForm.id = 'reply-form-' + replyId;
    replyForm.style.display = 'none';
    replyForm.style.marginTop = '10px';
    replyForm.onsubmit = function(e) { return submitReply(e, replyId); };
    
    replyForm.innerHTML = `
      <input type="hidden" name="post_id" value="${form.querySelector('[name="post_id"]').value}">
      <input type="hidden" name="parent_comment_id" value="${replyId}">
      <textarea name="content" placeholder="Write a reply..."></textarea>
      <button type="submit">Post Reply</button>
      <button type="button" onclick="hideReplyForm('${replyId}')">Cancel</button>
    `;
    
    const nestedRepliesContainer = document.createElement('ul');
    nestedRepliesContainer.className = 'replies';
    nestedRepliesContainer.id = 'replies-' + replyId;
    nestedRepliesContainer.style.display = 'none';
    nestedRepliesContainer.style.marginLeft = '20px';
    
    newReply.appendChild(replyButton);
    newReply.appendChild(replyForm);
    newReply.appendChild(nestedRepliesContainer);
    
    repliesContainer.appendChild(newReply);
    
    // Show replies if they were hidden
    if (repliesContainer.style.display === 'none') {
      repliesContainer.style.display = 'block';
      const toggleBtn = document.getElementById('toggle-btn-' + parentId);
      if (toggleBtn) {
        toggleBtn.textContent = toggleBtn.textContent.replace('Show', 'Hide');
      }
    }
    
    // Update reply count in button
    const toggleBtn = document.getElementById('toggle-btn-' + parentId);
    if (toggleBtn) {
      const currentCount = parseInt(toggleBtn.textContent.match(/\((\d+)\)/)?.[1] || 0);
      toggleBtn.textContent = toggleBtn.textContent.replace(/\(\d+\)/, '(' + (currentCount + 1) + ')');
    } else {
      // Create toggle button if it doesn't exist
      const newToggleBtn = document.createElement('button');
      newToggleBtn.type = 'button';
      newToggleBtn.id = 'toggle-btn-' + parentId;
      newToggleBtn.textContent = 'Hide Replies (1)';
      newToggleBtn.onclick = function() { toggleReplies(parentId); };
      repliesContainer.parentNode.insertBefore(newToggleBtn, repliesContainer);
    }
    
    form.reset();
    form.style.display = 'none';
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Error posting reply.');
  });
  return false;
}
</script>

</body>
</html>