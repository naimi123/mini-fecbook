<?php
require_once __DIR__ . '/../Models/Post.php';

class PostController {
    private $postModel;

    public function __construct() {
        $this->postModel = new Post();
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $posts = $this->postModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_post'])) {
            $content = trim($_POST['content'] ?? '');

            if (!empty($content)) {
                $image = null;

                if (isset($_FILES['post_image']) && $_FILES['post_image']['error'] === 0) {
                    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                    $filename = $_FILES['post_image']['name'];
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                    if (in_array($ext, $allowed)) {
                        $newFilename = uniqid() . '.' . $ext;
                        $uploadPath = __DIR__ . '/../../public/uploads/' . $newFilename;

                        if (move_uploaded_file($_FILES['post_image']['tmp_name'], $uploadPath)) {
                            $image = $newFilename;
                        }
                    }
                }

                $this->postModel->create($_SESSION['user_id'], $content, $image);
            }

            header('Location: index.php?page=home');
            exit;
        }

        require_once __DIR__ . '/../../views/home.php';
    }

    public function delete() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $postId = $_POST['post_id'] ?? null;
        if ($postId) {
            $this->postModel->delete($postId, $_SESSION['user_id']);
        }

        header('Location: index.php?page=home');
        exit;
    }
}
