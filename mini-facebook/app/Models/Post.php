<?php
require_once __DIR__ . '/../config/database.php';

class Post {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function create($userId, $content, $image = null) {
        $stmt = $this->pdo->prepare("INSERT INTO posts (user_id, content, image) VALUES (:user_id, :content, :image)");
        return $stmt->execute([
            'user_id' => $userId,
            'content' => $content,
            'image' => $image
        ]);
    }

    public function getAll() {
        $stmt = $this->pdo->query("
            SELECT posts.*, users.name, users.profile_image 
            FROM posts 
            JOIN users ON posts.user_id = users.id 
            ORDER BY posts.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("
            SELECT posts.*, users.name, users.profile_image 
            FROM posts 
            JOIN users ON posts.user_id = users.id 
            WHERE posts.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function delete($id, $userId) {
        $stmt = $this->pdo->prepare("DELETE FROM posts WHERE id = :id AND user_id = :user_id");
        return $stmt->execute(['id' => $id, 'user_id' => $userId]);
    }
}
