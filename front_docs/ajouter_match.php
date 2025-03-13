<?php
require_once 'db_connect.php';
require_once 'queries.php';

$joueursDisponibles = getAllJoueursMinimal($pdo);

$message = '';
if (isset($_GET['success'])) {
    if ($_GET['success'] == 1) {
        $message = '<div class="success-message">Le match a été ajouté avec succès !</div>';
    } elseif ($_GET['success'] == 0) {
        $message = '<div class="error-message">Une erreur est survenue lors de l\'ajout du match.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ajouter un match</title>
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
    <?= $message ?>
    <h1>Ajouter un match</h1>
    <form method="POST" action="action_ajout_match.php" class="form-ajout">
        <label for="Date_match">Date du match :</label>
        <input type="date" id="Date_match" name="Date_match" required>

        <label for="Equipe_adverse">Équipe adverse :</label>
        <input type="text" id="Equipe_adverse" name="Equipe_adverse" required>

        <label for="Lieux">Lieu :</label>
        <select id="Lieux" name="Lieux" required>
            <option value="Domicile">Domicile</option>
            <option value="Exterieur">Extérieur</option>
        </select>

        <label for="Resultat">Résultat :</label>
        <select id="Resultat" name="Resultat" required>
            <option value="Définir" selected>Ajouter plus tard</option>
            <option value="Victoire">Victoire</option>
            <option value="Defaite">Défaite</option>
        </select>
        <button type="submit" class="btn-ajout">Ajouter</button>
    </form>
</main>
</body>
</html>
