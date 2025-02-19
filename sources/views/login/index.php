
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Document</title>
</head>

<body>
  <form method="POST" action="/login">
  <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <input type="email" name="email">
    <input type="password" name="password">
    <button>
      Connexion
    </button>
  </form>
</body>

</html>