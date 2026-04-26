<?php

class OrderItem
{
    private $id;
    private $order_id;
    private $product_id;
    private $quantity;
    private $price;

    private $pdo;

    public function __construct($id = null, $order_id = null, $product_id = null, $quantity = null, $price = null)
    {
        $this->id = $id;
        $this->order_id = $order_id;
        $this->product_id = $product_id;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->pdo = require __DIR__ . "/../config/Database.php";
    }

    public function addItems($order_id, $items)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)");
            
            foreach ($items as $item) {
                $stmt->execute([
                    'order_id' => $order_id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }
            return true;
        } catch (PDOException $e) {
            error_log("Error adding order items: " . $e->getMessage());
            return false;
        }
    }

    public function getItemsByOrderId($order_id)
    {
        $stmt = $this->pdo->prepare("SELECT oi.*, 
                                            COALESCE(p.name, pt.name) as product_name, 
                                            COALESCE(p.image_path, pt.image_path) as image_path 
                                    FROM order_items oi 
                                    LEFT JOIN produits p ON oi.product_id = p.id 
                                    LEFT JOIN produits_t pt ON oi.product_id = pt.id
                                    WHERE oi.order_id = :order_id");
        $stmt->execute(['order_id' => $order_id]);
        return $stmt->fetchAll();
    }
}
