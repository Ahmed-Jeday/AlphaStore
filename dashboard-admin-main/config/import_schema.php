<?php
/**
 * Script d'importation du schéma SQL - Accessible via HTTP
 * URL: http://localhost/AlphaStore/dashboard-admin-main/config/import_schema.php
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/database.php';

try {
    // Lire le fichier schema.sql
    $sqlFile = __DIR__ . '/schema.sql';
    if (!file_exists($sqlFile)) {
        throw new Exception("Fichier schema.sql non trouvé: $sqlFile");
    }
    
    $sql = file_get_contents($sqlFile);
    
    if (!$sql) {
        throw new Exception('Impossible de lire le fichier schema.sql');
    }
    
    // Diviser les requêtes SQL par ";"
    $queries = array_filter(array_map('trim', explode(';', $sql)));
    
    $db = Database::getInstance();
    $count = 0;
    $warnings = [];
    
    foreach ($queries as $query) {
        if (!empty($query)) {
            try {
                $db->exec($query);
                $count++;
            } catch (PDOException $e) {
                $msg = $e->getMessage();
                // Ignorer les erreurs de table existante
                if (strpos($msg, 'already exists') === false && 
                    strpos($msg, 'Duplicate entry') === false &&
                    strpos($msg, 'Duplicate key') === false) {
                    $warnings[] = $msg;
                }
            }
        }
    }
    
    // Récupérer les tables créées
    $tables = $db->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'alphastore'")->fetchAll();
    $tableNames = array_column($tables, 'TABLE_NAME');
    
    echo json_encode([
        'success' => true,
        'message' => "Schéma importé avec succès",
        'queries_executed' => $count,
        'tables_created' => $tableNames,
        'warnings' => $warnings
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
