<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . "/../model/Review.php");
require_once(__DIR__ . "/../model/Produit.php");
require_once(__DIR__ . "/../model/User.php");

function AddReview(){
    header('Content-Type: application/json; charset=utf-8');
    $input = json_decode(file_get_contents('php://input'), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'message' => 'Mauvaise requête JSON']);
        return;
    }

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
        return;
    }

    $productId = $input['productId'] ?? null;
    $rating = $input['rating'] ?? null;
    $title = trim($input['title'] ?? '');
    $body = trim($input['body'] ?? '');

    if (!$productId || !$rating || !$title || !$body) {
        echo json_encode(['success' => false, 'message' => 'Tous les champs requis doivent être remplis']);
        return;
    }

    $reviewModel = new Review();

    try {
        $res = $reviewModel->addReview(
            $productId,
            $_SESSION['user_id'],      
            $rating,
            $title,
            $body
        );
    } catch (Exception $e) {
        error_log('ReviewController AddReview error: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Erreur serveur lors de l enregistrement de l avis']);
        return;
    }

    if ($res) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de l enregistrement en base de données']);
    }
}


function getAllReview(){
    header('Content-Type: application/json; charset=utf-8');
    $productId = $_GET['productId'] ?? null;
    if (!$productId) {
        echo json_encode(['success' => false, 'message' => 'ID produit manquant']);
        return;
    }

    $reviewModel = new Review();
    $res = $reviewModel->getReviewsByProductId($productId);
    
    // Convertir les noms de colonnes DB vers les noms attendus par le JS (body -> body)
    // Et formater la date si nécessaire
    echo json_encode(['success' => true, 'data' => $res]);
}