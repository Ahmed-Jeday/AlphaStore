<?php

class LoginLog {
    private $pdo;

    public function __construct(){
        $this->pdo = require __DIR__ . "/../config/Database.php";
    }

    public function logLoginAttempt($userId, $email, $status) {
        $sql = "INSERT INTO login_logs (user_id, email, status, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$userId, $email, $status]);
    }

    public function getLogsByUserId($userId) {
        $sql = "SELECT id, user_id, email, status, created_at FROM login_logs WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllLogs() {
        $sql = "SELECT id, user_id, email, status, created_at FROM login_logs ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
