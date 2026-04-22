<?php
/**
 * Modèle Commande
 * Gère toutes les opérations DB relatives aux commandes
 */

require_once __DIR__ . '/../config/database.php';

class Order {
    private PDO $db;

    public const STATUSES = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Récupère toutes les commandes avec infos utilisateur
     */
    public function getAll(): array {
        $stmt = $this->db->query("
            SELECT o.*, u.name AS user_name, u.email AS user_email,
                   COUNT(oi.id) AS items_count
            FROM orders o
            JOIN users u ON o.user_id = u.id
            LEFT JOIN order_items oi ON o.id = oi.order_id
            GROUP BY o.id
            ORDER BY o.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Récupère une commande avec ses articles
     */
    public function getById(int $id): array|false {
        $stmt = $this->db->prepare("
            SELECT o.*, u.name AS user_name, u.email AS user_email
            FROM orders o
            JOIN users u ON o.user_id = u.id
            WHERE o.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Récupère les articles d'une commande
     */
    public function getItems(int $orderId): array {
        $stmt = $this->db->prepare("
            SELECT oi.*, p.name AS product_name, p.image AS product_image
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    /**
     * Met à jour le statut d'une commande
     */
    public function updateStatus(int $id, string $status): bool {
        if (!in_array($status, self::STATUSES)) return false;
        $stmt = $this->db->prepare("UPDATE orders SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    /**
     * Compte le total des commandes
     */
    public function count(): int {
        return (int) $this->db->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    }

    /**
     * Calcule le chiffre d'affaires total
     */
    public function getTotalRevenue(): float {
        return (float) $this->db->query(
            "SELECT COALESCE(SUM(total), 0) FROM orders WHERE status != 'cancelled'"
        )->fetchColumn();
    }

    /**
     * Ventes des 30 derniers jours (pour graphique)
     */
    public function getDailySales(int $days = 30): array {
        $stmt = $this->db->prepare("
            SELECT DATE(created_at) AS sale_date,
                   COUNT(*) AS order_count,
                   SUM(total) AS daily_revenue
            FROM orders
            WHERE created_at >= NOW() - INTERVAL ? DAY
              AND status != 'cancelled'
            GROUP BY DATE(created_at)
            ORDER BY sale_date ASC
        ");
        $stmt->execute([$days]);
        return $stmt->fetchAll();
    }

    /**
     * Revenus mensuels de l'année en cours
     */
    public function getMonthlyRevenue(): array {
        $stmt = $this->db->query("
            SELECT MONTH(created_at) AS month,
                   MONTHNAME(created_at) AS month_name,
                   SUM(total) AS revenue
            FROM orders
            WHERE YEAR(created_at) = YEAR(NOW())
              AND status != 'cancelled'
            GROUP BY MONTH(created_at), MONTHNAME(created_at)
            ORDER BY month ASC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Commandes récentes (pour dashboard)
     */
    public function getRecent(int $limit = 5): array {
        $stmt = $this->db->prepare("
            SELECT o.*, u.name AS user_name
            FROM orders o
            JOIN users u ON o.user_id = u.id
            ORDER BY o.created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
}
