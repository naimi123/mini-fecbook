<?php
require_once __DIR__ . '/../Models/Comment.php';

class CommentController {
    private $commentModel;

    public function __construct() {
        $this->commentModel = new Comment();
    }

    public function create() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $postId = $_POST['post_id'] ?? null;
        $content = trim($_POST['content'] ?? '');

        if ($postId && !empty($content)) {
            $this->commentModel->create($postId, $_SESSION['user_id'], $content);
        }

        header('Location: index.php?page=home');
        exit;
    }

    public function delete() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $commentId = $_POST['comment_id'] ?? null;
        if ($commentId) {
            $this->commentModel->delete($commentId, $_SESSION['user_id']);
        }

        header('Location: index.php?page=home');
        exit;
    }
}
