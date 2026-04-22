<?php
session_start();
if (isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/User.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Veuillez remplir tous les champs.';
    } else {
        $userModel = new User();
        $admin = $userModel->authenticate($email, $password);

        if ($admin) {
            session_regenerate_id(true); // nouvelle session propre
            $_SESSION['admin_id']    = $admin['id'];
            $_SESSION['admin_name']  = $admin['name'];
            $_SESSION['admin_email'] = $admin['email'];
            header('Location: index.php');
            exit;
        } else {
            $error = 'Email ou mot de passe incorrect.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — ShopAdmin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .login-card {
            background: #fff;
            border-radius: 20px;
            padding: 48px 40px;
            width: 100%; max-width: 420px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,.5);
        }
        .brand-icon {
            width: 56px; height: 56px; border-radius: 16px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; color: #fff; margin: 0 auto 20px;
        }
        .login-title { font-size: 24px; font-weight: 800; color: #0f172a; }
        .form-control {
            border-radius: 10px; border: 1.5px solid #e2e8f0;
            padding: 12px 16px; font-size: 14px;
            transition: border-color .2s;
        }
        .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79,70,229,.1);
        }
        .btn-login {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #fff; border: none; border-radius: 10px;
            padding: 13px; font-weight: 700; font-size: 15px;
            width: 100%; transition: opacity .2s;
        }
        .btn-login:hover { opacity: .9; color: #fff; }
        .demo-info {
            background: #f8fafc; border-radius: 10px;
            padding: 12px 16px; font-size: 13px; color: #64748b;
        }
        .form-label { font-size: 13px; font-weight: 600; color: #374151; }
    </style>
</head>
<body>
    <div class="login-card text-center">
        <div class="brand-icon"><i class="bi bi-bag-heart-fill"></i></div>
        <h1 class="login-title mb-1">ShopAdmin</h1>
        <p class="text-secondary mb-4" style="font-size:14px;">Connexion au panneau d'administration</p>

        <?php if ($error): ?>
        <div class="alert alert-danger d-flex align-items-center gap-2 text-start" style="font-size:14px;">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <form method="POST" autocomplete="off">
            <div class="mb-3 text-start">
                <label class="form-label">Adresse email</label>
                <div class="input-group">
                    <span class="input-group-text" style="border-radius:10px 0 0 10px;border:1.5px solid #e2e8f0;border-right:none;">
                        <i class="bi bi-envelope text-secondary"></i>
                    </span>
                    <input type="email" name="email" class="form-control" style="border-radius:0 10px 10px 0;"
                           placeholder="admin@shop.com"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>
            </div>

            <div class="mb-4 text-start">
                <label class="form-label">Mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text" style="border-radius:10px 0 0 10px;border:1.5px solid #e2e8f0;border-right:none;">
                        <i class="bi bi-lock text-secondary"></i>
                    </span>
                    <input type="password" name="password" class="form-control" style="border-radius:0 10px 10px 0;"
                           placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-login mb-4">
                <i class="bi bi-arrow-right-circle-fill me-2"></i>Se connecter
            </button>
        </form>

        <div class="demo-info text-start">
            <div class="fw-600 mb-1" style="font-size:12px;color:#4f46e5;">
                <i class="bi bi-info-circle me-1"></i>ACCÈS DEMO
            </div>
            <div><b>Email :</b> admin@shop.com</div>
            <div><b>Mot de passe :</b> password</div>
        </div>
    </div>
</body>
</html>