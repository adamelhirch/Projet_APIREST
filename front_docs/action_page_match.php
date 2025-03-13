<?php
require_once 'db_connect.php';
require_once 'queries.php';

$matches = [];

try {
    // Supprimer un match si demandé
    if (isset($_GET['delete']) && isset($_GET['ID_Match'])) {
        $ID_Match = $_GET['ID_Match'];

        // Vérifier si le match existe et récupérer ses détails
        $match = getMatchById($pdo, $ID_Match);

        if ($match) {
            $currentDate = new DateTime();
            $matchDate = new DateTime($match['Date_match']);

            // Vérifier si la date du match est passée
            if ($matchDate < $currentDate) {
                // La date est passée, rediriger avec un message d'erreur
                header('Location: page_match.php?error=match_date_passed');
                exit();
            }

            // Si la date n'est pas passée, on peut supprimer
            deleteMatch($pdo, $ID_Match);
            header('Location: page_match.php');
            exit();
        } else {
            // Le match n'existe pas
            header('Location: page_match.php?error=match_not_found');
            exit();
        }
    }

    // Récupérer tous les matchs
    $matches = getAllMatches($pdo);
} catch (Exception $e) {
    echo "<p>Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
