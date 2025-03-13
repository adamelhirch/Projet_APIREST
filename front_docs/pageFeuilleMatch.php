<?php
require_once 'action_feuille_match.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feuille de Match | Gestion d'Équipe de Football</title>
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
        <h1>Feuille de Match</h1>

        <div class="info-selection">
            <p>⚠️ Vous devez sélectionner exactement <strong>11 titulaires</strong> pour enregistrer la feuille de match.</p>
        </div>

        <form method="post" action="action_feuille_match.php" id="feuille-match-form" onsubmit="return validateForm()">
            <!-- Sélecteur pour choisir un match -->
            <div class="match-selector">
                <label for="match-select">Sélectionnez un match :</label>
                <select id="match-select" name="match-select" class="select_FeuilleMatch" required>
                    <?php foreach ($matches as $match): ?>
                    <option value="<?= $match['ID_Match'] ?>">
                        <?= htmlspecialchars($match['Date_match']) ?> - <?= htmlspecialchars($match['Equipe_adverse']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Tableau des joueurs -->
            <table class="table-feuille-match">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Statut</th>
                        <th>Titulaire</th>
                        <th>Remplaçant</th>
                        <th>Poste</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($joueurs as $joueur): ?>
                        <tr>
                            <td><?= htmlspecialchars($joueur['Nom']) ?></td>
                            <td><?= htmlspecialchars($joueur['Prenom']) ?></td>
                            <td><?= htmlspecialchars($joueur['Statut']) ?></td>
                            <td>
                                <input 
                                    type="checkbox" 
                                    name="titulaire[<?= $joueur['Numero_de_licence'] ?>]" 
                                    value="1" 
                                    class="checkbox_FeuilleMatch titulaire-checkbox">
                            </td>
                            <td>
                                <input 
                                    type="checkbox" 
                                    name="remplacant[<?= $joueur['Numero_de_licence'] ?>]" 
                                    class="checkbox_FeuilleMatch"
                                    value="1">
                            </td>
                            <td>
                                <select name="Role[<?= $joueur['Numero_de_licence'] ?>]" class="select_FeuilleMatch">
                                    <option value="definir" selected>A définir</option>
                                    <option value="gardien">Gardien</option>
                                    <option value="defenseur">Défenseur</option>
                                    <option value="milieu">Milieu</option>
                                    <option value="attaquant">Attaquant</option>
                                </select>
                            </td>
                            <td>
                                <input type="number" name="Evaluation[<?= $joueur['Numero_de_licence'] ?>]" min="0" max="10" placeholder="Note" class="Note" >
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Bouton Enregistrer -->
            <div class="form-actions">
                <button type="submit" class="btn-FeuilleMatch" name="save-composition" id="save-composition-btn">Enregistrer la composition</button>
            </div>
        </form>
    </main>

    <script src="script.js"></script>
</body>
</html>
