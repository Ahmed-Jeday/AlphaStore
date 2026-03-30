<?php
require_once __DIR__ . "/../../Controller/AuthController.php";

$message = completeReset();
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
        <h1>Changement de mot de passe</h1>
        <div class="message <?= strpos($message, 'succes') !== false ? 'success' : 'error' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
        
        <?php if (strpos($message, 'succes') !== false): ?>
            <p>Votre mot de passe a été mis à jour avec succès.</p>
            <a href="signUp.php" style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">Se connecter</a>
        <?php else: ?>
            <p><a href="reset-password.php?token=<?= htmlspecialchars($_POST['token'] ?? '') ?>">Réessayer avec le lien</a></p>
            <p><a href="forget-password.php">Demander un nouveau lien</a></p>
        <?php endif; ?>
    </div>
</body>
</html>
