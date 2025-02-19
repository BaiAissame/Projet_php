<?php
require_once __DIR__ . "/../core/Database.php";  
try {
    $pdo = Database::getConnection();
    
    echo "Exécution des migrations...\n";

    // Exécuter tous les fichiers de migration présents dans `db/migrations`
    $migrationFiles = glob(__DIR__ . "/migrations/*.php");

    foreach ($migrationFiles as $file) {
        echo "Exécution de : " . basename($file) . "\n";
        require_once $file;
    }

    echo "Toutes les migrations ont été appliquées avec succès !\n";
} catch (Exception $e) {
    die("Erreur lors de l'exécution des migrations : " . $e->getMessage() . "\n");
}
