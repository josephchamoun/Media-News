<?php
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

try {
    $name = $_GET['name'] ?? '';

    if (empty($name)) {
        echo json_encode([]);
        exit;
    }

    // Create regex for case-insensitive search
    $regex = new MongoDB\BSON\Regex($name, 'i');

    // Search for users whose name contains the search term
    $users = $db->users->find(
        ['name' => $regex],
        ['limit' => 10]
    );

    $results = [];
    foreach ($users as $user) {
        $results[] = [
            'id' => (string) $user['_id'],
            'name' => $user['name'] ?? 'Unknown'
        ];
    }

    echo json_encode($results);

} catch (Exception $e) {
    // Log error and return empty array
    error_log("Search error: " . $e->getMessage());
    echo json_encode([]);
}
?>