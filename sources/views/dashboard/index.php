<?php
if (!isset($_SESSION['user'])) {
    header("Location: /login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Groupes</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>

    <nav>
        <ul>
            <li><a href="/">Accueil</a></li>
            <li><a href="/dashboard">Tableau de bord</a></li>
            <li><a href="/groupe">Groupe</a></li>
            <li><a href="/logout">Déconnexion</a></li>
        </ul>
    </nav>

    <h1>Bienvenue <?= htmlspecialchars($_SESSION['user']['lastname'])." ".htmlspecialchars($_SESSION['user']['firstname']); ?> !</h1>   

</body>

</html>