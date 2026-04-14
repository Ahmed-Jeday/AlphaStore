<?php


require_once(__DIR__ . "/../model/Profile.php"); 




function updateProfile() {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || !isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Données invalides ou non connecté']);
        return;
    }

    // Mapping des noms du JS vers les noms du modèle/DB
    $data = [
        'firstname' => $input['firstName'],
        'lastname'  => $input['lastName'],
        'age'       => $input['age'],
        'phone'     => $input['phone'],
        'gender'    => $input['gender'],
        'avatar'    => $input['avatar'] ?? null
    ];

    $profileModel = new Profile();
    $res = $profileModel->updateProfile($_SESSION["user_id"], $data);

    if ($res) {
        // Optionnel : On peut aussi mettre à jour la table 'users' si l'email change.
        // Pour l'instant, on met juste la SESSION à jour pour l'affichage.
        $_SESSION['user_name']      = $input['firstName'];
        $_SESSION['user_last_name'] = $input['lastName'];
        $_SESSION['user_email']     = $input['email'];
        $_SESSION['user_age']       = $input['age'];
        $_SESSION['user_phone']     = $input['phone'];
        $_SESSION["user_gender"]   = $input['gender'];
       
        $_SESSION["user_avatar"]   = $input['avatar'];
       

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur SQL lors de la mise à jour']);
    }
}

function getProfileInfo() {
    if (!isset($_SESSION['user_id'])) {
        header("HTTP/1.1 401 Unauthorized");
        
      
    }

    $profileModel = new Profile();
    $profileInfo = $profileModel->getAllInfo($_SESSION['user_id']);
    if ($profileInfo){
        $_SESSION['user_name']      = $profileInfo['firstname'];
        $_SESSION['user_last_name'] = $profileInfo['lastname'];
        $_SESSION["user_phone"]     = $profileInfo['phone'];
        $_SESSION["user_age"]       = $profileInfo['age'];
        
    }



   
}


