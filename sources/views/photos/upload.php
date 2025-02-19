
<h1>Uploader une photo</h1>

<form action="/upload" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
    <input type="file" name="photo" required>

    <label>Choisir un groupe :</label>
    <select name="group_id" required>
        <option value="">Sélectionner un groupe</option>
        <?php if (empty($groups)): ?>
            <option disabled>Aucun groupe disponible</option>
        <?php else: ?>
            <?php foreach ($groups as $group): ?>
                <option value="<?= htmlspecialchars($group['id']) ?>">
                    <?= htmlspecialchars($group['name']) ?>
                </option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>

    <button type="submit">Envoyer</button>
</form>
