<?php

$host = 'localhost';
$dbname = "AlphaStore";
$username = "root";
$password = '';

try {
    $dbConnection = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Set PDO error mode to exception
    $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbConnection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Connection error: " . $e->getMessage());
}

return $dbConnection;
?>