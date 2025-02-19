<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des membres du groupe</title>
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

    <h1>Gestion des membres du groupe : <?= htmlspecialchars($group['name']) ?></h1>

    <h2>Membres actuels</h2>
    <ul>
        <?php foreach ($members as $member): ?>
            <li>
                <?= htmlspecialchars($member['firstname'] . " " . $member['lastname']) ?> 
                (<?= htmlspecialchars($member['role']) ?>)

                <?php if ($member['id'] !== $_SESSION['user']['id']): ?>
                    <form action="/groupe/<?= $group['id'] ?>/remove-member"  method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? ''; ?>">
                        <input type="hidden" name="group_id" value="<?= htmlspecialchars($group_id) ?>">
                        <input type="hidden" name="user_id" value="<?= htmlspecialchars($member['id']) ?>">
                        <button type="submit" >Supprimer</button>
                    </form>
                    <form action="/groupe/<?= $group['id'] ?>/update-role" method="POST" style="display:inline;">
                        <input type="hidden" name="user_id" value="<?= $member['id'] ?>">
                        <select name="role">
                            <option value="read" <?= $member['role'] === 'read' ? 'selected' : '' ?>>Lecture</option>
                            <option value="write" <?= $member['role'] === 'write' ? 'selected' : '' ?>>Écriture</option>
                        </select>
                        <button type="submit">Modifier</button>
                    </form>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Ajouter un membre</h2>
    <form action="/groupe/<?= htmlspecialchars($group['id']) ?>/add-member" method="POST">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? ''; ?>">
    <input type="hidden" name="group_id" value="<?= htmlspecialchars($group['id']) ?>">

    <label for="user_id">Sélectionner un utilisateur :</label>
    <select name="user_id" required>
        <option value="">-- Choisissez un utilisateur --</option>
        <?php if (empty($allUsers)): ?>
            <option disabled>Aucun utilisateur disponible</option>
        <?php else: ?>
            <?php foreach ($allUsers as $user): ?>
                <option value="<?= htmlspecialchars($user['id']) ?>">
                    <?= htmlspecialchars($user['firstname'] . " " . $user['lastname']) ?>
                </option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>

    <label for="role">Attribuer un rôle :</label>
    <select name="role" required>
        <option value="read">Lecture</option>
        <option value="write">Écriture</option>
    </select>

    <button type="submit">Ajouter</button>
</form>


</body>
</html>
