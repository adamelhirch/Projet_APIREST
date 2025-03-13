<?php
require_once 'db_connect.php';
require_once 'queries.php';

$statsJoueurs = [];

try {
    // Appel à la fonction pour récupérer les statistiques des joueurs
    $statsJoueurs = getStatsJoueurs($pdo);
} catch (Exception $e) {
    echo "Erreur : " . htmlspecialchars($e->getMessage());
}
?>
