<?php
session_start();

$action = $_GET['action'] ?? null;

if ($action === 'updateProfile') {
    require_once(__DIR__ . "/Controller/ProfileController.php");
    updateProfile();
    exit;
}

// Routes par défaut ou inconnues : redirection
header("Location: html/index.html");
exit;
?>
