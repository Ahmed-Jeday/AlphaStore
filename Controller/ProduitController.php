<?php



require_once __DIR__ . '/../model/Produit.php';
require_once __DIR__ . '/../model/Favorite.php';

function getAllProduits()
{
    $produitModel = new Produit();
    $produits = $produitModel->getAllProduits();
    
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

function getProduitByCategory($category)
{
    $categories = explode(",", $category);
    $minPrice = $_GET['minPrice'] ?? null;
    $maxPrice = $_GET['maxPrice'] ?? null;
    $sortBy = $_GET['sortBy'] ?? 'default';

    $produitModel = new Produit();
    $produits = $produitModel->getFilteredProduits($categories, $minPrice, $maxPrice, $sortBy);

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

function getProduitById($id){
    $produitModel = new Produit();
    $produit = $produitModel->getProduitById($id);

    if ($produit && isset($_SESSION['user_id'])) {
        $favoriteModel = new Favorite();
        $produit['is_favorite'] = $favoriteModel->exist($_SESSION['user_id'], $produit['id']);
    }

    header('Content-Type: application/json');
    echo json_encode($produit);
    exit;
}

//get all images product (for the product detail)

function getAllimage($id)
{
    $model = new Produit();
$products = $model->getAllProducts($id);

header('Content-Type: application/json');
echo json_encode($products);
exit;
}