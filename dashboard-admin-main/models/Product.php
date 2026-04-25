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
            FROM produits p
            LEFT JOIN categories c ON p.category_id = c.id
            ORDER BY p.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Récupère un produit par son ID
     */
<<<<<<< HEAD
    public function getById($id): array|false {
=======
    public function getById(int $id): array|false {
>>>>>>> c9b4dfd97ac92a7c1c6cf615116ce52bc0f3ba68
        $stmt = $this->db->prepare("
            SELECT p.*, c.name AS category_name
            FROM produits p
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
            INSERT INTO produits (name, description, price, stock, image_path, category_id)
            VALUES (:name, :description, :price, :stock, :image_path, :category_id)
        ");
        return $stmt->execute([
            ':name'        => $data['name'],
            ':description' => $data['description'],
            ':price'       => $data['price'],
            ':stock'       => $data['stock'],
<<<<<<< HEAD
            ':image_path'  => $data['image'] ?? null,
=======
            ':image_path'  => $data['image_path'] ?? null,
>>>>>>> c9b4dfd97ac92a7c1c6cf615116ce52bc0f3ba68
            ':category_id' => $data['category_id'] ?: null,
        ]);
    }

    /**
     * Met à jour un produit existant
     */
<<<<<<< HEAD
    public function update($id, array $data): bool {
=======
    public function update(int $id, array $data): bool {
>>>>>>> c9b4dfd97ac92a7c1c6cf615116ce52bc0f3ba68
        $stmt = $this->db->prepare("
            UPDATE produits
            SET name = :name, description = :description, price = :price,
                stock = :stock, image_path = :image_path, category_id = :category_id
            WHERE id = :id
        ");
        return $stmt->execute([
            ':name'        => $data['name'],
            ':description' => $data['description'],
            ':price'       => $data['price'],
            ':stock'       => $data['stock'],
<<<<<<< HEAD
            ':image_path'  => $data['image'] ?? null,
=======
            ':image_path'  => $data['image_path'] ?? null,
>>>>>>> c9b4dfd97ac92a7c1c6cf615116ce52bc0f3ba68
            ':category_id' => $data['category_id'] ?: null,
            ':id'          => $id,
        ]);
    }

    /**
     * Supprime un produit
     */
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM produits WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Compte le total des produits
     */
    public function count(): int {
        return (int) $this->db->query("SELECT COUNT(*) FROM produits")->fetchColumn();
    }

    /**
     * Produits avec stock faible (< 5 unités)
     */
    public function getLowStock(int $threshold = 5): array {
        $stmt = $this->db->prepare("
            SELECT * FROM produits WHERE stock < ? ORDER BY stock ASC
        ");
        $stmt->execute([$threshold]);
        return $stmt->fetchAll();
    }

    /**
     * Top produits les plus vendus
     */
    public function getTopSelling(int $limit = 5): array {
        $stmt = $this->db->prepare("
            SELECT p.id, p.name, p.price, p.image_path as image,
                   SUM(oi.quantity) AS total_sold,
                   SUM(oi.quantity * oi.price) AS revenue
            FROM produits p
            JOIN order_items oi ON p.id = oi.product_id
            GROUP BY p.id, p.name, p.price, p.image_path
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
            SELECT p.id, p.name, p.price, p.image_path as image,
                   COUNT(f.id) AS favorites_count
            FROM produits p
            JOIN favorites f ON p.id = f.product_id
            GROUP BY p.id, p.name, p.price, p.image_path
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
    public function getImage($id): string|null {
        $stmt = $this->db->prepare("SELECT image_path FROM produits WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() ?: null;
    }
}
