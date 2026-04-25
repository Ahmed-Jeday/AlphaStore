<?php
/**
 * Vérifie le statut de la base de données et des tables
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/config/database.php';

try {
    $db = Database::getInstance();
    
    // Vérifier si la base de données existe
    $result = $db->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'alphastore'")->fetch();
    $db_exists = !empty($result);
    
    // Compter les tables
    $tables = $db->query("SELECT COUNT(*) as count FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'alphastore'")->fetch();
    $tables_count = $tables['count'] ?? 0;
    
    echo json_encode([
        'db_exists' => $db_exists,
        'tables_count' => $tables_count,
        'database' => 'alphastore'
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage(),
        'db_exists' => false,
        'tables_count' => 0
    ]);
}
?>
