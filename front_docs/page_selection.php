<?php
require_once 'action_page_selection.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sélection des Joueurs</title>
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
    <h1>Sélection des joueurs pour le match <?= htmlspecialchars($ID_Match); ?></h1>

    <!-- Bouton pour modifier toute la sélection -->
    <div style="text-align: right; margin-bottom: 10px;">
        <?php if (!$matchDatePassed): ?>
            <button>
                <a href="modifier_selection.php?id_match=<?= htmlspecialchars($ID_Match); ?>" class="btn-modifier-all">Modifier toute la sélection</a>
            </button>
        <?php else: ?>
            <button disabled class="btn-modifier-all btn-disabled">Modification impossible (date dépassée)</button>
        <?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>Numéro de Licence</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Rôle</th>
                <th>Titulaire</th>
                <th>Évaluation</th>
                <th>Commentaire</th>
                <th>Ajouter note/commentaire</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($selection)): ?>
                <?php foreach ($selection as $player): ?>
                    <tr>
                        <td><?= htmlspecialchars($player['Numero_de_licence']); ?></td>
                        <td><?= htmlspecialchars($player['Nom']); ?></td>
                        <td><?= htmlspecialchars($player['Prenom']); ?></td>
                        <td><?= htmlspecialchars($player['Role']); ?></td>
                        <td><?= $player['Titulaire'] ? 'Oui' : 'Non'; ?></td>
                        <td><?= htmlspecialchars($player['Evaluation']); ?></td>
                        <td><?= htmlspecialchars($player['Commentaire'] ?? 'Aucun commentaire'); ?></td>
                        <td>
                            <!-- Bouton pour ouvrir le popup -->
                            <button onclick="openPopup('<?= htmlspecialchars($player['Numero_de_licence']); ?>')" class="btn-ajouter-note">Ajouter</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="9">Aucun joueur trouvé pour ce match.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>
<script src="script.js"></script>
<!-- Popup -->
<div class="popup" id="popup_match" style="display: none;">
    <div class="popup-content">
        <span class="close-button" onclick="closePopup()">&times;</span>
        <h2>Ajouter une note ou un commentaire</h2>
        <form method="post" action="action_page_selection.php" class="form-ajout">
            <!-- Champ caché pour ID_Match -->
            <input type="hidden" name="id_match" id="popup_id_match" value="<?= htmlspecialchars($ID_Match); ?>">

            <!-- Champ caché pour Numero_de_Licence -->
            <input type="hidden" name="numero_de_licence" id="popup_numero_licence">

            <div>
                <label for="evaluation">Note :</label>
                <input type="number" name="evaluation" id="evaluation" min="0" max="10" placeholder="Note">
            </div>
            <div>
                <label for="commentaire">Commentaire :</label>
                <input name="commentaire" id="commentaire" rows="4" placeholder="Ajouter un commentaire">
            </div>
            <div>
                <button type="submit" name="add_comment">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
