<?php
/**
 * Modèle Produit
 * Gère toutes les opérations DB relatives aux produits
 */

require_once __DIR__ . '/../config/database.php';

class Product {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Récupère tous les produits avec leur catégorie
     */
    public function getAll(): array {
        $stmt = $this->db->query("
            SELECT p.*, c.name AS category_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            ORDER BY p.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Récupère un produit par son ID
     */
    public function getById(int $id): array|false {
        $stmt = $this->db->prepare("
            SELECT p.*, c.name AS category_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Crée un nouveau produit
     */
    public function create(array $data): bool {
        $stmt = $this->db->prepare("
            INSERT INTO products (name, description, price, stock, image, category_id)
            VALUES (:name, :description, :price, :stock, :image, :category_id)
        ");
        return $stmt->execute([
            ':name'        => $data['name'],
            ':description' => $data['description'],
            ':price'       => $data['price'],
            ':stock'       => $data['stock'],
            ':image'       => $data['image'] ?? null,
            ':category_id' => $data['category_id'] ?: null,
        ]);
    }

    /**
     * Met à jour un produit existant
     */
    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("
            UPDATE products
            SET name = :name, description = :description, price = :price,
                stock = :stock, image = :image, category_id = :category_id
            WHERE id = :id
        ");
        return $stmt->execute([
            ':name'        => $data['name'],
            ':description' => $data['description'],
            ':price'       => $data['price'],
            ':stock'       => $data['stock'],
            ':image'       => $data['image'] ?? null,
            ':category_id' => $data['category_id'] ?: null,
            ':id'          => $id,
        ]);
    }

    /**
     * Supprime un produit
     */
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Compte le total des produits
     */
    public function count(): int {
        return (int) $this->db->query("SELECT COUNT(*) FROM products")->fetchColumn();
    }

    /**
     * Produits avec stock faible (< 5 unités)
     */
    public function getLowStock(int $threshold = 5): array {
        $stmt = $this->db->prepare("
            SELECT * FROM products WHERE stock < ? ORDER BY stock ASC
        ");
        $stmt->execute([$threshold]);
        return $stmt->fetchAll();
    }

    /**
     * Top produits les plus vendus
     */
    public function getTopSelling(int $limit = 5): array {
        $stmt = $this->db->prepare("
            SELECT p.id, p.name, p.price, p.image,
                   SUM(oi.quantity) AS total_sold,
                   SUM(oi.quantity * oi.unit_price) AS revenue
            FROM products p
            JOIN order_items oi ON p.id = oi.product_id
            GROUP BY p.id, p.name, p.price, p.image
            ORDER BY total_sold DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    /**
     * Produits les plus aimés (favoris)
     */
    public function getMostFavorited(int $limit = 5): array {
        $stmt = $this->db->prepare("
            SELECT p.id, p.name, p.price, p.image,
                   COUNT(f.id) AS favorites_count
            FROM products p
            JOIN favorites f ON p.id = f.product_id
            GROUP BY p.id, p.name, p.price, p.image
            ORDER BY favorites_count DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    /**
     * Récupère toutes les catégories
     */
    public function getCategories(): array {
        return $this->db->query("SELECT * FROM categories ORDER BY name")->fetchAll();
    }

    /**
     * Récupère l'image actuelle d'un produit
     */
    public function getImage(int $id): string|null {
        $stmt = $this->db->prepare("SELECT image FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() ?: null;
    }
}
