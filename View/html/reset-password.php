
<?php


require_once __DIR__ . "/../../model/User.php";

$token = $_GET["token"] ?? null;
if (!$token) {
    die("Token manquant ou invalide.");
}

$token_hash = hash("sha256", $token);

$userModel = new User();
$user = $userModel->validateToken($token_hash);

if (!$user) {
    die("Token invalide ou expiré. <br><a href='forget-password.php'>Demander un nouveau lien</a>");
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>

    <h1>Reset Password</h1>

    <form method="post" action="process-reset-password.php">

        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <label for="password">New password</label>
        <input type="password" id="password" name="password">

        <label for="password_confirmation">Repeat password</label>
        <input type="password" id="password_confirmation"
               name="password_confirmation">

        <button>Send</button>
    </form>

</body>
</html>