<?php


require_once(__DIR__ . "/../model/Favorite.php");

function toggle()
{
    if (!isset($_SESSION["user_id"])) {
        echo json_encode(["status" => "not_logged_in"]);
        exit;
    }
    $productID = $_POST["product_id"];
    $userID = $_SESSION["user_id"];
    $favorite = new Favorite();
    if($favorite->exist($userID, $productID)){
        $favorite->removeFavorite($userID, $productID);
        echo json_encode(["status" => "removed"]);
    }else{
        $favorite->addFavorite($userID, $productID);
        echo json_encode(["status" => "added"]);
    }
}

function getFavorites($userID)
{
    $favorite = new Favorite();
    $favorites = $favorite->getFavoriteByUser($userID);

    echo json_encode($favorites);
}

