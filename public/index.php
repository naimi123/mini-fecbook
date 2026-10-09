<?php
session_start();

define('BASE_URL', '/mini-facebook/public/');

require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/PostController.php';
require_once __DIR__ . '/../app/Controllers/CommentController.php';
require_once __DIR__ . '/../app/Controllers/ProfileController.php';

$page = $_GET['page'] ?? 'home';

if (isset($_COOKIE['remember_email']) && isset($_COOKIE['remember_token']) && !isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/../app/Models/User.php';
    $userModel = new User();
    $email = $_COOKIE['remember_email'];

    $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && md5($user['password']) === $_COOKIE['remember_token']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_profile_image'] = $user['profile_image'];
    }
}

switch ($page) {
    case 'login':
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login();
        } else {
            $controller->showLogin();
        }
        break;

    case 'register':
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->register();
        } else {
            $controller->showRegister();
        }
        break;

    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    case 'home':
        $controller = new PostController();
        $controller->index();
        break;

    case 'create_post':
        $controller = new PostController();
        $controller->create();
        break;

    case 'delete_post':
        $controller = new PostController();
        $controller->delete();
        break;

    case 'add_comment':
        $controller = new CommentController();
        $controller->create();
        break;

    case 'delete_comment':
        $controller = new CommentController();
        $controller->delete();
        break;

    case 'profile':
        $controller = new ProfileController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->update();
        } else {
            $controller->index();
        }
        break;

    default:
        header('Location: index.php?page=home');
        exit;
}
