<?php

class User {

    public $name;
    public $email;
    public $password;
    private $pdo;

    public function __construct($name = null, $email = null, $password = null) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        // Assurez-vous que ce chemin est correct par rapport à l'emplacement de ce fichier
        $this->pdo = require __DIR__ . "/../config/Database.php";
    }

    public function updatePassword($userId, $newPassword) {
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$newPassword, $userId]);
    }

    public function getUserById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateResetToken($email, $tokenHash, $expiry) {
        $sql = "UPDATE users SET reset_token_hash = ?, reset_token_expires_at = ? WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$tokenHash, $expiry, $email]);
    }

    public function validateUser($email)
    {
        $stmt=$this->pdo->prepare("UPDATE users SET is_verified = 1 WHERE email = ?");
        return $stmt->execute([$email]);
    }

    public function updateVerificationCode($email, $token, $expiry) {
        $sql="update users set verification_code=?,code_expiry=? where email=?";
        $stmt=$this->pdo->prepare($sql);
        return $stmt->execute([$token,$expiry,$email]);
    }

    public function addUser($name, $email, $password, $verification_code, $code_expiry) {
        // Correction : 5 colonnes = 5 points d'interrogation
        $sql = "INSERT INTO users (name, email, password, verification_code, code_expiry) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$name, $email, $password, $verification_code, $code_expiry]);

        return $this->pdo->lastInsertId();
    }

    public function addProfile($userId, $firstname) {
        $sql = "INSERT INTO profiles (user_id, firstname) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$userId, $firstname]);
    }

    public function registerUser($name, $email, $password, $code, $expiry) {
        try {
            $this->pdo->beginTransaction();

            // 1. Insertion de l'utilisateur
            $userId = $this->addUser($name, $email, $password, $code, $expiry);

            // 2. Insertion du profil associé
            $this->addProfile($userId, $name);

            $this->pdo->commit();
            return $userId;

        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            if ($e->getCode() == 23000) {
                return "Email déjà utilisé";
            }

            return "Erreur : " . $e->getMessage();
        }
    }

    public function getUserByToken($tokenHash) {
        $sql = "SELECT * FROM users WHERE reset_token_hash = ? AND reset_token_expires_at > NOW()";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$tokenHash]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function validateToken($hash) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE reset_token_hash = ? AND reset_token_expires_at > NOW()");
        $stmt->execute([$hash]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}