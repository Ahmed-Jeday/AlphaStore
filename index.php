<?php
session_start();

$action = $_GET['action'] ?? null;

if ($action === 'updateProfile') {
    require_once(__DIR__ . "/Controller/ProfileController.php");
    updateProfile();
    exit;
}

if ($action ==='getProduits') {
    require_once(__DIR__ . "/Controller/ProduitController.php");
    getAllProduits();
    exit;
}

if ($action ==='getProduitByCategory') {
    require_once(__DIR__ . "/Controller/ProduitController.php");
    getProduitByCategory($_GET["category"]);
    exit;
}
if ($action === "getProduitById") {
    require_once(__DIR__ . "/Controller/ProduitController.php");
    getProduitById($_GET["id"]);
    exit;
}

if ($action === "toggleFavorite") {
    require_once(__DIR__ . "/Controller/FavoriteController.php");
    toggle();
    exit;
}

if ($action === "getFavorites") {
    if (!isset($_SESSION["user_id"])) {
        echo json_encode([]);
        exit;
    }
    require_once(__DIR__ . "/Controller/FavoriteController.php");
    getFavorites($_SESSION["user_id"]);
    exit;
}

if ($action === "getAllimage") {
    require_once(__DIR__ . "/Controller/ProduitController.php");
    getAllimage($_GET["id"]);
    exit;
}



// Routes par défaut ou inconnues : redirection
header("Location: View/html/index.html");
exit;
?>
