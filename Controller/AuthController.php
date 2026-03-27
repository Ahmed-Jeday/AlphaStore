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

    if (empty($errors)) {
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

        $user = new User(
            $data['user_name'],
            $data['email'],
            $hashedPassword
        );
        

        $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";

        try {
            $stmt = $cnx->prepare($sql);
            $success = $stmt->execute([
                ':name'      => $user->nom,
                ':email'    => $user->email,
                ':password' => $user->password
            ]);
            
            return $success; // Retourne true si l'insertion a réussi
            
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return ["Cet email est déjà utilisé."];
            } else {
                return ["Erreur base de données : " . $e->getMessage()];
            }
        }
    }

    return $errors; // Retourne le tableau d'erreurs (format, longueur, etc.)
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
            $_SESSION["user_nom"] = $user["nom"];
            
            // 5. Redirection
            header("Location: ../my-account/my-account.php");
            exit;
        }
    }

    // 6. Si on arrive ici, c'est que la connexion a échoué
    return "Email ou mot de passe incorrect.";
}



    

    
