<?php

require_once __DIR__ . "/../models/User.php";
require_once __DIR__ . "/../requests/LoginRequest.php";

class LoginController
{
  public static function index(): void
  {
    require_once __DIR__ . "/../views/login/index.php";
  }

  public static function post(): void
  {
      require_once __DIR__ . "/../models/User.php";
      require_once __DIR__ . "/../requests/LoginRequest.php";
  
      // Vérifier le CSRF token
      if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
          http_response_code(403);
          die(" Erreur CSRF : requête non autorisée.");
      }
  
      // Supprimer le token après vérification
      unset($_SESSION['csrf_token']);
  
      $request = new LoginRequest();
      $user = User::findOneByEmail($request->email);
  
      if (!$user || !$user->isValidPassword($request->password)) {
          die(" Adresse email ou mot de passe incorrect.");
      }
  
     
      if (session_status() === PHP_SESSION_NONE) {
          session_start();
      }
  
      // Enregistrer les infos utilisateur en session
      $_SESSION['user'] = [
          'id' => $user->id,
          'firstname' => $user->firstname,
          'lastname' => $user->lastname,
          'email' => $user->email
      ];
  
      // Redirection après connexion
      header("Location: /dashboard");
      exit();
  }
  
}
