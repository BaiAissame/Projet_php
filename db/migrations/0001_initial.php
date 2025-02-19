<?php

require_once __DIR__ . "/../../core/Database.php";

try {
    $pdo = Database::getConnection();

    $query = "
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL UNIQUE,
            password CHAR(60) NOT NULL,
            firstname VARCHAR(50) NOT NULL,
            lastname VARCHAR(50) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB;
    ";
    $pdo->exec($query);
    echo "Migration réussie : Table 'users' créée avec succès !\n";

    $query = "
        CREATE TABLE IF NOT EXISTS groups (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            owner_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB;
    ";
    $pdo->exec($query);
    echo "Migration réussie : Table 'groups' créée avec succès !\n";

    $query = "
        CREATE TABLE IF NOT EXISTS group_members (
            id INT AUTO_INCREMENT PRIMARY KEY,
            group_id INT NOT NULL,
            user_id INT NOT NULL,
            role ENUM('read', 'write') DEFAULT 'read',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB;
    ";
    $pdo->exec($query);
    echo "Migration réussie : Table 'group_members' créée avec succès !\n";

    
    $query = "
        CREATE TABLE IF NOT EXISTS photos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            filename VARCHAR(255) NOT NULL,
            filepath VARCHAR(255) NOT NULL,
            uploaded_by INT NOT NULL,
            group_id INT NOT NULL,
            public_link VARCHAR(255) NULL UNIQUE,
            visibility ENUM('private', 'public') DEFAULT 'private',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE
        ) ENGINE=InnoDB;
    ";
    $pdo->exec($query);
    echo "Migration réussie : Table 'photos' créée avec succès !\n";

} catch (PDOException $e) {
    die("Erreur lors de la migration : " . $e->getMessage() . "\n");
}
