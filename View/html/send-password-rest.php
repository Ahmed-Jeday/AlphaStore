<?php
require_once __DIR__ . "/../../Controller/AuthController.php";

// On s'assure que la requête vient du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $message = requestReset();
} else {
    $message = "Accès non autorisé.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Réinitialisation du mot de passe</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .container { max-width: 600px; margin: 50px auto; text-align: center; }
        .message { padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .success { background-color: #d1fae5; color: #065f46; border: 1px solid #10b981; }
        .error { background-color: #fee2e2; color: #991b1b; border: 1px solid #ef4444; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Résultat de la demande</h1>
        <div class="message <?= strpos($message, 'succes') !== false ? 'success' : 'error' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
        <p><a href="signUp.php">Retour à la page de connexion</a></p>
    </div>
</body>
</html>
