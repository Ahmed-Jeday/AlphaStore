<?php

class Order
{
    private $id;
    private $user_id;
    private $total_price;
    private $status;
    private $created_at;

    private $pdo;

    public function __construct($id = null, $user_id = null, $total_price = null, $status = 'pending', $created_at = null)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->total_price = $total_price;
        $this->status = $status;
        $this->created_at = $created_at;
        $this->pdo = require __DIR__ . "/../config/Database.php";
    }

    public function createOrder($user_id, $total_price)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO orders (user_id, total_price, status) VALUES (:user_id, :total_price, 'pending')");
            $stmt->execute([
                'user_id' => $user_id,
                'total_price' => $total_price
            ]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error creating order: " . $e->getMessage());
            return false;
        }
    }

    public function getOrderById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getOrdersByUserId($user_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll();
    }

    public function updateStatus($id, $status)
    {
        $allowed_statuses = ['pending', 'paid', 'shipped', 'delivered', 'cancelled'];
        if (!in_array($status, $allowed_statuses)) {
            return false;
        }

        $stmt = $this->pdo->prepare("UPDATE orders SET status = :status WHERE id = :id");
        return $stmt->execute([
            'status' => $status,
            'id' => $id
        ]);
    }

    public function getAllOrders()
    {
        $stmt = $this->pdo->query("SELECT o.*, u.name as user_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC");
        return $stmt->fetchAll();
    }
}
