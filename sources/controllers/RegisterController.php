<?php

require_once __DIR__ . "/../models/User.php";
require_once __DIR__ . "/../models/UserValidator.php";

class RegisterController
{
    public static function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        require_once __DIR__ . "/../views/register/index.php";
    }

    public static function post(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        http_response_code(403);
        die("Erreur CSRF : requête non autorisée.");
    }

    unset($_SESSION['csrf_token']);

    if (
        empty($_POST["firstname"]) || empty($_POST["lastname"]) ||
        empty($_POST["email"]) || empty($_POST["password"]) || empty($_POST["passwordConfirm"])
    ) {
        die("Erreur : Tous les champs doivent être remplis.");
    }

    if ($_POST["password"] !== $_POST["passwordConfirm"]) {
        die("Erreur : Les mots de passe ne correspondent pas.");
    }

    $user = new User(null,$_POST['email'], $_POST["password"], $_POST["firstname"], $_POST["lastname"]);

    $validator = new UserValidator($user, $_POST["passwordConfirm"]);
    $errors = $validator->getErrors();

    if (!empty($errors)) {
        print_r($errors);
        die();
    }

    $user->password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    if ($user->insertUser()) {
        header("Location: /login");
        exit();
    } else {
        die("❌ Erreur lors de l'insertion en base de données.");
    }
}

    
    
}
