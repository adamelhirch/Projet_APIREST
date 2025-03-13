<?php
require_once 'action_modifier_selection.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la Sélection | Gestion d'Équipe de Football</title>
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
    <h1>Modifier la Sélection</h1>

    <!-- Affichage des messages d'erreur ou de succès -->
    <?php if (!empty($erreur)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <form method="post" action="modifier_selection.php?id_match=<?= htmlspecialchars($idMatch) ?>" id="modifier-selection-form" onsubmit="return validateForm();">
        <input type="hidden" name="id_match" value="<?= htmlspecialchars($idMatch) ?>">

        <!-- Tableau des joueurs -->
        <table class="table-feuille-match">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Rôle</th>
                    <th>Titulaire</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($selection)): ?>
                    <?php foreach ($selection as $joueur): ?>
                        <tr>
                            <td><?= htmlspecialchars($joueur['Nom']) ?></td>
                            <td><?= htmlspecialchars($joueur['Prenom']) ?></td>
                            <td>
                                <select name="role[<?= $joueur['Numero_de_licence'] ?>]" class="select_FeuilleMatch">
                                    <option value="gardien" <?= $joueur['Role'] === 'gardien' ? 'selected' : '' ?>>Gardien</option>
                                    <option value="defenseur" <?= $joueur['Role'] === 'defenseur' ? 'selected' : '' ?>>Défenseur</option>
                                    <option value="milieu" <?= $joueur['Role'] === 'milieu' ? 'selected' : '' ?>>Milieu</option>
                                    <option value="attaquant" <?= $joueur['Role'] === 'attaquant' ? 'selected' : '' ?>>Attaquant</option>
                                </select>
                            </td>
                            <td>
                                <input type="checkbox" name="titulaire[<?= $joueur['Numero_de_licence'] ?>]" value="1" class="checkbox_FeuilleMatch titulaire-checkbox" <?= $joueur['Titulaire'] ? 'checked' : '' ?>>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6">Aucun joueur trouvé pour ce match.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Bouton Enregistrer -->
        <div class="form-actions">
            <button type="submit" class="btn-FeuilleMatch" name="modifier_selection">Enregistrer les modifications</button>
        </div>
    </form>

    <!-- Charger le script externe -->
    <script>
        const isSuccess = <?= $isSuccess ? 'true' : 'false'; ?>;
    </script>
    <script src="script.js"></script>
</main>

</body>
</html>
