<?php
// Initialize a cURL session
$ch = curl_init();

// Set the URL and other options
curl_setopt($ch, CURLOPT_URL, "http://localhost/projet-api/api/V1/index.php?action=allPlayers");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Return response instead of printing
curl_setopt($ch, CURLOPT_TIMEOUT, 30);           // Timeout after 30 seconds

// If you need headers, set them here:
$headers = [
    "Content-Type: application/json"
];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Execute the request
$joueurs = curl_exec($ch);

// If there is an error, capture it
$error = curl_error($ch);

// Close the cURL session
curl_close($ch);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste Joueur</title>
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
    <h1>Liste des joueurs</h1>

    <!-- Afficher les messages de feedback -->
    <?php if (isset($_GET['error']) && $_GET['error'] === 'joueur_in_match'): ?>
        <p style="text-align: center; color: #f44336;">Impossible de supprimer ce joueur car il a déjà participé à un match.</p>
    <?php endif; ?>

    <div class="search-bar">
        <input type="text" placeholder="Rechercher un joueur...">
    </div>
    <br>
    <table>
    <thead>
        <tr>
            <th>Numéro de licence</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Date de naissance</th>
            <th>Taille</th>
            <th>Poids</th>
            <th>Statut</th>
            <th>Actions</th> <!-- Nouvelle colonne -->
        </tr>
    </thead>
    <tbody>
        <?php foreach ($joueurs as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['num_licence']) ?></td>
                <td><?= htmlspecialchars($row['nom']) ?></td>
                <td><?= htmlspecialchars($row['prenom']) ?></td>
                <td><?= htmlspecialchars($row['date_naissance']) ?></td>
                <td><?= htmlspecialchars($row['taille']) ?></td>
                <td><?= htmlspecialchars($row['poids']) ?></td>
                <td><span class="status <?= strtolower(htmlspecialchars($row['statut'])) ?>"><?= htmlspecialchars($row['statut']) ?></span></td>
                <td>
                    <a href="http://localhost/projet-api/api/V1/index.php?action=allPlayers" class="btn btn-primary">Modifier</a>
                    <a <$row['num_licence'] ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce joueur ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


    <div class="popup" id="popup" style="display: none;">
        <div class="popup-content">
            <span class="close-button" onclick="closePopup()">&times;</span>
            <h2>Actions</h2>
            <a id="modify-link" href="#">Modifier le joueur</a><br>
            <a id="delete-link" href="#">Supprimer le joueur</a>
        </div>
    </div>
</main>
<script src="script.js"></script>
</body>
</html>
