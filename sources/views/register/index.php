<h1>S'inscrire</h1>

<?php

if (!empty($errors)) {
    echo '<div style="background-color: red">';
    foreach ($errors as $error) {
        echo '<li>' . $error . '</li>';
    }
    echo '</div>';
}

?>


<form action="/register" method="POST">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <input type="text" name="firstname" required placeholder="Votre prénom"><br>
    <input type="text" name="lastname" required placeholder="Votre nom"><br>
    <input type="email" name="email" required placeholder="Votre email"><br>
    <input type="password" name="password" required placeholder="Votre mot de passe"><br>
    <input type="password" name="passwordConfirm" required placeholder="Confirmation"><br>
    <input type="submit" value="S'inscrire">
</form>
