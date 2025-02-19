<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="/assets/style.css"> 
</head>
<body>

    <!-- Navbar -->
    <nav>
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
    </nav>

    <h1>Bienvenue sur notre application !</h1>
    <p>Connectez-vous ou inscrivez-vous pour accéder aux fonctionnalités.</p>

</body>
</html>
