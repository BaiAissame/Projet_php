<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Groupe : <?= htmlspecialchars($group['name']) ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>

    <nav>
        <ul>
            <li><a href="/">Accueil</a></li>
            <li><a href="/dashboard">Tableau de bord</a></li>
            <li><a href="/groupe">Groupes</a></li>
            <li><a href="/logout">Déconnexion</a></li>
        </ul>
    </nav>

    <h1>Groupe : <?= htmlspecialchars($group['name']) ?></h1>

    <h2>Photos du groupe</h2>
    <?php if (empty($photos)): ?>
        <p>Aucune photo pour l'instant.</p>
    <?php else: ?>
        <div class="photo-gallery">
            <?php foreach ($photos as $photo): ?>
                <div class="photo-card">
                    <img src="/uploads/<?= htmlspecialchars($photo['filename']) ?>" alt="Photo">
                    <p>Posté par : <?= htmlspecialchars($photo['uploader_lastname']) . " " . htmlspecialchars($photo['uploader_name']) ?></p>

                    <!-- Vérification des permissions -->
                    <?php if ($_SESSION['user']['id'] == $photo['uploaded_by'] || $_SESSION['user']['id'] == $group['owner_id']): ?>
                        <form action="/delete-photo/<?= $photo['id'] ?>" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                            <button type="submit" class="delete-btn">Supprimer</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <h2>Uploader une photo</h2>
    <form action="/upload/<?= htmlspecialchars($group['id']) ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
        <input type="file" name="photo" required>
        <button type="submit">Envoyer</button>
    </form>

</body>
</html>
