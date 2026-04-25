<?php

require_once __DIR__ . '/../model/ProduitTech.php';
require_once __DIR__ . '/../model/Favorite.php';

function getAllTechProduits()
{
    $model = new ProduitTech();
    $produits = $model->getAllTechProduits();

    if (isset($_SESSION['user_id'])) {
        $favoriteModel = new Favorite();
        foreach ($produits as &$p) {
            $p['is_favorite'] = $favoriteModel->exist($_SESSION['user_id'], $p['id']);
        }
    }

    header('Content-Type: application/json');
    echo json_encode($produits);
    exit;
}

function getTechProduitByCategory($category)
{
    $categories = explode(",", $category);
    $minPrice = $_GET['minPrice'] ?? null;
    $maxPrice = $_GET['maxPrice'] ?? null;
    $sortBy = $_GET['sortBy'] ?? 'default';

    $model = new ProduitTech();
    $produits = $model->getFilteredTechProduits($categories, $minPrice, $maxPrice, $sortBy);

    if (isset($_SESSION['user_id'])) {
        $favoriteModel = new Favorite();
        foreach ($produits as &$p) {
            $p['is_favorite'] = $favoriteModel->exist($_SESSION['user_id'], $p['id']);
        }
    }

    header('Content-Type: application/json');
    echo json_encode($produits);
    exit;
}

function getTechProduitById($id)
{
    $model = new ProduitTech();
    $produit = $model->getTechProduitById($id);

    if ($produit && isset($_SESSION['user_id'])) {
        $favoriteModel = new Favorite();
        $produit['is_favorite'] = $favoriteModel->exist($_SESSION['user_id'], $produit['id']);
    }

    header('Content-Type: application/json');
    echo json_encode($produit);
    exit;
}

function getTechProduitDetail($id)
{
    $model = new ProduitTech();
    $product = $model->getTechProduitById($id);

    if ($product) {
        // Handle images (similar to Produit model but simplified as tech products might not have folders yet)
        $product['images'] = [$product['image_path']];
        // We could add logic here to look for more images in a folder if needed
        
        header('Content-Type: application/json');
        echo json_encode([$product]); // Return as array to match frontend expectation
        exit;
    }

    header('Content-Type: application/json');
    echo json_encode([]);
    exit;
}
