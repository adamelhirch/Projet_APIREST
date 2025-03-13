<?php
require_once 'action_modifier_joueur.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Joueur</title>   
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
    <h1>Modifier les informations du joueur</h1>

    <?php if (!empty($message)): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if (isset($player)): ?>
        <form action="action_modifier_joueur.php" method="post" class="form-modif">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($player['Nom']) ?>" required>

            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($player['Prenom']) ?>" required>

            <label for="date_naissance">Date de naissance</label>
            <input type="date" id="date_naissance" name="date_naissance" value="<?= htmlspecialchars($player['Date_de_naissance']) ?>" required>

            <label for="taille">Taille</label>
            <input type="number" id="taille" name="taille" value="<?= htmlspecialchars($player['Taille']) ?>" required>

            <label for="poids">Poids</label>
            <input type="number" id="poids" name="poids" value="<?= htmlspecialchars($player['Poids']) ?>" required>

            <label for="statut">Statut</label>
            <select id="statut" name="statut">
                <option value="actif" <?= $player['Statut'] == 'actif' ? 'selected' : '' ?>>Actif</option>
                <option value="inactif" <?= $player['Statut'] == 'inactif' ? 'selected' : '' ?>>Inactif</option>
                <option value="blessé" <?= $player['Statut'] == 'blessé' ? 'selected' : '' ?>>Blessé</option>
            </select>

            <input type="hidden" name="numero_licence" value="<?= htmlspecialchars($player['Numero_de_licence']) ?>">

            <button class="btn-modif" type="submit">Enregistrer les modifications</button>
        </form>
    <?php else: ?>
        <p>Joueur non trouvé.</p>
    <?php endif; ?>
</main>

</body>
</html>
