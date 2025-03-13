<?php
require_once 'db_connect.php';

$match = [];
$ID_Match = null;

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer les données du formulaire
        $ID_Match = $_POST['id_match'];
        $Date_match = $_POST['date_match'] ?? null;
        $Equipe_adverse = $_POST['equipe_adverse'] ?? null;
        $Lieux = $_POST['Lieux'] ?? null;
        $Resultat = $_POST['Resultat'] ?? null;

        // Vérifier si le match est passé
        $query = "SELECT Date_match FROM match_foot WHERE ID_Match = :id_match";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':id_match' => $ID_Match]);
        $match = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$match) {
            throw new Exception("Match introuvable.");
        }

        $isPassed = (new DateTime($match['Date_match'])) < (new DateTime());

        if ($isPassed) {
            // Mise à jour uniquement du résultat si le match est passé
            $query = "UPDATE match_foot SET Resultat = :resultat WHERE ID_Match = :id_match";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':resultat' => $Resultat,
                ':id_match' => $ID_Match,
            ]);
        } else {
            // Mise à jour complète si le match n'est pas passé
            $query = "
                UPDATE match_foot 
                SET Date_match = :date_match, 
                    Equipe_adverse = :equipe_adverse, 
                    Lieux = :lieux, 
                    Resultat = :resultat
                WHERE ID_Match = :id_match
            ";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':date_match' => $Date_match,
                ':equipe_adverse' => $Equipe_adverse,
                ':lieux' => $Lieux,
                ':resultat' => $Resultat,
                ':id_match' => $ID_Match,
            ]);
        }

        // Redirection après mise à jour
        header("Location: page_match.php");
        exit();
    } elseif (isset($_GET['ID_Match'])) {
        // Récupérer les détails du match
        $ID_Match = $_GET['ID_Match'];
        $query = "SELECT * FROM match_foot WHERE ID_Match = :id_match";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':id_match' => $ID_Match]);
        $match = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$match) {
            throw new Exception("Match introuvable.");
        }
    } else {
        throw new Exception("ID_Match manquant dans l'URL.");
    }
} catch (Exception $e) {
    echo "<p>Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
    exit();
}
