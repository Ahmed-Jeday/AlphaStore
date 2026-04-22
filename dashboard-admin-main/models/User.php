<?php
/**
 * Modèle Utilisateur
 * Gère toutes les opérations DB relatives aux utilisateurs
 */

require_once __DIR__ . '/../config/database.php';

class User {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Récupère tous les utilisateurs avec stats de commandes
     */
    public function getAll(): array {
        $stmt = $this->db->query("
            SELECT u.*,
                   COUNT(o.id) AS order_count,
                   COALESCE(SUM(o.total), 0) AS total_spent
            FROM users u
            LEFT JOIN orders o ON u.id = o.user_id
            GROUP BY u.id
            ORDER BY u.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Récupère un utilisateur par son ID
     */
    public function getById(int $id): array|false {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Compte le total des utilisateurs actifs
     */
    public function count(): int {
        return (int) $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
    }

    /**
     * Active ou désactive un compte utilisateur
     */
    public function toggleStatus(int $id): bool {
        $stmt = $this->db->prepare("
            UPDATE users SET is_active = NOT is_active WHERE id = ? AND role = 'user'
        ");
        return $stmt->execute([$id]);
    }

    /**
     * Supprime un utilisateur
     */
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ? AND role = 'user'");
        return $stmt->execute([$id]);
    }

    /**
     * Authentification admin
     */
    public function authenticate(string $email, string $password): array|false {
        $stmt = $this->db->prepare("
            SELECT * FROM users WHERE email = ? AND role = 'admin' AND is_active = 1
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
