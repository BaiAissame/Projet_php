<?php
require_once __DIR__ . "/../models/Photo.php";
require_once __DIR__ . "/../models/Group.php";


class PhotoController
{
    public static function index(): void
    {
        if (!isset($_SESSION['user']['id'])) {
            header("Location: /login");
            exit();
        }

        $user_id = $_SESSION['user']['id'];
        $groups = Group::getUserGroups($user_id);

        require_once __DIR__ . "/../views/photos/upload.php";
    }

    public static function upload(int $group_id): void
    {
        if (!isset($_SESSION['user']['id'])) {
            die("Vous devez être connecté pour uploader des photos.");
        }

        if (!Group::isUserInGroup($_SESSION['user']['id'], $group_id)) {
            die("Accès interdit : Vous ne pouvez pas uploader dans ce groupe.");
        }

        if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_FILES["photo"])) {
            die("Aucune photo envoyée.");
        }

        $allowedTypes = ['image/jpeg', 'image/png'];
        $maxSize = 2 * 1024 * 1024;

        if (!in_array($_FILES["photo"]["type"], $allowedTypes)) {
            die("Type de fichier non autorisé !");
        }

        if ($_FILES["photo"]["size"] > $maxSize) {
            die("Fichier trop volumineux !");
        }

        $uploadDir = __DIR__ . "/../uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filename = uniqid() . "_" . basename($_FILES["photo"]["name"]);
        $filepath = $uploadDir . $filename;

        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $filepath)) {
            $photo = new Photo($filename, $filepath, $_SESSION['user']['id'], $group_id);
            if ($photo->save()) {
                header("Location: /groupe/" . $photo->group_id);
                exit();
            } else {
                die("Erreur lors de l'insertion en base.");
            }
        } else {
            die("Erreur lors du téléchargement.");
        }
    }
    public static function deletePhoto(int $photo_id): void
    {
        if (!isset($_SESSION['user']['id'])) {
            die("Accès interdit !");
        }

        $photo = Photo::getPhotoById($photo_id);
        if (!$photo) {
            die("Photo non trouvée.");
        }

        // Vérification des permissions
        $isOwner = $_SESSION['user']['id'] === $photo['uploaded_by'];
        $isGroupOwner = $_SESSION['user']['id'] === Group::getOwnerId($photo['group_id']);

        if (!$isOwner && !$isGroupOwner) {
            die("Vous n'avez pas les permissions pour supprimer cette photo !");
        }

        // Suppression de la photo
        if (Photo::deleteById($photo_id)) {
            header("Location: /groupe/" . $photo['group_id']);
            exit();
        } else {
            die("Erreur lors de la suppression.");
        }
    }

    /**
     * Voir une photo en accès public
     */
    public static function viewPublicPhoto(string $public_link): void
    {
        $photo = Photo::getPhotoByPublicLink($public_link);
        if (!$photo) {
            die("Photo non trouvée.");
        }

        header("Content-Type: image/jpeg");
        readfile(__DIR__ . "/../uploads/" . $photo['filename']);
    }
}
