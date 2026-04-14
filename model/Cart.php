<?php


class Cart {
    private $pdo ;

    public function __construct($pdo=null)
    {
         $this->pdo = require __DIR__ . "/../config/Database.php";
    }

    function addToCart($user_id, $product_id, $quantity = 1)
    {
        $stmt = $this->pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)");
        $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id, 'quantity' => $quantity]);
    }

    function removeFromCart($user_id, $product_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM cart WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
    }

    function getCart($user_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM cart WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

 

    function updateCart($user_id, $product_id, $quantity)
    {
        $stmt = $this->pdo->prepare("UPDATE cart SET quantity = :quantity WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id, 'quantity' => $quantity]);
    }

    function clearCart($user_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM cart WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $user_id]);
    }

  

    function getCartTotal($user_id)
    {
        $stmt = $this->pdo->prepare("SELECT SUM(p.price * c.quantity) as total FROM cart c JOIN produits p ON c.product_id = p.id WHERE c.user_id = :user_id");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    function getCartItem($user_id, $product_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM cart WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


  
 
   

  

}