<?php
/**
 * Configuration de la base de données
 * Connexion PDO avec gestion des erreurs
 */

require_once __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->safeLoad();

define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'alphastore');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');
define('DB_CHARSET', 'utf8mb4');

class Database {
    private static ?PDO $instance = null;

    /**
     * Retourne l'instance unique PDO (Singleton)
     */
    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                // En production : logger l'erreur, ne pas l'afficher
                error_log("Erreur connexion DB : " . $e->getMessage());
                die(json_encode(['error' => 'Connexion à la base de données échouée.']));
            }
        }
        return self::$instance;
    }

    // Empêcher le clonage et la désérialisation
    private function __clone() {}
    public function __wakeup() {}
}
