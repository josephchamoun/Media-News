<?php
session_start();

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action === 'register') {
        require __DIR__ . '/../controllers/register.php';
        exit;
    }
    if ($action === 'login') {
        require __DIR__ . '/../controllers/login.php';
        exit;
    }
    if ($action === 'create_post') {
        require __DIR__ . '/../controllers/create_post.php';
        exit;
    }
    if ($action === 'edit_post') {
    require __DIR__ . '/../controllers/edit_post.php';
    exit;
  }

    if ($action === 'follow') {
        require __DIR__ . '/../controllers/follow.php';
        exit;
    }
    
    if ($action === 'delete_post') {
        require __DIR__ . '/../controllers/delete_post.php';
        exit;
    }
    if ($action === 'search_people') {

        require_once __DIR__ . '/../config/db.php';
        header('Content-Type: application/json');
        
        try {
            $name = $_GET['name'] ?? '';
            
            if (empty($name)) {
                echo json_encode([]);
                exit;
            }
            
 
            $regex = new MongoDB\BSON\Regex($name, 'i');
            
         
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
            error_log("Search error: " . $e->getMessage());
            echo json_encode([]);
        }
        exit;
    }
    if ($action === 'logout') {
        session_destroy();
        header('Location: /');
        exit;
    }

    http_response_code(404);
    echo "Action not found.";
    exit;
}

$page = isset($_GET['page']) ? basename($_GET['page']) : 'welcome';
$allowed_pages = ['welcome', 'login', 'register', 'mainpage', 'profile', 'myposts', 'home', 'userprofile', 'edit_post'];

if (!in_array($page, $allowed_pages)) {
    http_response_code(404);
    echo "404 Not Found";
    exit;
}

switch ($page) {
    case 'myposts':
        require __DIR__ . '/../controllers/getmyposts.php';
        break;
    case 'home':
        require __DIR__ . '/../controllers/home.php';
        break;
    case 'profile':
        require __DIR__ . '/../controllers/profile.php';
        break;
    case 'userprofile':
        require __DIR__ . '/../controllers/userprofile.php';
        break;
    case 'edit_post':
        require __DIR__ . '/../controllers/display_post_info.php';
        break;
        


    default:
        include __DIR__ . "/../views/$page.php";
        break;
}
?>