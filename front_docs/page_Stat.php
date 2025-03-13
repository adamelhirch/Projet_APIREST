<?php
require_once 'action_page_stat.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques des Joueurs</title>
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
        <h1>Statistiques des Joueurs</h1>

        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Statut</th>
                    <th>Poste</th>
                    <th>Titularisations</th>
                    <th>Remplacements</th>
                    <th>Moyenne des Notes</th>
                    <th>Matchs Gagnés (%)</th>
                    <th>Sélections Consécutives</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($statsJoueurs as $stat): ?>
                    <tr>
                        <td><?= htmlspecialchars($stat['Nom']) ?></td>
                        <td><?= htmlspecialchars($stat['Prenom']) ?></td>
                        <td><?= htmlspecialchars($stat['Statut']) ?></td>
                        <td><?= !empty($stat['Poste']) ? htmlspecialchars($stat['Poste']) : 'Non défini' ?></td>
                        <td><?= htmlspecialchars($stat['Titularisations']) ?></td>
                        <td><?= htmlspecialchars($stat['Remplacements']) ?></td>
                        <td><?= number_format($stat['Moyenne_Note'], 2) ?></td>
                        <td><?= number_format($stat['Pourcentage_Gagnes'], 2) ?>%</td>
                        <td><?= htmlspecialchars($stat['Selections_Consecutives']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
