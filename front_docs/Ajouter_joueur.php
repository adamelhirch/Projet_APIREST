<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un joueur</title>
    <link rel="stylesheet" href="style_page_joueur.css">
</head>
<body>
<header class="navbar">
        <nav>
            <ul class="menu">
                <li><a href="index.php"><img class="logo" src="images/favicon.png" alt="logo"></a></li>
                <li class="menu-item">
                    <a href="#">Joueur</a>
                    <ul class="submenu">
                        <li><a href="Ajouter_joueur.php">Ajouter un joueur</a></li>
                        <li><a href="page_joueur.php">Voir la liste des joueurs</a></li>
                    </ul>
                </li>
                <li class="menu-item">
                    <a href="#">Match</a>
                    <ul class="submenu">
                        <li><a href="ajouter_match.php">Ajouter un match</a></li>
                        <li><a href="page_match.php">Voir la liste des matchs</a></li>
                    </ul>
                </li>
                <li class="menu-item"><a href="pageFeuilleMatch.php">Feuille de match</a></li>
                <li class="menu-item"><a href="page_Stat.php">Statistiques</a></li>
                <li class="menu-item"><a href="#">À propos</a></li>
                <li class="menu-item dernier"><a href="#">Profil</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <h1>Ajouter un joueur</h1>

        <!-- Afficher les messages de feedback -->
        <?php if (isset($_GET['success'])): ?>
            <p style="text-align: center; color: #4caf50;">Le joueur a été ajouté avec succès !</p>
        <?php elseif (isset($_GET['error'])): ?>
            <p style="text-align: center; color: #f44336;">Erreur : <?= htmlspecialchars($_GET['error']) ?></p>
        <?php endif; ?>

        <form method="POST" action="action.php" class="form-ajout">
            <label for="numero_de_licence">Numéro de licence :</label>
            <input type="text" id="numero_de_licence" name="numero_de_licence" required>

            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required>

            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" required>

            <label for="date_de_naissance">Date de naissance :</label>
            <input type="date" id="date_de_naissance" name="date_de_naissance" required>

            <label for="taille">Taille :</label>
            <input type="number" step="0.01" id="taille" name="taille" required>

            <label for="poids">Poids :</label>
            <input type="number" step="0.01" id="poids" name="poids" required>

            <label for="statut">Statut :</label>
            <select id="statut" name="statut" required>
                <option value="Apte">Apte</option>
                <option value="Absent">Absent</option>
                <option value="Suspendu">Suspendu</option>
            </select>

            <button type="submit" class="btn-ajout">Ajouter</button>
        </form>
    </main>
</body>
</html>
