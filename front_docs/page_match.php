<?php
require_once 'action_page_match.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste Match</title>   
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
    <h1>Liste des matchs</h1>
    <?php if (isset($_GET['error']) && $_GET['error'] === 'match_date_passed'): ?>
        <p style="color: red;">Impossible de supprimer un match dont la date est déjà passée.</p>
    <?php endif; ?>

    <div class="search-bar">
        <input type="text" placeholder="Rechercher un match...">
    </div>
    <br>
    <table>
        <thead>
            <tr>
                <th>ID_Match</th>
                <th>Date</th>
                <th>Adversaire</th>
                <th>Lieux</th>
                <th>Résultat</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($matches)): ?>
                <?php foreach ($matches as $match): ?>
                    <tr onclick="showPopupMatch('<?= htmlspecialchars($match['ID_Match']) ?>')">
                        <td><?= htmlspecialchars($match['ID_Match']) ?></td>
                        <td><?= htmlspecialchars($match['Date_match']) ?></td>
                        <td><?= htmlspecialchars($match['Equipe_adverse']) ?></td>
                        <td><?= htmlspecialchars($match['Lieux']) ?></td>
                        <td><?= htmlspecialchars($match['Resultat']) ?></td>
                        <td>
                            <button class="view-selection-btn" onclick="viewSelection('<?= htmlspecialchars($match['ID_Match']) ?>')">Voir la sélection</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6">Aucun match trouvé.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Popup pour les actions (modifier/supprimer) -->
    <div class="popup" id="popup_match" style="display: none;">
        <div class="popup-content">
            <span class="close-button" onclick="closePopupMatch()">&times;</span>
            <h2>Actions</h2>
            <a id="modify-link-match" href="Page_Modifier_Match.php">Modifier le match</a>
            <a id="delete-link-match" href="#">Supprimer le match</a>
        </div>
    </div>
</main>
<script src="script.js"></script>
</body>
</html>
