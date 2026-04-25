<?php

require_once(__DIR__ . "/../model/Order.php");
require_once(__DIR__ . "/../model/OrderItem.php");
require_once(__DIR__ . "/../model/Cart.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Handle placing an order
 */
function placeOrder()
{
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $cartModel = new Cart();
    $cartItems = $cartModel->getCart($user_id);

    if (empty($cartItems)) {
        echo json_encode(['status' => 'error', 'message' => 'Cart is empty']);
        exit;
    }

    $total_price = $cartModel->getCartTotal($user_id);
    
    $orderModel = new Order();
    $order_id = $orderModel->createOrder($user_id, $total_price);

    if ($order_id) {
        $orderItemModel = new OrderItem();
        $items = [];
        foreach ($cartItems as $item) {
            $items[] = [
                'product_id' => $item['produit_id'],
                'quantity' => $item['quantite'],
                'price' => $item['price']
            ];
        }

        if ($orderItemModel->addItems($order_id, $items)) {
            $cartModel->clearCart($user_id);
            echo json_encode(['status' => 'success', 'order_id' => $order_id]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add order items']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create order']);
    }
    exit;
}

/**
 * Get all orders for the logged-in user
 */
function getUserOrders()
{
    if (!isset($_SESSION['user_id'])) {
        echo json_encode([]);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $orderModel = new Order();
    $orders = $orderModel->getOrdersByUserId($user_id);
    
    echo json_encode($orders);
    exit;
}

/**
 * Get details of a specific order
 */
function getOrderDetails($order_id)
{
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        exit;
    }

    $orderModel = new Order();
    $order = $orderModel->getOrderById($order_id);

    // Security check: ensure the order belongs to the user
    if (!$order || $order['user_id'] != $_SESSION['user_id']) {
        echo json_encode(['status' => 'error', 'message' => 'Order not found or access denied']);
        exit;
    }

    $orderItemModel = new OrderItem();
    $items = $orderItemModel->getItemsByOrderId($order_id);
    
    $order['items'] = $items;
    
    echo json_encode($order);
    exit;
}

// Simple routing based on action parameter if needed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'place_order':
            placeOrder();
            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'get_user_orders':
            getUserOrders();
            break;
        case 'get_order_details':
            if (isset($_GET['order_id'])) {
                getOrderDetails($_GET['order_id']);
            }
            break;
    }
}
