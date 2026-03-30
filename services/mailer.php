<?php





namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Chemin vers l'autoloader de Composer

class MailerService {
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);
        
        // Configuration Serveur SMTP Gmail
        $this->mail->isSMTP();
        $this->mail->Host       = 'smtp.gmail.com';
        $this->mail->SMTPAuth   = true;
        $this->mail->Username = "ahmedjeday8@gmail.com";
        $this->mail->Password ="qqnv nwbd fmxr jovm";
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port       = 587;
        $this->mail->setFrom('no-reply@alphastore.com', 'Alpha Store Support');
        $this->mail->isHTML(true);
    }

    public function sendResetEmail($toEmail, $resetLink) {
        try {
            $this->mail->addAddress($toEmail);
            $this->mail->Subject = 'Reinitialisation de votre mot de passe - Alpha Store';
            
            // Corps de l'email en HTML
            $this->mail->Body = "
                <h2>Bonjour,</h2>
                <p>Vous avez demande la reinitialisation de votre mot de passe.</p>
                <p>Cliquez sur le bouton ci-dessous pour continuer (ce lien expire dans 30 minutes) :</p>
                <a href='{$resetLink}' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none;'>Reinitialiser mon mot de passe</a>
                <p>Si vous n'etes pas a l'origine de cette demande, ignorez cet email.</p>
            ";

            return $this->mail->send();
        } catch (Exception $e) {
            error_log("Erreur Mailer: " . $this->mail->ErrorInfo);
            return false;
        }
    }
}