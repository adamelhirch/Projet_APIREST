<?php
require_once 'db_connect.php';
require_once 'queries.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $dateMatch = $_POST['Date_match'];
        $equipeAdverse = $_POST['Equipe_adverse'];
        $lieu = $_POST['Lieux'];
        $resultat = $_POST['Resultat'];

        $matchId = insertMatch($pdo, $dateMatch, $equipeAdverse, $lieu, $resultat);

        if (isset($_POST['joueurs_assignes'])) {
            $joueursAssignes = json_decode($_POST['joueurs_assignes'], true);
            foreach ($joueursAssignes as $posteId => $joueur) {
                assignPlayerToMatch($pdo, $matchId, $joueur['Numero_de_licence']);
            }
        }

        header('Location: page_match.php?success=1');
        exit();
    }
} catch (Exception $e) {
    header('Location: ajouter_match.php?error=' . urlencode($e->getMessage()));
    exit();
}
?>
