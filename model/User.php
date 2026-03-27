<?php

class User {

    
    public $nom;
    public $email;
    public $password;

    private $pdo ;

    public function __construct($nom, $email, $password){
        $this->nom = $nom;
        $this->email = $email;
        $this->password = $password;
        $pdo = require __DIR__ . "/../../config/Database.php";

    }

    public function getUserById($id) {
        
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return  $stmt->fetch();

    }
    
    

}
?>
