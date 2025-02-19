<?php
require_once __DIR__ . "/../models/Group.php";
require_once __DIR__ . "/../models/Photo.php";

class GroupController
{
    public static function index(): void
    {

        if (!isset($_SESSION['user']['id'])) {
            header("Location: /login");
            exit();
        }

        $user_id = $_SESSION['user']['id'];
        $groups = Group::getUserGroups($user_id);

        require_once __DIR__ . "/../views/group/index.php";
    }

    public static function chat(int $group_id): void
    {

        if (!isset($_SESSION['user']['id'])) {
            header("Location: /login");
            exit();
        }

        $user_id = $_SESSION['user']['id'];
        if (!Group::isUserInGroup($user_id, $group_id)) {
            die("Accès refusé !");
        }

        $group = Group::getGroupById($group_id);
        $photos = Photo::getPhotosByGroupId($group_id);
        require_once __DIR__ . "/../views/group/chat.php";
    }
    public static function addUser(): void
    {
        if (!isset($_SESSION['user']['id'])) {
            header("Location: /login");
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['group_id']) || !isset($_POST['user_id'])) {
            die("Requête invalide.");
        }

        $group_id = intval($_POST['group_id']);
        $user_id = intval($_POST['user_id']);
        $role = $_POST['role'] ?? 'read';

        if (Group::addUserToGroup($group_id, $user_id, $role)) {
            header("Location: /groupe/$group_id/members");
            exit();
        } else {
            die("Erreur lors de l'ajout de l'utilisateur.");
        }
    }

    public static function removeUser(): void
    {
        if (!isset($_SESSION['user']['id'])) {
            header("Location: /login");
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['group_id']) || !isset($_POST['user_id'])) {
            die("Requête invalide.");
        }

        $group_id = intval($_POST['group_id']);
        $user_id = intval($_POST['user_id']);

        if (Group::removeUserFromGroup($group_id, $user_id)) {
            header("Location: /groupe/$group_id/members");
            exit();
        } else {
            die("Erreur lors de la suppression de l'utilisateur.");
        }
    }
    public static function manageMembers(int $group_id): void
    {
        if (!isset($_SESSION['user']['id'])) {
            header("Location: /login");
            exit();
        }

        $user_id = $_SESSION['user']['id'];

        if (!Group::isOwner($user_id, $group_id)) {
            die("Accès refusé.");
        }

        $group = Group::getGroupById($group_id);
        $members = Group::getGroupMembers($group_id);
        $allUsers = User::getAllUsers();

        require_once __DIR__ . "/../views/group/manage_members.php";
    }
}
