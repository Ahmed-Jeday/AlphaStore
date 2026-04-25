<?php


class Cart {
    private $pdo ;

    public function __construct($pdo=null)
    {
         $this->pdo = require __DIR__ . "/../config/Database.php";
    }

    function addToCart($user_id, $product_id, $quantity = 1)
    {
        // Check if item already exists
        $existing = $this->getCartItem($user_id, $product_id);
        if ($existing) {
            $newQuantity = $existing['quantite'] + $quantity;
            $this->updateCart($user_id, $product_id, $newQuantity);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO cart (user_id, produit_id, quantite, created_at) VALUES (:user_id, :produit_id, :quantite, NOW())");
            $stmt->execute(['user_id' => $user_id, 'produit_id' => $product_id, 'quantite' => $quantity]);
        }
    }

    function removeFromCart($user_id, $product_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM cart WHERE user_id = :user_id AND produit_id = :produit_id");
        $stmt->execute(['user_id' => $user_id, 'produit_id' => $product_id]);
    }

    function getCart($user_id)
    {
        $stmt = $this->pdo->prepare("SELECT c.*, p.name, p.price, p.image_path FROM cart c JOIN produits p ON c.produit_id = p.id WHERE c.user_id = :user_id");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

 

    function updateCart($user_id, $product_id, $quantity)
    {
        $stmt = $this->pdo->prepare("UPDATE cart SET quantite = :quantite WHERE user_id = :user_id AND produit_id = :produit_id");
        $stmt->execute(['user_id' => $user_id, 'produit_id' => $product_id, 'quantite' => $quantity]);
    }

    function clearCart($user_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM cart WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $user_id]);
    }

  

    function getCartTotal($user_id)
    {
        $stmt = $this->pdo->prepare("SELECT SUM(p.price * c.quantite) as total FROM cart c JOIN produits p ON c.produit_id = p.id WHERE c.user_id = :user_id");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    function getCartItem($user_id, $product_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM cart WHERE user_id = :user_id AND produit_id = :produit_id");
        $stmt->execute(['user_id' => $user_id, 'produit_id' => $product_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

 

}