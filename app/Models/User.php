<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function register($name, $email, $password) {
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        return $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }

    public function login($email, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function updateProfile($id, $name, $bio, $profileImage = null) {
        if ($profileImage) {
            $stmt = $this->pdo->prepare("UPDATE users SET name = :name, bio = :bio, profile_image = :profile_image WHERE id = :id");
            return $stmt->execute([
                'name' => $name,
                'bio' => $bio,
                'profile_image' => $profileImage,
                'id' => $id
            ]);
        }
        $stmt = $this->pdo->prepare("UPDATE users SET name = :name, bio = :bio WHERE id = :id");
        return $stmt->execute([
            'name' => $name,
            'bio' => $bio,
            'id' => $id
        ]);
    }

    public function emailExists($email) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetchColumn() > 0;
    }
}
