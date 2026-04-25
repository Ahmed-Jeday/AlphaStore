<?php

class ProduitTech
{
    private $id;
    private $name;
    private $description;
    private $price;
    private $stock;
    private $sku;
    private $image_path;
    private $category;
    private $color;
    private $created_at;

    private $pdo;

    public function __construct($id = null, $name = null, $description = null, $price = null, $stock = null, $sku = null, $image_path = null, $category = null, $color = null, $created_at = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->stock = $stock;
        $this->sku = $sku;
        $this->image_path = $image_path;
        $this->category = $category;
        $this->color = $color;
        $this->created_at = $created_at;
        $this->pdo = require __DIR__ . "/../config/Database.php";
    }

    public function getTechProduitById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM produits_t WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllTechProduits()
    {
        $stmt = $this->pdo->query("SELECT * FROM produits_t");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFilteredTechProduits($categories = [], $minPrice = null, $maxPrice = null, $sortBy = 'default')
    {
        $sql = "SELECT * FROM produits_t WHERE 1=1";
        $params = [];

        if (!empty($categories)) {
            $placeholders = implode(",", array_fill(0, count($categories), "?"));
            $sql .= " AND category IN ($placeholders)";
            $params = array_merge($params, $categories);
        }

        if ($minPrice !== null) {
            $sql .= " AND price >= ?";
            $params[] = $minPrice;
        }

        if ($maxPrice !== null) {
            $sql .= " AND price <= ?";
            $params[] = $maxPrice;
        }

        if ($sortBy === 'price_low') {
            $sql .= " ORDER BY price ASC";
        } elseif ($sortBy === 'price_high') {
            $sql .= " ORDER BY price DESC";
        } else {
            $sql .= " ORDER BY id DESC";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
