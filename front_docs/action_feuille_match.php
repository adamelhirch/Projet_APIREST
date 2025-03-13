<?php
require_once 'db_connect.php';
require_once 'queries.php';

$matches = [];
$joueurs = [];

try {
    // Récupérer uniquement les matchs sans composition
    $matches = getMatchesWithoutComposition($pdo);

    // Récupérer uniquement les joueurs actifs
    $joueurs = getActiveJoueurs($pdo);
} catch (Exception $e) {
    echo "Erreur : " . htmlspecialchars($e->getMessage());
    $matches = [];
    $joueurs = [];
}

// Traitement du formulaire pour enregistrer la composition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save-composition'])) {
    try {
        $matchId = $_POST['match-select'];
        $titulaireData = $_POST['titulaire'] ?? [];
        $RoleData = $_POST['Role'] ?? [];
        $EvaluationData = $_POST['Evaluation'] ?? [];

        // Vérifier qu'il y a exactement 11 titulaires sélectionnés
        if (count($titulaireData) !== 11) {
            throw new Exception("Vous devez sélectionner exactement 11 titulaires pour enregistrer la composition.");
        }

        // Parcourir les joueurs pour enregistrer la composition
        foreach ($joueurs as $joueur) {
            $licence = $joueur['Numero_de_licence'];
            $isTitulaire = isset($titulaireData[$licence]) ? 1 : 0;
            $Role = $RoleData[$licence] ?? '';
            $Evaluation = $EvaluationData[$licence] ?? null;

            $query = "
                INSERT INTO selection (ID_Match, Numero_de_licence, Titulaire, Role, Evaluation)
                VALUES (:matchId, :licence, :titulaire, :Role, :Evaluation)
                ON DUPLICATE KEY UPDATE
                    Titulaire = :titulaire,
                    Role = :Role,
                    Evaluation = :Evaluation
            ";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':matchId' => $matchId,
                ':licence' => $licence,
                ':titulaire' => $isTitulaire,
                ':Role' => $Role,
                ':Evaluation' => $Evaluation
            ]);
        }

        // Message de succès
        echo "<script>
                alert('La composition a été enregistrée avec succès !');
                setTimeout(() => {
                    window.location.href = 'page_match.php';
                }, 3000); // Redirection après 3 secondes
              </script>";
    } catch (Exception $e) {
        // Gestion des erreurs
        echo "<script>
                alert('Erreur : " . htmlspecialchars($e->getMessage()) . "');
                window.history.back();
              </script>";
    }
}
?>
