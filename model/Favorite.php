<?php


class Favorite {
    private $pdo ;

    public function __construct($pdo=null)
    {
         $this->pdo = require __DIR__ . "/../config/Database.php";
    }

    function addFavorite($user_id, $product_id){
        $stmt = $this->pdo->prepare("INSERT INTO favorites (user_id, product_id) VALUES (:user_id, :product_id)");
        $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
    }

    function removeFavorite($user_id, $product_id){
        $stmt = $this->pdo->prepare("DELETE FROM favorites WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
    }

    function getFavorites($user_id){
        $stmt = $this->pdo->prepare("SELECT * FROM favorites WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function isFavorite($user_id, $product_id){
        $stmt = $this->pdo->prepare("SELECT * FROM favorites WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function getFavoriteByUser($user_id)
    {
        $stmt = $this->pdo->prepare('
            SELECT f.*, 
                   COALESCE(p.name, pt.name) as name, 
                   COALESCE(p.price, pt.price) as price, 
                   COALESCE(p.image_path, pt.image_path) as image_path 
            FROM favorites f 
            LEFT JOIN produits p ON f.product_id = p.id 
            LEFT JOIN produits_t pt ON f.product_id = pt.id 
            WHERE f.user_id = :user_id 
            ORDER BY f.created_at DESC
        ');
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function exist($user_id, $product_id)
    {
        $stmt = $this->pdo->prepare('select * from favorites 
                                    where user_id = :user_id
                                    and product_id = :product_id
                                    limit 1;
                                   
        ');
        $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
        if ($stmt->fetch(PDO::FETCH_ASSOC)){
            return true;
        }else{
            return false;
        }
    }

}