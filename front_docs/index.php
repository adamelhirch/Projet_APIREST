<?php include 'actionPageAccueil.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil | Gestion d'Équipe de Football</title>
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
        <div class="hero-section">
            <h1>Bienvenue sur le Gestionnaire d'Équipe de Football</h1>
            <p class="intro-text">
                Gagnez du temps et optimisez la gestion de votre équipe. Gérez vos joueurs, suivez vos matchs, et analysez les performances en un seul endroit.
            </p>
            <a href="Ajouter_joueur.php" class="cta-button">Ajouter un nouveau joueur</a>
            <a href="Ajouter_match.php" class="cta-button">Ajouter un nouveau match</a>
        </div>

        <section class="overview-section">
            <h2>Aperçu de l'équipe</h2>
            <div class="overview-cards">
                <div class="card">
                    <h3>Total Joueurs</h3>
                    <p><?= $totalPlayers ?> joueurs</p>
                </div>
                <div class="card">
                    <h3>Matchs joués</h3>
                    <p><?= $totalMatches ?> matchs</p>
                </div>
                <div class="card">
                    <h3>Ratio Victoires</h3>
                    <p><?= round(($data['Victoire'] / max($totalMatches, 1)) * 100, 1) ?>%</p>
                </div>
            </div>
        </section>

        <section class="graphs-container">
            <!-- Graphique circulaire -->
            <div class="graph-item">
                <h2>Diagramme des résultats des matchs</h2>
                <div class="pie-chart" style="--victoires: <?= $percentages['Victoire'] ?>; --defaites: <?= $percentages['Défaite'] ?>; --nuls: <?= $percentages['Nul'] ?>;">
                    <div class="slice victoire"></div>
                    <div class="slice defaite"></div>
                    <div class="slice nul"></div>
                </div>
                <div class="legend">
                    <div><span class="legend-box victoire"></span> Victoire : <?= round($percentages['Victoire'], 1) ?>%</div>
                    <div><span class="legend-box defaite"></span> Défaite : <?= round($percentages['Défaite'], 1) ?>%</div>
                    <div><span class="legend-box nul"></span> Nul : <?= round($percentages['Nul'], 1) ?>%</div>
                </div>
            </div>

            <!-- Graphique en barres -->
            <div class="graph-item">
                <h2>Statistiques des 5 meilleurs joueurs</h2>
                <div class="bar-chart">
                    <?php foreach ($topPlayersStats as $player): ?>
                        <div class="bar-container">
                            <div class="bar" style="height: <?= $player['moyenne'] * 20 ?>px;" title="<?= $player['Nom'] ?> <?= $player['Prenom'] ?> : <?= round($player['moyenne'], 1) ?>/10">
                                <?= round($player['moyenne'], 1) ?>
                            </div>
                            <div class="bar-label"><?= $player['Nom'] ?> <?= $player['Prenom'] ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="team-history">
            <h2>Historique récent</h2>
            <ul class="recent-matches">
                <?php foreach ($recentMatches as $match): ?>
                    <li>
                        <span class="match-date"><?= date('d/m/Y', strtotime($match['Date_match'])) ?></span>
                        <span class="match-details"><?= $match['Equipe_adverse'] ?> - <?= ucfirst(strtolower($match['Resultat'])) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </main>

    <footer class="footer">
        <p>&copy; <?= date('Y') ?> Gestionnaire d'Équipe de Football. Tous droits réservés.</p>
    </footer>
</body>
</html>
