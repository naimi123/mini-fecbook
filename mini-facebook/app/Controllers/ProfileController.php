<?php
require_once __DIR__ . '/../Models/User.php';

class ProfileController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $user = $this->userModel->findById($_SESSION['user_id']);
        require_once __DIR__ . '/../../views/profile.php';
    }

    public function update() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $profileImage = null;

        if (empty($name)) {
            $_SESSION['error'] = 'Name is required';
            header('Location: index.php?page=profile');
            exit;
        }

        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['profile_image']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (in_array($ext, $allowed)) {
                $newFilename = uniqid() . '.' . $ext;
                $uploadPath = __DIR__ . '/../../public/uploads/' . $newFilename;

                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadPath)) {
                    $profileImage = $newFilename;
                }
            }
        }

        $this->userModel->updateProfile($_SESSION['user_id'], $name, $bio, $profileImage);

        $_SESSION['user_name'] = $name;
        if ($profileImage) {
            $_SESSION['user_profile_image'] = $profileImage;
        }

        $_SESSION['success'] = 'Profile updated successfully';
        header('Location: index.php?page=profile');
        exit;
    }
}
