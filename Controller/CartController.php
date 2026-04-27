<?php

require_once(__DIR__ . "/../model/Cart.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


function addToCart($productID, $quantity)
{
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'not_logged_in']);
        return;
    }
    $user_id = $_SESSION['user_id'];
    $cart = new Cart();
    $cart->addToCart($user_id, $productID, $quantity);
    echo json_encode(['status' => 'success']);
}

function getCart()
{
    if (!isset($_SESSION['user_id'])) {
        echo json_encode([]);
        return;
    }
    $user_id = $_SESSION['user_id'];
    $cartModel = new Cart();
    $items = $cartModel->getCart($user_id);
    echo json_encode($items);
}

function removeFromCart($productID)
{
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'not_logged_in']);
        return;
    }
    $user_id = $_SESSION['user_id'];
    $cart = new Cart();
    $cart->removeFromCart($user_id, $productID);
    echo json_encode(['status' => 'success']);
}

function updateQuantity($productID, $quantity)
{
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'not_logged_in']);
        return;
    }
    $user_id = $_SESSION['user_id'];
    $cart = new Cart();
    $cart->updateCart($user_id, $productID, $quantity);
    echo json_encode(['status' => 'success']);
}

// Handle form submissions only when CartController.php is accessed directly
if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
            addToCart($_POST['product_id'], $_POST['quantity']);
            $section = isset($_POST['section']) ? '&section=' . urlencode($_POST['section']) : '';
            header("Location: ../View/user_Dashboard/index.php?success=added_to_cart" . $section);
            exit;
        }
    }
}
