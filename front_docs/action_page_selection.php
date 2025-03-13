<?php
require_once 'db_connect.php';

$selection = [];
$ID_Match = null;
$matchDatePassed = false;

try {
    // Vérifier si l'ID_Match est fourni dans l'URL ou via le formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_comment'])) {
        // ID_Match transmis via le formulaire
        $ID_Match = $_POST['id_match'];
        $numeroDeLicence = $_POST['numero_de_licence'];
        $evaluation = $_POST['evaluation'] ?? null;
        $commentaire = $_POST['commentaire'] ?? null;

        // Ajouter la note dans la table "selection"
        if (!empty($evaluation)) {
            $query = "UPDATE selection SET Evaluation = :evaluation WHERE Numero_de_licence = :numeroDeLicence AND ID_Match = :idMatch";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':evaluation' => $evaluation,
                ':numeroDeLicence' => $numeroDeLicence,
                ':idMatch' => $ID_Match
            ]);
        }

        // Ajouter le commentaire dans la table "commentaire"
        if (!empty($commentaire)) {
            // Insérer le commentaire dans la table "Commentaire"
            $query = "INSERT INTO commentaire (Contenu) VALUES (:commentaire)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':commentaire' => $commentaire]);

            // Récupérer l'ID du commentaire inséré
            $idCommentaire = $pdo->lastInsertId();

            // Lier le commentaire au joueur et au match dans "Appartenir"
            $queryLink = "INSERT INTO appartenir (Numero_de_licence, ID_Commentaire, ID_Match) VALUES (:numeroDeLicence, :idCommentaire, :idMatch)";
            $stmtLink = $pdo->prepare($queryLink);
            $stmtLink->execute([
                ':numeroDeLicence' => $numeroDeLicence,
                ':idCommentaire' => $idCommentaire,
                ':idMatch' => $ID_Match
            ]);
        }

        // Rediriger après le traitement
        header("Location: page_selection.php?ID_Match=$ID_Match");
        exit();
    } elseif (isset($_GET['ID_Match'])) {
        // ID_Match transmis via l'URL
        $ID_Match = $_GET['ID_Match'];

        // Récupérer la date du match
        $queryDate = "SELECT Date_match FROM match_foot WHERE ID_Match = :idMatch";
        $stmtDate = $pdo->prepare($queryDate);
        $stmtDate->execute([':idMatch' => $ID_Match]);
        $matchData = $stmtDate->fetch(PDO::FETCH_ASSOC);

        if ($matchData) {
            $matchDate = $matchData['Date_match'];
            $currentDate = date('Y-m-d');

            // Vérifier si la date du match est passée
            $matchDatePassed = $matchDate < $currentDate;
        } else {
            throw new Exception("Aucune information trouvée pour ce match.");
        }

        // Récupérer la sélection des joueurs titulaires avec leurs commentaires pour ce match
        $query = "
            SELECT 
                s.Numero_de_licence, 
                j.Nom, 
                j.Prenom, 
                s.Role, 
                s.Titulaire, 
                s.Evaluation, 
                c.Contenu AS Commentaire
            FROM selection s
            LEFT JOIN joueur j ON s.Numero_de_licence = j.Numero_de_licence
            LEFT JOIN appartenir a ON s.Numero_de_licence = a.Numero_de_licence AND a.ID_Match = s.ID_Match
            LEFT JOIN commentaire c ON a.ID_Commentaire = c.ID_Commentaire
            WHERE s.ID_Match = :idMatch AND s.Titulaire = 1 -- Filtrer uniquement les titulaires
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':idMatch' => $ID_Match]);
        $selection = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        throw new Exception("ID_Match manquant dans l'URL.");
    }
} catch (Exception $e) {
    echo "<p>Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
    exit();
}
?>
