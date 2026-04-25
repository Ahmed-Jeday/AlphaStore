<?php

require_once(__DIR__ . "/../model/Cart.php");


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
