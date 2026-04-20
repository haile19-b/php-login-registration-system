<?php
require_once __DIR__ . '/../config/database.php';

class Auth {
    private $pdo;

    public function __construct() {
        $this->pdo = getDBConnection();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function register($data) {
        $sql = "INSERT INTO users (first_name, last_name, department, gender, hobbies, others, username, password_hash) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->pdo->prepare($sql);
        
        $hobbiesJson = !empty($data['hobbies']) ? json_encode($data['hobbies']) : null;
        $hash = password_hash($data['password'], PASSWORD_DEFAULT);

        return $stmt->execute([
            $data['first_name'],
            $data['last_name'],
            $data['department'],
            $data['gender'],
            $hobbiesJson,
            $data['others'],
            $data['username'],
            $hash
        ]);
    }

    public function login($username, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            return true;
        }
        return false;
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function logout() {
        session_unset();
        session_destroy();
    }

    public function getAllUsers() {
        $stmt = $this->pdo->query("SELECT * FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }
}
