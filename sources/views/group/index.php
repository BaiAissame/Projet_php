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

    <h1>Mes Groupes</h1>

    <?php if (empty($groups)): ?>
        <p>Aucun groupe trouvé.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($groups as $group): ?>
                <li>
                    <a href="/groupe/<?= htmlspecialchars($group['id']) ?>">
                        <?= htmlspecialchars($group['name']) ?>
                    </a>
                    <?php if ($group['owner_id'] == $_SESSION['user']['id']): ?>
                        <a href="/groupe/<?= htmlspecialchars($group['id']) ?>/members" class="manage-members-link">
                            Gérer les membres
                        </a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</body>
</html>
