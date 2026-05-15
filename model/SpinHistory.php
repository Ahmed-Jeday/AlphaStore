<?php

class SpinHistory {
    private $pdo;

    public function __construct() {
        $this->pdo = require __DIR__ . "/../config/Database.php";
    }

    /**
     * Check if the user has already spun today
     */
    public function hasSpunToday($userId) {
        $sql = "SELECT COUNT(*) FROM spin_history WHERE user_id = ? AND DATE(created_at) = CURDATE()";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Save a new spin result
     */
    public function saveSpin($userId, $prizeLabel, $prizeNumber, $isWin) {
        $sql = "INSERT INTO spin_history (user_id, prize_number, prize_label, is_win) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$userId, $prizeNumber, $prizeLabel, $isWin]);
    }

    /**
     * Get user spin history
     */
    public function getHistoryByUser($userId, $limit = 10) {
        $sql = "SELECT * FROM spin_history WHERE user_id = :user_id ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':user_id', (int)$userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
