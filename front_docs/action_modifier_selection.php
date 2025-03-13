<?php
require_once 'db_connect.php';

// Initialiser les variables
$erreur = "";
$success = "";
$isSuccess = false;
$idMatch = isset($_GET['id_match']) ? htmlspecialchars($_GET['id_match']) : htmlspecialchars($_POST['id_match'] ?? null);
$selection = [];

if (!$idMatch) {
    $erreur = "Erreur : ID du match non spécifié.";
} else {
    try {
        // Récupérer les informations de sélection pour le match
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
            LEFT JOIN appartenir a ON s.Numero_de_licence = a.Numero_de_licence AND s.ID_Match = a.ID_Match
            LEFT JOIN commentaire c ON a.ID_Commentaire = c.ID_Commentaire
            WHERE s.ID_Match = :idMatch
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':idMatch' => $idMatch]);
        $selection = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $erreur = "Erreur lors de la récupération des données : " . htmlspecialchars($e->getMessage());
    }
}

// Traiter la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier_selection'])) {
    try {
        // Compter les joueurs sélectionnés comme titulaires
        $titulaireCount = isset($_POST['titulaire']) ? count($_POST['titulaire']) : 0;

        // Vérifier que la sélection contient exactement 11 joueurs
        if ($titulaireCount !== 11) {
            $erreur = "La sélection doit contenir exactement 11 joueurs titulaires. Actuellement : $titulaireCount.";
        } else {
            // Mise à jour des données dans la base de données
            foreach ($_POST['role'] as $numeroDeLicence => $role) {
                $titulaire = isset($_POST['titulaire'][$numeroDeLicence]) ? 1 : 0;
                $evaluation = $_POST['evaluation'][$numeroDeLicence] ?? null;
                $commentaire = $_POST['commentaire'][$numeroDeLicence] ?? null;

                // Mise à jour de la table "selection"
                $queryUpdateSelection = "
                    UPDATE selection
                    SET Role = :role, Titulaire = :titulaire, Evaluation = :evaluation
                    WHERE Numero_de_licence = :numeroDeLicence AND ID_Match = :idMatch
                ";
                $stmtUpdate = $pdo->prepare($queryUpdateSelection);
                $stmtUpdate->execute([
                    ':role' => $role,
                    ':titulaire' => $titulaire,
                    ':evaluation' => $evaluation,
                    ':numeroDeLicence' => $numeroDeLicence,
                    ':idMatch' => $idMatch,
                ]);

                // Gestion des commentaires
                if (!empty($commentaire)) {
                    // Insertion du commentaire dans la table "commentaire"
                    $queryInsertComment = "INSERT INTO commentaire (Contenu) VALUES (:commentaire)";
                    $stmtComment = $pdo->prepare($queryInsertComment);
                    $stmtComment->execute([':commentaire' => $commentaire]);

                    // Récupération de l'ID du commentaire inséré
                    $idCommentaire = $pdo->lastInsertId();

                    // Lier le commentaire au joueur et au match dans "appartenir"
                    $queryLinkComment = "
                        INSERT INTO appartenir (Numero_de_licence, ID_Commentaire, ID_Match)
                        VALUES (:numeroDeLicence, :idCommentaire, :idMatch)
                    ";
                    $stmtLink = $pdo->prepare($queryLinkComment);
                    $stmtLink->execute([
                        ':numeroDeLicence' => $numeroDeLicence,
                        ':idCommentaire' => $idCommentaire,
                        ':idMatch' => $idMatch,
                    ]);
                }
            }

            // Opération réussie
            $isSuccess = true;
            $success = "Les modifications ont été enregistrées avec succès.";
            header("Location: page_match.php?numero_licence=$id_match&success=1");
        }
    } catch (Exception $e) {
        $erreur = "Erreur lors de la mise à jour : " . htmlspecialchars($e->getMessage());
    }
}
?>
