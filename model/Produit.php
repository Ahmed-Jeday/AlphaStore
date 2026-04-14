<?php


class Produit
{
    private $id;
    private $name;
    private $description;
    private $price;
    private $stock;
    private $sku;
    private $image_path;
    private $category_id;
    private $color_id;
    private $created_at;

    private $pdo;

    public function __construct($id = null, $name = null, $description = null, $price = null, $stock = null, $sku = null, $image_path = null, $category_id = null, $color_id = null, $created_at = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->stock = $stock;
        $this->sku = $sku;
        $this->image_path = $image_path;
        $this->category_id = $category_id;
        $this->color_id = $color_id;
        $this->created_at = $created_at;
        $this->pdo = require __DIR__ . "/../config/Database.php";
    }

    function getProduitById($id)
    {
        $stmt = $this->pdo->prepare("SELECT p.*, c.name as category, co.name as color 
                                    FROM produits p 
                                    LEFT JOIN categories c ON p.category_id = c.id 
                                    LEFT JOIN colors co ON p.color_id = co.id 
                                    WHERE p.id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function getAllProduits()
    {
        $stmt = $this->pdo->query("SELECT p.*, c.name as category, co.name as color 
                                   FROM produits p 
                                   LEFT JOIN categories c ON p.category_id = c.id 
                                   LEFT JOIN colors co ON p.color_id = co.id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getFilteredProduits($categories, $minPrice = null, $maxPrice = null, $sortBy = 'default')
    {
        $sql = "SELECT p.*, c.name as category, co.name as color 
                FROM produits p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN colors co ON p.color_id = co.id";
        $params = [];
        $where = " WHERE 1=1";

        if (!empty($categories)) {
            $placeholders = implode(",", array_fill(0, count($categories), "?"));
            $where .= " AND c.name IN ($placeholders)";
            $params = array_merge($params, $categories);
        }

        $sql .= $where;

        if ($minPrice !== null) {
            $sql .= " AND p.price >= ?";
            $params[] = $minPrice;
        }

        if ($maxPrice !== null) {
            $sql .= " AND p.price <= ?";
            $params[] = $maxPrice;
        }

        if ($sortBy === 'price_low') {
            $sql .= " ORDER BY p.price ASC";
        } elseif ($sortBy === 'price_high') {
            $sql .= " ORDER BY p.price DESC";
        } else {
            $sql .= " ORDER BY p.id DESC";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllProducts($id)
    {
        $stmt = $this->pdo->prepare("SELECT p.*, c.name as category, co.name as color 
                                    FROM produits p 
                                    LEFT JOIN categories c ON p.category_id = c.id 
                                    LEFT JOIN colors co ON p.color_id = co.id 
                                    WHERE p.id = :id");
        $stmt->execute(['id' => $id]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($products as &$product) {
            $imagePath = $product['image_path'];
            if (!$imagePath) {
                $product['images'] = [];
                continue;
            }
            $folderRelative = dirname($imagePath);
            $folderAbsolute = __DIR__ . "/../public/" . $folderRelative;

            // Supporte plusieurs extensions d'images
            $images = glob($folderAbsolute . "/*.{jpg,jpeg,png,webp,avif}", GLOB_BRACE);

            if ($images) {
                // On transforme les chemins absolus en chemins relatifs par rapport au dossier public
                $product['images'] = array_map(function($img) use ($folderRelative) {
                    $filename = basename($img);
                    return $folderRelative . "/" . $filename;
                }, $images);
            } else {
                $product['images'] = [$imagePath];
            }
        }

        return $products;
    }

}
