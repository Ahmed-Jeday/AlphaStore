<?php

class Review {
    private $pdo;

    public function __construct($pdo = null) {
        $this->pdo = $pdo ?? require __DIR__ . "/../config/Database.php";
    }

    public function getReviewsByProductId($productId) {
        // On renomme dynamiquement 'comment' en 'body' pour que le JavaScript s'y retrouve
        $stmt = $this->pdo->prepare("
            SELECT r.id, r.user_id, r.product_id, r.rating, r.comment AS body, r.created_at, u.name as author 
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            WHERE r.product_id = :product_id 
            ORDER BY r.created_at DESC
        ");
        $stmt->execute(['product_id' => $productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   
    public function addReview($productId, $userId, $rating, $title, $coment) {
        // Comme la BDD n'a pas de colonne "title", on peut le fusionner avec le commentaire
        $fullComment = $title . " - " . $coment; 

        $stmt = $this->pdo->prepare("INSERT INTO reviews (product_id, user_id, rating, comment, created_at) VALUES (:product_id, :user_id, :rating, :comment, NOW())");
        return $stmt->execute([
            'product_id' => $productId,
            'user_id' => $userId,
            'rating' => $rating,
            'comment' => $fullComment // On insère dans la colonne 'comment'
        ]);
    }
   
}