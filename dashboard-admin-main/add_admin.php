<?php
/**
 * Script pour ajouter un nouvel administrateur
 * À utiliser une seule fois, puis à supprimer ou sécuriser
 */

require_once __DIR__ . '/config/database.php';

// Vérifier si le formulaire a été soumis
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Validation
    $errors = [];
    
    if (empty($name)) {
        $errors[] = 'Le nom est obligatoire.';
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email invalide.';
    }
    
    if (empty($password) || strlen($password) < 6) {
        $errors[] = 'Le mot de passe doit contenir au moins 6 caractères.';
    }
    
    if ($password !== $password_confirm) {
        $errors[] = 'Les mots de passe ne correspondent pas.';
    }

    if (empty($errors)) {
        try {
            $db = Database::getInstance();
            
            // Vérifier si l'email existe déjà
            $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetchColumn() > 0) {
                $errors[] = 'Cet email existe déjà.';
            } else {
                // Hasher le mot de passe
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                
                // Insérer l'admin
                $stmt = $db->prepare("
                    INSERT INTO users (name, email, password, role, is_active, is_verified)
                    VALUES (:name, :email, :password, 'admin', 1, 1)
                ");
                
                if ($stmt->execute([
                    ':name' => $name,
                    ':email' => $email,
                    ':password' => $hashedPassword
                ])) {
                    $message = "✅ Administrateur ajouté avec succès! Email: $email";
                    $messageType = 'success';
                    // Réinitialiser le formulaire
                    $name = '';
                    $email = '';
                    $password = '';
                    $password_confirm = '';
                } else {
                    $errors[] = 'Erreur lors de l\'insertion en base de données.';
                }
            }
        } catch (Exception $e) {
            $errors[] = 'Erreur: ' . $e->getMessage();
        }
    }

    if (!empty($errors)) {
        $message = implode('<br>', $errors);
        $messageType = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Administrateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container-form {
            background: white;
            border-radius: 16px;
            padding: 40px;
            max-width: 450px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .form-header h1 {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 10px;
        }
        .form-header p {
            color: #64748b;
            font-size: 14px;
        }
        .form-label {
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 8px;
        }
        .form-control {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 14px;
        }
        .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        .btn-submit {
            background: #4f46e5;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 700;
            width: 100%;
            cursor: pointer;
            margin-top: 20px;
            transition: all 0.3s;
        }
        .btn-submit:hover {
            background: #3730a3;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.2);
        }
        .alert-success {
            background: #dcfce7;
            border: 1px solid #86efac;
            color: #166534;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 20px;
        }
        .alert-error {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            color: #991b1b;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .password-info {
            background: #f0f9ff;
            border-left: 4px solid #4f46e5;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            color: #0c4a6e;
            margin-top: 20px;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            color: #4f46e5;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container-form">
        <div class="form-header">
            <h1>👨‍💼 Ajouter Admin</h1>
            <p>Créer un nouvel compte administrateur</p>
        </div>

        <?php if ($message): ?>
            <div class="alert-<?= $messageType ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="needs-validation">
            <div class="form-group">
                <label class="form-label" for="name">Nom complet *</label>
                <input type="text" class="form-control" id="name" name="name" 
                       value="<?= htmlspecialchars($name ?? '') ?>"
                       placeholder="Ahmed Jday" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email *</label>
                <input type="email" class="form-control" id="email" name="email" 
                       value="<?= htmlspecialchars($email ?? '') ?>"
                       placeholder="admin@example.com" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Mot de passe *</label>
                <input type="password" class="form-control" id="password" name="password" 
                       placeholder="Minimum 6 caractères" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirm">Confirmer mot de passe *</label>
                <input type="password" class="form-control" id="password_confirm" 
                       name="password_confirm" placeholder="Répétez le mot de passe" required>
            </div>

            <button type="submit" class="btn-submit">
                ✓ Créer l'administrateur
            </button>
        </form>

        <div class="password-info">
            💡 <strong>Important :</strong> Le mot de passe sera automatiquement hashé avec bcrypt pour la sécurité.
        </div>

        <div class="back-link">
            <a href="login.php">← Retour à la connexion</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
