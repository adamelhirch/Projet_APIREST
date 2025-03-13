<?php
require_once 'action_connection.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="page_login">
    <header>
        <div class="container">
            <h1>Gestionnaire d'équipe de foot</h1>
            <nav class="nav_login"><a href="#">À propos</a></nav>
        </div>
    </header>
    <main>
        <div class="form-container">
            <h2>Bienvenue</h2>
            <?php if (!empty($error)): ?>
                <p style="color: red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <form method="POST" action="action_connection.php">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" placeholder="Entrer votre nom d'utilisateur" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" placeholder="Entrer votre mot de passe" required>
                </div>
                <button type="submit">Connexion</button>
            </form>
        </div>
    </main>
</body>
</html>
