<?php





namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

class MailerService {
    private $mail;

    public function __construct() {
        // Ensure Dotenv is loaded if this file is called outside index.php
        if (empty($_ENV['MAIL_USERNAME'])) {
            $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
            $dotenv->safeLoad();
        }

        $this->mail = new PHPMailer(true);
        
        // Configuration Serveur SMTP
        $this->mail->isSMTP();
        $this->mail->Host       = $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com';
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = $_ENV['MAIL_USERNAME'];
        $this->mail->Password   = $_ENV['MAIL_PASSWORD'];
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port       = 587;
        $this->mail->setFrom('no-reply@alphastore.com', 'Alpha Store Support');
        $this->mail->isHTML(true);
        $this->mail->CharSet = 'UTF-8';
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

    public function sendValidationEmail($toEmail, $code) {
        try {
            $this->mail->addAddress($toEmail);
            $this->mail->Subject = "Validation de votre email - Alpha Store";
            $this->mail->Body = <<<HTML
<div style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 8px;">
    <div style="text-align: center; padding-bottom: 20px;">
        <h2 style="color: #2c3e50; margin-bottom: 10px;">Bonjour,</h2>
        <p style="font-size: 16px; color: #555;">Merci de valider votre email en entrant le code suivant :</p>
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <span style="display: inline-block; font-family: monospace; font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #2563eb; background-color: #f0f4ff; padding: 15px 30px; border-radius: 4px; border: 1px dashed #2563eb;">
            {$code}
        </span>
    </div>

    <div style="border-top: 1px solid #eee; padding-top: 20px; text-align: center;">
        <p style="font-size: 13px; color: #888;">
            Si vous n'êtes pas à l'origine de cette demande, vous pouvez ignorer cet email en toute sécurité.
        </p>
    </div>
</div>
HTML;
            return $this->mail->send();
        } catch (Exception $e) {
            error_log("Erreur Mailer: " . $this->mail->ErrorInfo);
            return false;
        }
    }
}