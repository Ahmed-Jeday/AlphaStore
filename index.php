<?php

require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();




session_start();

$action = $_GET['action'] ?? null;

if ($action === 'updateProfile') {
    require_once(__DIR__ . "/Controller/ProfileController.php");
    updateProfile();
    exit;
}

if ($action === 'getProduits') {
    require_once(__DIR__ . "/Controller/ProduitController.php");
    getAllProduits();
    exit;
}

if ($action === 'getProduitByCategory') {
    require_once(__DIR__ . "/Controller/ProduitController.php");
    getProduitByCategory($_GET["category"]);
    exit;
}
if ($action === "getProduitById") {
    require_once(__DIR__ . "/Controller/ProduitController.php");
    getProduitById($_GET["id"]);
    exit;
}

if ($action === 'getTechProduits') {
    require_once(__DIR__ . "/Controller/ProduitTechController.php");
    getAllTechProduits();
    exit;
}

if ($action === 'getTechProduitByCategory') {
    require_once(__DIR__ . "/Controller/ProduitTechController.php");
    getTechProduitByCategory($_GET["category"]);
    exit;
}

if ($action === "getTechProduitById") {
    require_once(__DIR__ . "/Controller/ProduitTechController.php");
    getTechProduitById($_GET["id"]);
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

if ($action === "getTechProduitDetail") {
    require_once(__DIR__ . "/Controller/ProduitTechController.php");
    getTechProduitDetail($_GET["id"]);
    exit;
}

if ($action === "addReview") {
    require_once(__DIR__ . "/Controller/ReviewController.php");
    AddReview();
    exit;
}

if ($action === "getAllReview") {
    require_once(__DIR__ . "/Controller/ReviewController.php");
    getAllReview();
    exit;
}



if ($action === "addToCart") {
    require_once(__DIR__ . "/Controller/CartController.php");
    addToCart($_POST["productID"], $_POST["quantity"]);
    exit;
}

if ($action === "getCart") {
    require_once(__DIR__ . "/Controller/CartController.php");
    getCart();
    exit;
}
if ($action === "removeFromCart") {
    require_once(__DIR__ . "/Controller/CartController.php");
    removeFromCart($_POST["productID"]);
    exit;
}

if ($action === "updateQuantity") {
    require_once(__DIR__ . "/Controller/CartController.php");
    updateQuantity($_POST["productID"], $_POST["quantity"]);
    exit;
}
if ($action === "chatbot") {
    require_once(__DIR__ . "/Controller/ChatbotController.php");
    handleChat();
    exit;
}
if ($action === "verif_code") {
    require_once(__DIR__ . "/Controller/AuthController.php");
    $result = verifOTP($_POST["email"], $_POST["code"]);

    if ($result === true) {
        header("Location: View/html/signUp.php?verified=1"); // Rediriger vers login après succès
    } else {
        header("Location: View/html/verifie.php?email=" . urlencode($_POST["email"]) . "&error=" . urlencode($result));
    }
    exit;
}

header("Location: View/html/index.html");
exit;
?>