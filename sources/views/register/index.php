
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Document</title>
  <link rel="stylesheet" href="/dist/framework-esgi.css">

</head>
<body>
    
<nav class="navbar">
    <div class="container">
        <ul>
            <li><a href="/">Accueil</a></li>
            <?php if (isset($_SESSION['user'])): ?>
                <li><a href="/dashboard">Tableau de bord</a></li>
                <li><a href="/groupe">Groupe</a></li>
                <li><a href="/logout">Déconnexion</a></li>
            <?php else: ?>
                <li><a href="/login">Connexion</a></li>
                <li><a href="/register">Inscription</a></li>
            <?php endif; ?>
        </ul>

        <!-- Formulaire de recherche affiché uniquement si l'utilisateur est connecté -->
        <?php if (isset($_SESSION['user'])): ?>
            <form>
                <input placeholder="Search" />
                <button class="button">Search</button>
            </form>
        <?php endif; ?>

        <!-- Bouton de menu responsive -->
        <button class="navbar__button">
            <svg
                width="18"
                height="14"
                viewBox="0 0 18 14"
                fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M0.75 13C0.75 12.8011 0.829018 12.6103 0.96967 12.4697C1.11032 12.329 1.30109 12.25 1.5 12.25H16.5C16.6989 12.25 16.8897 12.329 17.0303 12.4697C17.171 12.6103 17.25 12.8011 17.25 13C17.25 13.1989 17.171 13.3897 17.0303 13.5303C16.8897 13.671 16.6989 13.75 16.5 13.75H1.5C1.30109 13.75 1.11032 13.671 0.96967 13.5303C0.829018 13.3897 0.75 13.1989 0.75 13ZM0.75 7C0.75 6.80109 0.829018 6.61032 0.96967 6.46967C1.11032 6.32902 1.30109 6.25 1.5 6.25H16.5C16.6989 6.25 16.8897 6.32902 17.0303 6.46967C17.171 6.61032 17.25 6.80109 17.25 7C17.25 7.19891 17.171 7.38968 17.0303 7.53033C16.8897 7.67098 16.6989 7.75 16.5 7.75H1.5C1.30109 7.75 1.11032 7.67098 0.96967 7.53033C0.829018 7.38968 0.75 7.19891 0.75 7ZM0.75 1C0.75 0.801088 0.829018 0.610322 0.96967 0.46967C1.11032 0.329018 1.30109 0.25 1.5 0.25H16.5C16.6989 0.25 16.8897 0.329018 17.0303 0.46967C17.171 0.610322 17.25 0.801088 17.25 1C17.25 1.19891 17.171 1.38968 17.0303 1.53033C16.8897 1.67098 16.6989 1.75 16.5 1.75H1.5C1.30109 1.75 1.11032 1.67098 0.96967 1.53033C0.829018 1.38968 0.75 1.19891 0.75 1Z"
                    fill="currentColor" />
            </svg>
        </button>
    </div>
</nav>

<h1 style="text-align:center">S'inscrire</h1>

<?php

if (!empty($errors)) {
    echo '<div style="background-color: red">';
    foreach ($errors as $error) {
        echo '<li>' . $error . '</li>';
    }
    echo '</div>';
}

?>

<div class="container">
<form class="form" action="/register" method="POST">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <input type="text" name="firstname" required placeholder="Votre prénom"><br>
    <input type="text" name="lastname" required placeholder="Votre nom"><br>
    <input type="email" name="email" required placeholder="Votre email"><br>
    <input type="password" name="password" required placeholder="Votre mot de passe"><br>
    <input type="password" name="passwordConfirm" required placeholder="Confirmation"><br>
    <input class="button button--primary" type="submit" value="S'inscrire">
</form>
</div>
<script src="/dist/framework-esgi.js" defer></script>

</body>
