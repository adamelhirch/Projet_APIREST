<?php
require_once 'db_connection.php'; // Inclure la connexion à la base de données

if (!isset($_GET['numero_licence'])) {
    die('Numéro de licence manquant.');
}

$numeroLicence = intval($_GET['numero_licence']);

try {
    $query = "SELECT * FROM joueur WHERE Numero_de_licence = :numero_licence";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['numero_licence' => $numeroLicence]);
    $joueur = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$joueur) {
        die('Joueur non trouvé.');
    }
} catch (PDOException $e) {
    die("Erreur lors de la récupération du joueur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du joueur</title>
    <link rel="stylesheet" href="style_page_joueur.css">
</head>
<body>
    <header>
        <nav>
            <a href="page_joueur.php">Retour à la liste des joueurs</a>
        </nav>
    </header>
    <main>
        <h1>Détails du joueur</h1>
        <table>
            <tr>
                <th>Numéro de licence</th>
                <td><?= htmlspecialchars($joueur['Numero_de_licence']) ?></td>
            </tr>
            <tr>
                <th>Nom</th>
                <td><?= htmlspecialchars($joueur['Nom']) ?></td>
            </tr>
            <tr>
                <th>Prénom</th>
                <td><?= htmlspecialchars($joueur['Prenom']) ?></td>
            </tr>
            <tr>
                <th>Date de naissance</th>
                <td><?= htmlspecialchars($joueur['Date_de_naissance']) ?></td>
            </tr>
            <tr>
                <th>Taille</th>
                <td><?= htmlspecialchars($joueur['Taille']) ?></td>
            </tr>
            <tr>
                <th>Poids</th>
                <td><?= htmlspecialchars($joueur['Poids']) ?></td>
            </tr>
            <tr>
                <th>Statut</th>
                <td><?= htmlspecialchars($joueur['Statut']) ?></td>
            </tr>
        </table>
    </main>
</body>
</html>

