<?php

class User {

    
    public $nom;
    public $email;
    public $password;

    private $pdo ;

    public function __construct($nom = null, $email = null, $password = null){
        $this->nom = $nom;
        $this->email = $email;
        $this->password = $password;
        $this->pdo = require __DIR__ . "/../config/Database.php";
    }

   


    

    public function getUserById($id) {
        
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return  $stmt->fetch();

    }

    public function getUserByEmail($email) {
        
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return  $stmt->fetch();

    }

    
    public function updateResetToken($email, $tokenHash, $expiry) {
        $sql = "UPDATE users SET reset_token_hash = ?, reset_token_expires_at = ? WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$tokenHash, $expiry, $email]);
    }

 public function addUser($name, $email, $password)
{
    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";

    try {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$name, $email, $password]);
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            return ["Cet email est déjà utilisé."];
        } else {
            return ["Erreur base de données : " . $e->getMessage()];
        }
    }
}
    

    public function getUserByToken($tokenHash) {
        $sql = "SELECT * FROM users WHERE reset_token_hash = ? AND reset_token_expires_at > NOW()";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$tokenHash]);
        return $stmt->fetch();
    }


    public function validateToken($hash) {
        // On vérifie que le hash existe ET que la date d'expiration est encore dans le futur
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE reset_token_hash = ? AND reset_token_expires_at > NOW()");
        $stmt->execute([$hash]);
        return $stmt->fetch();
    }
    public function updatePassword($userid,$newPassword)
    {
        $hasshPassword=password_hash($newPassword,PASSWORD_DEFAULT);
        $stmt=$this->pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([$hasshPassword,$userid]);

    }

}
?>
