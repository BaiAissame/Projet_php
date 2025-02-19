<?php

require_once __DIR__ . "/../core/Database.php"; // Inclure la classe Database

class User
{
    public function __construct(
        public ?int $id,
        public string $email,
        public string $password,
        public string $firstname,
        public string $lastname
    ) {}

    public static function getAllUsers(): array
    {
        $pdo = Database::getConnection();

        $query = $pdo->query("SELECT id, firstname, lastname, email FROM users");

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer un utilisateur par son email.
     */
    public static function findOneByEmail(string $email): ?User
    {
        $pdo = Database::getConnection(); // Connexion unique via Database

        $query = $pdo->prepare("SELECT id,email, password, firstname, lastname FROM users WHERE email = :email");
        $query->execute(["email" => $email]);

        $user = $query->fetch();

        if (!$user) {
            return null; // Aucun utilisateur trouvé
        }

        return new User($user["id"],$user["email"], $user["password"], $user["firstname"], $user["lastname"]);
    }

    /**
     * Vérifier si le mot de passe est correct.
     */
    public function isValidPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    /**
     * Insérer un nouvel utilisateur en base de données.
     */
    public function insertUser(): bool
    {
        try {
            $pdo = Database::getConnection(); // Connexion à la base
    
            $query = $pdo->prepare("
                INSERT INTO users (firstname, lastname, email, password) 
                VALUES (:firstname, :lastname, :email, :password)
            ");
    
            $executed = $query->execute([
                "firstname" => $this->firstname,
                "lastname" => $this->lastname,
                "email" => $this->email,
                "password" => $this->password
            ]);

    
            return $executed;
        } catch (PDOException $e) {
            die("Erreur SQL lors de l'insertion : " . $e->getMessage());
        }
    }
    
    

    /**
     * Met à jour les informations d'un utilisateur.
     */
    public function updateUser(): void
    {
        $pdo = Database::getConnection();

        $query = $pdo->prepare("
            UPDATE users SET firstname = :firstname, lastname = :lastname, password = :password WHERE email = :email
        ");

        $query->execute([
            "firstname" => $this->firstname,
            "lastname" => $this->lastname,
            "password" => $this->password,
            "email" => $this->email
        ]);
    }

    /**
     * Supprimer un utilisateur de la base de données.
     */
    public static function deleteUserByEmail(string $email): void
    {
        $pdo = Database::getConnection();

        $query = $pdo->prepare("DELETE FROM users WHERE email = :email");
        $query->execute(["email" => $email]);
    }
}
