<?php
require_once(__DIR__ . "/../config/Database.php");
require_once(__DIR__ . "/../model/User.php"); // Changé de Controller à Model

function validateData($data)
{
    $errors = [];

    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format d'email invalide.";
    }

    if (strlen($data['password']) < 8) {
        $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
    }

    if (!preg_match('/[A-Z]/', $data['password'])) {
        $errors[] = "Le mot de passe doit contenir au moins une majuscule.";
    }

    if (!preg_match("/[0-9]/", $data['password'])) { // Utilisation de $data au lieu de $_POST
        $errors[] = "Le mot de passe doit contenir au moins un chiffre.";
    }

    return $errors;
}

function AddUser($cnx, $data)
{
    $errors = validateData($data);

    if (!empty($errors)) {
        return $errors;
    }

    $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

    $user = new User($cnx);

    $res = $user->addUser(
        $data['user_name'],
        $data['email'],
        $hashedPassword
    );

    return $res;
}
function loginUser($cnx, $data) {
    // 1. On récupère l'utilisateur par son email
    // On utilise une requête préparée (plus sûr que sprintf)
    $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
    $stmt = $cnx->prepare($sql);
    $stmt->execute([':email' => $data['email']]);
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // 2. Si l'utilisateur existe
    if ($user) {
        // 3. Vérification du mot de passe haché

        if (password_verify($data['password'], $user['password'])) {
            
            // 4. Gestion de la session
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            session_regenerate_id(true); // Sécurité contre la fixation de session
            
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_nom"] = $user["name"];
            
            // 5. Redirection
            header("Location: ../my-account/my-account.php");
            exit;
        }
    }

    // 6. Si on arrive ici, c'est que la connexion a échoué
    return "Email ou mot de passe incorrect.";
}


require_once(__DIR__ . "/../services/mailer.php");
use App\Services\MailerService;

function requestReset()
{
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(16));
    $token_hash = hash("sha256", $token);
    $expiry = date("Y-m-d H:i:s", time() + 60 * 30);
    $userModel = new User();
    
    $success = $userModel->updateResetToken($email, $token_hash, $expiry);
    
    $resetLink = "http://localhost/AlphaStore/View/html/reset-password.php?token=" . $token;
    $mailer = new MailerService();
    $mailSent = $mailer->sendResetEmail($email, $resetLink);
    
    if ($mailSent) {
        return "Email de reinitialisation envoye avec succes.";
    } else {
        return "Erreur lors de l'envoi de l'email.";
    }
}

function completeReset()
{
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        return "Méthode invalide.";
    }

    $token = $_POST["token"];
    $newPassword = $_POST["password"];
    $confirmPassword = $_POST["password_confirmation"];

    if ($newPassword !== $confirmPassword) {
        return "Les mots de passe ne correspondent pas.";
    }

    $hash = hash("sha256", $token);

    $userModel = new User();
    $user = $userModel->validateToken($hash);

    if ($user) {
        $userModel->updatePassword($user["id"], $newPassword);
        
        // On invalide le token après usage
        $userModel->updateResetToken($user['email'], null, null);

        return "Mot de passe reinitialise avec succes.";
    } else {
        return "Token invalide ou expire.";
    }
}



    

    
