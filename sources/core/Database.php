<?php

class Database
{
    private static ?PDO $pdo = null;

    public static function getConnection(): PDO
    {
        if (self::$pdo === null) {
            try {
                self::$pdo = new PDO(
                    "mysql:host=mariadb;dbname=database;charset=utf8",
                    "user",
                    "password",
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // Lancer une exception en cas d'erreur SQL
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Récupérer les résultats sous forme de tableau associatif
                        PDO::ATTR_EMULATE_PREPARES => false // Utiliser les requêtes préparées réelles (sécurité)
                    ]
                );
            } catch (PDOException $e) {
                die("Erreur de connexion à la base de données : " . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}
