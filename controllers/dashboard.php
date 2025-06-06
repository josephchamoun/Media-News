<?php

require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /?page=login");
    exit;
}

$collection = $db->posts;
$userId = $_SESSION['user_id'];

// Most commented posts
$cursor = $collection->aggregate([
    ['$match'=> ['user_id' => $userId]],
    ['$lookup' => [
        'from' => 'comments',
        'let' => ['postId' => '$_id'],
        'pipeline' => [
            ['$match' => [
                '$expr' => ['$eq' => ['$$postId', ['$toObjectId' => '$post_id']]]
            ]]
        ],
        'as' => 'comments'
    ]],
    ['$addFields' => ['comment_count' => ['$size' => '$comments']]],
    ['$sort' => ['comment_count' => -1]],
    ['$limit' => 5]
]);
$mostCommentedPosts = iterator_to_array($cursor);

// Top commenters on user's posts
// Using the same lookup pattern as your most commented posts query
$topCommentersCursor = $collection->aggregate([
    // Stage 1: Match posts by the logged-in user
    [
        '$match' => [
            'user_id' => $userId
        ]
    ],
    
    // Stage 2: Lookup comments using the same pattern as your working query
    [
        '$lookup' => [
            'from' => 'comments',
            'let' => ['postId' => '$_id'],
            'pipeline' => [
                ['$match' => [
                    '$expr' => ['$eq' => ['$postId', ['$toObjectId' => '$post_id']]]
                ]]
            ],
            'as' => 'comments'
        ]
    ],
    
    // Stage 3: Unwind the comments array
    [
        '$unwind' => '$comments'
    ],
    
    // Stage 4: Group by commenter user_id and count comments
    [
        '$group' => [
            '_id' => '$comments.user_id',
            'commentCount' => ['$sum' => 1],
            'commenterName' => ['$first' => '$comments.user_name']
        ]
    ],
    
    // Stage 5: Sort by comment count descending
    [
        '$sort' => ['commentCount' => -1]
    ],
    
    // Stage 6: Limit results to top 10
    [
        '$limit' => 10
    ],
    
    // Stage 7: Project final format
    [
        '$project' => [
            '_id' => 0,
            'userId' => '$_id',
            'userName' => '$commenterName',
            'totalComments' => '$commentCount'
        ]
    ]
]);

// Debug: Let's also check if we have any comments at all
$debugComments = iterator_to_array($db->comments->find(['post_id' => ['$exists' => true]], ['limit' => 1]));
$debugPosts = iterator_to_array($collection->find(['user_id' => $userId], ['limit' => 1]));

// Store top commenters in array for use in frontend
$topCommenters = iterator_to_array($topCommentersCursor);


// Alternative simpler approach if the above doesn't work
if (empty($topCommenters)) {
    // Try direct approach from comments collection
    $commentsCollection = $db->comments;
    
    // First get all post IDs by the user
    $userPosts = iterator_to_array($collection->find(['user_id' => $userId], ['projection' => ['_id' => 1]]));
    $postIds = array_map(function($post) { return (string)$post['_id']; }, $userPosts);
    
    if (!empty($postIds)) {
        $alternativeCommenters = $commentsCollection->aggregate([
            [
                '$match' => [
                    'post_id' => ['$in' => $postIds]
                ]
            ],
            [
                '$group' => [
                    '_id' => '$user_id',
                    'commentCount' => ['$sum' => 1],
                    'commenterName' => ['$first' => '$user_name']
                ]
            ],
            [
                '$sort' => ['commentCount' => -1]
            ],
            [
                '$limit' => 10
            ],
            [
                '$project' => [
                    '_id' => 0,
                    'userId' => '$_id',
                    'userName' => '$commenterName',
                    'totalComments' => '$commentCount'
                ]
            ]
        ]);
        
        $topCommenters = iterator_to_array($alternativeCommenters);
    }
}

// Process data for additional use cases
$commenterNames = array_column($topCommenters, 'userName');
$commenterCounts = array_column($topCommenters, 'totalComments');

// Create lookup array for quick access by userId
$commentersLookup = [];
foreach ($topCommenters as $commenter) {
    $commentersLookup[$commenter['userId']] = $commenter;
}

// Create summary data
$topCommentersSummary = [
    'totalCommenters' => count($topCommenters),
    'topCommenter' => !empty($topCommenters) ? $topCommenters[0] : null,
    'totalComments' => array_sum($commenterCounts),
    'averageComments' => count($topCommenters) > 0 ? array_sum($commenterCounts) / count($topCommenters) : 0
];

include __DIR__ . '/../views/dashboard.php';

?>