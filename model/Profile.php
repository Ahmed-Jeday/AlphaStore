<?php

class Profile {
    // Propriétés privées correspondant aux colonnes de la table
    private $id;
    private $user_id;
    private $firstname;
    private $lastname;
    private $age;
    private $phone;
    private $gender;
    private $avatar;
     private $pdo ;

    // Constructeur pour initialiser l'objet
    public function __construct($id=null,$user_id=null,$firstname=null,$lastname=null,$age=null,$phone=null,$gender=null,$avatar=null) {
        $this->id        = $id;
        $this->user_id   = $user_id;
        $this->firstname = $firstname;
        $this->lastname  = $lastname;
        $this->age       = $age;
        $this->phone     = $phone;
        $this->gender    = $gender;
        $this->avatar    = $avatar;
        $this->pdo = require __DIR__ . "/../config/Database.php";
    }

    

    public function getAllInfo($userId) {
        $sql = "SELECT * FROM profiles WHERE user_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

  public function updateProfile($userId, $data) {
    $sql = "UPDATE profiles SET firstname = ?, lastname = ?, age = ?, phone = ?, gender = ?, avatar = ? WHERE user_id = ?";
    $stmt = $this->pdo->prepare($sql);
    
    // Assurer que avatar a une valeur (par défaut l'actuel ou null)
    $avatar = $data['avatar'] ?? 'https://i.pravatar.cc/180?img=3';
    
    return $stmt->execute([
        $data['firstname'], 
        $data['lastname'], 
        $data['age'], 
        $data['phone'], 
        $data['gender'], 
        $avatar,
        $userId
    ]);
  }

    

    // Méthode utilitaire pour afficher le nom complet
    public function getFullName() {
        return $this->firstname . ' ' . $this->lastname;
    }




}