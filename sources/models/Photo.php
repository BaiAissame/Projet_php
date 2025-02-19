<?php

require_once __DIR__ . "/../core/Database.php";
require_once __DIR__ . "/../core/QueryBuilder.php";

class Photo
{
    public function __construct(
        public string $filename,
        public string $filepath,
        public int $uploaded_by,
        public int $group_id,
        public string $visibility = 'private'
    ) {}

    /**
     * Enregistrer une photo dans la base de données
     */
    public function save(): bool
    {
        $queryBuilder = new QueryBuilder();
        return $queryBuilder
            ->insert("photos", [
                "filename" => $this->filename,
                "filepath" => $this->filepath,
                "uploaded_by" => $this->uploaded_by,
                "group_id" => $this->group_id,
                "public_link" => bin2hex(random_bytes(16)) // Générer un lien unique
            ]);
    }

    /**
     * Récupérer toutes les photos d'un groupe
     */
    public static function getPhotosByGroupId(int $group_id): array
    {
        $pdo = Database::getConnection();
    
        $query = $pdo->prepare("
            SELECT 
                p.id, 
                p.filename, 
                p.filepath, 
                p.uploaded_by, 
                p.public_link, 
                u.firstname AS uploader_name, 
                u.lastname AS uploader_lastname
            FROM photos p
            INNER JOIN users u ON p.uploaded_by = u.id
            WHERE p.group_id = :group_id
        ");
    
        $query->execute(['group_id' => $group_id]);
    
        return $query->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
    
    /**
     * Récupérer une photo par ID
     */
    public static function getPhotoById(int $photo_id): ?array
    {
        $queryBuilder = new QueryBuilder();
        return $queryBuilder
            ->select(["id", "filename", "filepath", "uploaded_by", "group_id", "public_link"])
            ->from("photos")
            ->where("id", $photo_id)
            ->fetch();
    }

    /**
     * Récupérer une photo via son lien public
     */
    public static function getPhotoByPublicLink(string $public_link): ?array
    {
        $queryBuilder = new QueryBuilder();
        return $queryBuilder
            ->select(["id", "filename", "filepath", "uploaded_by", "group_id"])
            ->from("photos")
            ->where("public_link", $public_link)
            ->fetch();
    }

    /**
     * Supprimer une photo (seulement si autorisé)
     */
    public static function deleteById(int $photo_id): bool
    {
        $photo = self::getPhotoById($photo_id);
        if (!$photo) {
            return false;
        }

        // Supprimer le fichier du serveur
        $filePath = __DIR__ . "/../uploads/" . $photo['filename'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Supprimer de la base de données
        $queryBuilder = new QueryBuilder();
        return $queryBuilder
            ->delete("photos")
            ->where("id", $photo_id)
            ->execute();
    }
}
