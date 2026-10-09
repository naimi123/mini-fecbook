<?php
require_once __DIR__ . '/../Models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function showLogin() {
        if (isset($_SESSION['user_id'])) {
            header('Location: index.php?page=home');
            exit;
        }
        require_once __DIR__ . '/../../views/login.php';
    }

    public function showRegister() {
        if (isset($_SESSION['user_id'])) {
            header('Location: index.php?page=home');
            exit;
        }
        require_once __DIR__ . '/../../views/register.php';
    }

    public function login() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'All fields are required';
            header('Location: index.php?page=login');
            exit;
        }

        $user = $this->userModel->login($email, $password);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_profile_image'] = $user['profile_image'];

            if ($remember) {
                setcookie('remember_email', $email, time() + (86400 * 30), '/');
                setcookie('remember_token', md5($user['password']), time() + (86400 * 30), '/');
            }

            header('Location: index.php?page=home');
            exit;
        }

        $_SESSION['error'] = 'Invalid email or password';
        header('Location: index.php?page=login');
        exit;
    }

    public function register() {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($name) || empty($email) || empty($password)) {
            $_SESSION['error'] = 'All fields are required';
            header('Location: index.php?page=register');
            exit;
        }

        if ($password !== $confirmPassword) {
            $_SESSION['error'] = 'Passwords do not match';
            header('Location: index.php?page=register');
            exit;
        }

        if (strlen($password) < 6) {
            $_SESSION['error'] = 'Password must be at least 6 characters';
            header('Location: index.php?page=register');
            exit;
        }

        if ($this->userModel->emailExists($email)) {
            $_SESSION['error'] = 'Email already exists';
            header('Location: index.php?page=register');
            exit;
        }

        if ($this->userModel->register($name, $email, $password)) {
            $_SESSION['success'] = 'Registration successful! Please login.';
            header('Location: index.php?page=login');
            exit;
        }

        $_SESSION['error'] = 'Registration failed';
        header('Location: index.php?page=register');
        exit;
    }

    public function logout() {
        session_destroy();
        setcookie('remember_email', '', time() - 3600, '/');
        setcookie('remember_token', '', time() - 3600, '/');
        header('Location: index.php?page=login');
        exit;
    }
}
