<?php
/**
 * UTILITAIRE — Réinitialiser le mot de passe admin
 * Accès : http://localhost/admin/fix_password.php
 * ⚠️ SUPPRIMER CE FICHIER après utilisation !
 */

require_once __DIR__ . '/config/database.php';

$message = '';
$success = false;

// ── Action : mettre à jour le mot de passe ─────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['new_password'] ?? 'password';
    $email       = 'admin@shop.com';

    // Générer le hash bcrypt directement en PHP
    $hash = password_hash($newPassword, PASSWORD_BCRYPT);

    $db   = Database::getInstance();
    $stmt = $db->prepare("UPDATE users SET password = ? WHERE email = ?");
    $ok   = $stmt->execute([$hash, $email]);

    if ($ok && $stmt->rowCount() > 0) {
        $success = true;
        $message = "✅ Mot de passe mis à jour avec succès !<br>
                    <strong>Email :</strong> $email<br>
                    <strong>Mot de passe :</strong> " . htmlspecialchars($newPassword) . "<br>
                    <strong>Hash généré :</strong> <code style='font-size:11px;word-break:break-all;'>$hash</code>";
    } else {
        $message = "❌ Erreur : aucun utilisateur trouvé avec l'email $email.<br>
                    Vérifie que la base de données est bien importée.";
    }
}

// ── Vérification : lire le hash actuel ────────────────────────────────────
$currentHash = '';
$userFound   = false;
try {
    $db   = Database::getInstance();
    $stmt = $db->prepare("SELECT email, password, role, is_active FROM users WHERE email = 'admin@shop.com'");
    $stmt->execute();
    $user = $stmt->fetch();
    if ($user) {
        $userFound   = true;
        $currentHash = $user['password'];
    }
} catch (Exception $e) {
    $message = "❌ Erreur DB : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fix Password — ShopAdmin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height:100vh;">
<div class="card shadow" style="width:100%;max-width:560px;border-radius:16px;border:none;">
    <div class="card-body p-5">
        <h4 class="fw-800 mb-1">🔧 Fix Mot de passe Admin</h4>
        <p class="text-muted mb-4" style="font-size:14px;">Cet outil recrée le hash bcrypt directement en PHP</p>

        <!-- Statut utilisateur en DB -->
        <div class="alert <?= $userFound ? 'alert-success' : 'alert-danger' ?> mb-4" style="font-size:13px;">
            <?php if ($userFound): ?>
                ✅ <strong>Utilisateur trouvé</strong> — email : <code>admin@shop.com</code><br>
                Role : <code><?= $user['role'] ?></code> |
                Actif : <code><?= $user['is_active'] ? 'OUI' : 'NON' ?></code><br>
                Hash actuel : <code style="font-size:10px;word-break:break-all;"><?= htmlspecialchars($currentHash) ?></code><br>
                Hash valide ? :
                <strong><?= password_verify('password', $currentHash) ? '✅ OUI — le mot de passe "password" est correct !' : '❌ NON — le hash est invalide, utilise le bouton ci-dessous' ?></strong>
            <?php else: ?>
                ❌ <strong>Aucun utilisateur trouvé</strong> avec email <code>admin@shop.com</code><br>
                Vérifie que tu as bien importé <code>schema_corrige.sql</code>
            <?php endif; ?>
        </div>

        <!-- Résultat action -->
        <?php if ($message): ?>
        <div class="alert <?= $success ? 'alert-success' : 'alert-warning' ?> mb-4" style="font-size:13px;">
            <?= $message ?>
        </div>
        <?php endif; ?>

        <!-- Formulaire fix -->
        <?php if ($userFound): ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-600">Nouveau mot de passe</label>
                <input type="text" name="new_password" class="form-control"
                       value="password" style="border-radius:10px;">
                <div class="form-text">Laisse "password" pour garder le mot de passe par défaut</div>
            </div>
            <button type="submit" class="btn btn-primary w-100" style="border-radius:10px;">
                🔄 Régénérer le hash bcrypt
            </button>
        </form>

        <?php if ($success): ?>
        <div class="mt-4 p-3 bg-light rounded-3 text-center">
            <p class="mb-2 fw-600">Maintenant tu peux te connecter :</p>
            <a href="login.php" class="btn btn-success" style="border-radius:10px;">
                → Aller à la page de login
            </a>
        </div>
        <div class="alert alert-warning mt-3" style="font-size:12px;">
            ⚠️ <strong>Supprime ce fichier</strong> après utilisation :<br>
            <code>admin/fix_password.php</code>
        </div>
        <?php endif; ?>
        <?php endif; ?>

    </div>
</div>
</body>
</html>
