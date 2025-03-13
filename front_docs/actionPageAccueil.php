<?php
require_once 'db_connect.php';
require_once 'queries.php';

try {
    // Récupérer les résultats des matchs (pour le diagramme circulaire)
    $matchResults = getMatchResults($pdo);
    $data = [
        'Victoire' => 0,
        'Défaite' => 0,
        'Nul' => 0
    ];

    foreach ($matchResults as $result) {
        if (isset($data[$result['resultat']])) {
            $data[$result['resultat']] = (int) $result['total'];
        }
    }

    $totalMatches = array_sum($data);
    $percentages = [
        'Victoire' => ($totalMatches > 0) ? ($data['Victoire'] / $totalMatches) * 100 : 0,
        'Défaite' => ($totalMatches > 0) ? ($data['Défaite'] / $totalMatches) * 100 : 0,
        'Nul' => ($totalMatches > 0) ? ($data['Nul'] / $totalMatches) * 100 : 0
    ];
    $query = "SELECT COUNT(*) AS total_players FROM joueur";
    $stmt = $pdo->query($query);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalPlayers = $result['total_players'];

    $recentMatchesQuery = "
        SELECT Date_match, Equipe_adverse, Resultat 
        FROM match_foot 
        ORDER BY Date_match DESC 
        LIMIT 5
    ";
    $recentMatches = $pdo->query($recentMatchesQuery)->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les statistiques des 5 meilleurs joueurs
    $topPlayersStats = getTopPlayersStats($pdo);
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
    $percentages = ['Victoire' => 0, 'Défaite' => 0, 'Nul' => 0];
    $topPlayersStats = [];
}
?>
