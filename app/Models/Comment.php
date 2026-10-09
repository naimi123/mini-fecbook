<?php
require_once __DIR__ . '/../config/database.php';

class Comment {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function create($postId, $userId, $content) {
        $stmt = $this->pdo->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (:post_id, :user_id, :content)");
        return $stmt->execute([
            'post_id' => $postId,
            'user_id' => $userId,
            'content' => $content
        ]);
    }

    public function getByPostId($postId) {
        $stmt = $this->pdo->prepare("
            SELECT comments.*, users.name, users.profile_image 
            FROM comments 
            JOIN users ON comments.user_id = users.id 
            WHERE comments.post_id = :post_id 
            ORDER BY comments.created_at ASC
        ");
        $stmt->execute(['post_id' => $postId]);
        return $stmt->fetchAll();
    }

    public function delete($id, $userId) {
        $stmt = $this->pdo->prepare("DELETE FROM comments WHERE id = :id AND user_id = :user_id");
        return $stmt->execute(['id' => $id, 'user_id' => $userId]);
    }
}
