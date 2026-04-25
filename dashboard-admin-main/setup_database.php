<?php
/**
 * Script CLI d'importation du schéma SQL
 * À exécuter: php setup_database.php
 */

require_once __DIR__ . '/config/database.php';

echo "🔧 Importation du schéma de base de données...\n";

try {
    // Lire le fichier schema.sql
    $sqlFile = __DIR__ . '/config/schema.sql';
    if (!file_exists($sqlFile)) {
        die("❌ Fichier schema.sql non trouvé: $sqlFile\n");
    }
    
    $sql = file_get_contents($sqlFile);
    
    if (!$sql) {
        die("❌ Impossible de lire le fichier schema.sql\n");
    }
    
    // Diviser les requêtes SQL par ";"
    $queries = array_filter(array_map('trim', explode(';', $sql)));
    
    $db = Database::getInstance();
    $count = 0;
    $errors = [];
    
    foreach ($queries as $query) {
        if (!empty($query)) {
            try {
                $db->exec($query);
                $count++;
                echo ".";
            } catch (PDOException $e) {
                $msg = $e->getMessage();
                // Ignorer les erreurs de table existante et de contrainte unique
                if (strpos($msg, 'already exists') === false && 
                    strpos($msg, 'Duplicate entry') === false &&
                    strpos($msg, 'Duplicate key') === false) {
                    $errors[] = $msg;
                }
            }
        }
    }
    
    echo "\n\n✅ Importation réussie!\n";
    echo "📊 $count requêtes exécutées\n";
    
    if (!empty($errors)) {
        echo "\n⚠️  Avertissements:\n";
        foreach ($errors as $err) {
            echo "  - $err\n";
        }
    }
    
    // Vérifier les tables créées
    echo "\n📋 Tables créées:\n";
    $tables = $db->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'alphastore'")->fetchAll();
    foreach ($tables as $table) {
        echo "  ✓ {$table['TABLE_NAME']}\n";
    }
    
} catch (Exception $e) {
    echo "\n❌ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
?>
