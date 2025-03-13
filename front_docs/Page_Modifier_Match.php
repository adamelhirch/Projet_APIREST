<?php require_once 'action_modifier_match.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Match</title>
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
    <h1>Modifier un Match</h1>
    <?php if (!empty($match)): ?>
        <?php 
            // Déterminer si le match est passé
            $isPassed = (new DateTime($match['Date_match'])) < (new DateTime());
        ?>
        <form action="action_modifier_match.php" method="post" class="form-modif">
            <input type="hidden" name="id_match" value="<?= htmlspecialchars($match['ID_Match']); ?>">

            <div class="form-group">
                <label for="date_match">Date du Match :</label>
                <input type="date" id="date_match" name="date_match" value="<?= htmlspecialchars($match['Date_match']); ?>" <?= $isPassed ? 'disabled' : 'required'; ?>>
            </div>

            <div class="form-group">
                <label for="equipe_adverse">Équipe Adverse :</label>
                <input type="text" id="equipe_adverse" name="equipe_adverse" value="<?= htmlspecialchars($match['Equipe_adverse']); ?>" <?= $isPassed ? 'disabled' : 'required'; ?>>
            </div>

            <label for="Lieux">Lieu :</label>
            <select id="Lieux" name="Lieux" <?= $isPassed ? 'disabled' : 'required'; ?>>
                <option value="Domicile" <?= $match['Lieux'] === 'Domicile' ? 'selected' : ''; ?>>Domicile</option>
                <option value="Exterieur" <?= $match['Lieux'] === 'Exterieur' ? 'selected' : ''; ?>>Extérieur</option>
            </select>

            <label for="Resultat">Résultat :</label>
            <select id="Resultat" name="Resultat" required>
                <option value="Defaite" <?= $match['Resultat'] === 'Defaite' ? 'selected' : ''; ?>>Défaite</option>
                <option value="Victoire" <?= $match['Resultat'] === 'Victoire' ? 'selected' : ''; ?>>Victoire</option>
                <option value="Nul" <?= $match['Resultat'] === 'Nul' ? 'selected' : ''; ?>>Nul</option>
            </select>

            <div class="form-actions">
                <button type="submit">Enregistrer les modifications</button>
            </div>
        </form>
    <?php else: ?>
        <p>Match introuvable ou ID_Match manquant.</p>
    <?php endif; ?>
</main>
</body>
</html>
