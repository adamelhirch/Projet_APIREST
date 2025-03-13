<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

require_once 'functions.php';
require_once '../../auth/jwt_utils.php'; // Charger le fichier de gestion des JWT

// Vérifier la présence du token dans le header Authorization
$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(["message" => "Token manquant"]);
    exit;
}

// Extraire le token JWT
$token = str_replace("Bearer ", "", $headers['Authorization']);

// Vérifier la validité du token
if (!is_jwt_valid($token, "your_secret_key")) {
    http_response_code(403);
    echo json_encode(["message" => "Token invalide ou expiré"]);
    exit;
}
function deliver_response($status_code, $status_message, $data = null) {
    http_response_code($status_code);
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode([
        "status_code" => $status_code,
        "status_message" => $status_message,
        "data" => $data
    ]);
}

// Récupération de l'URL demandée et segmentation
$request_uri = explode("/", trim($_SERVER['REQUEST_URI'], "/"));
$version = isset($request_uri[0]) ? $request_uri[0] : null;
$method = $_SERVER["REQUEST_METHOD"];
$id = isset($_GET['id']) ? (int)$_GET['id'] : null; // Assurer un entier
$input = json_decode(file_get_contents("php://input"), true);
$action = $_GET['action'] ?? '';


$response = [];

// Gestion des requêtes selon la méthode HTTP
switch ($method) {
    case "GET":
        /**
         * Exemples d'actions possibles en GET:
         *  - action=allPlayers        -> Retourne la liste complète des joueurs
         *  - action=playerMinimal     -> Retourne la liste minimale (num_licence, nom, prenom)
         *  - action=playerByLicence   -> Retourne un seul joueur via son num_licence (= $id)
         *  - action=allMatches        -> Liste tous les matchs
         *  - action=matchById         -> Affiche un match par son id_match (= $id)
         *  - action=selectionByMatch  -> Liste la sélection de joueurs pour un match donné
         *  - action=topPlayers        -> Top 5 joueurs (moyenne des évaluations)
         *  - action=statsJoueurs      -> Statistiques complètes de tous les joueurs
         *  - action=matchesNoComp     -> Liste les matchs sans composition
         *  - action=matchResults      -> Récupération du total de Victoires / Défaites / Nuls
         */
        if ($action === 'allPlayers') {
            $data = getAllJoueurs();
            deliver_response(200, "Liste complète des joueurs", $data);
        } 
        elseif ($action === 'playerMinimal') {
            $data = getAllJoueursMinimal();
            deliver_response(200, "Liste minimale des joueurs", $data);
        } 
        elseif ($action === 'playerByLicence' && !empty($id)) {
            $data = getJoueurByLicence($id);
            deliver_response(200, "Joueur récupéré", $data);
        }
        elseif ($action === 'allMatches') {
            $data = getAllMatches();
            deliver_response(200, "Liste de tous les matchs", $data);
        }
        elseif ($action === 'matchById' && !empty($id)) {
            $data = getMatchById($id);
            deliver_response(200, "Match récupéré", $data);
        }
        elseif ($action === 'selectionByMatch' && !empty($id)) {
            $data = getSelectionByMatch($id);
            deliver_response(200, "Sélection du match récupérée", $data);
        }
        elseif ($action === 'topPlayers') {
            $data = getTopPlayersStats();
            deliver_response(200, "Top 5 joueurs (moyenne évaluation)", $data);
        }
        elseif ($action === 'statsJoueurs') {
            $data = getStatsJoueurs();
            deliver_response(200, "Statistiques complètes des joueurs", $data);
        }
        elseif ($action === 'matchesNoComp') {
            $data = getMatchesWithoutComposition();
            deliver_response(200, "Matchs sans composition récupérés", $data);
        }
        elseif ($action === 'matchResults') {
            $data = getMatchResults();
            deliver_response(200, "Statistiques globales de résultats des matchs", $data);
        }
        else {
            // Action non reconnue ou manquante
            deliver_response(400, "Action GET non reconnue ou paramètre manquant");
        }
        break;

    case "POST":
        /**
         * Exemples d'actions possibles en POST:
         *  - action=joueur  -> Insertion d'un nouveau joueur
         *  - action=match   -> Insertion d'un nouveau match
         *  - action=assign  -> Assigner un joueur à un match (table selection)
         *  - action=comment -> Ajouter un commentaire
         *  - action=note    -> Ajouter une note (évaluation)
         */
        if ($action === 'joueur') {
            // Exemple : On attend que $input contienne toutes les infos du joueur
            if (
                !empty($input['num_licence']) &&
                !empty($input['nom']) &&
                !empty($input['prenom']) &&
                !empty($input['date_naissance']) &&
                isset($input['taille']) &&
                isset($input['poids']) &&
                !empty($input['statut'])
            ) {
                try {
                    insertJoueur(
                        $input['num_licence'],
                        $input['nom'],
                        $input['prenom'],
                        $input['date_naissance'],
                        $input['taille'],
                        $input['poids'],
                        $input['statut']
                    );
                    deliver_response(201, "Joueur inséré avec succès");
                } catch (Exception $e) {
                    deliver_response(400, $e->getMessage());
                }
            } else {
                deliver_response(400, "Données insuffisantes pour insérer un joueur");
            }
        }
        elseif ($action === 'match') {
            // Insertion d'un match
            if (
                !empty($input['date_match']) &&
                !empty($input['equipe_adverse']) &&
                isset($input['lieu']) &&
                isset($input['resultat'])
            ) {
                try {
                    $matchId = insertMatch(
                        $input['date_match'],
                        $input['equipe_adverse'],
                        $input['lieu'],
                        $input['resultat']
                    );
                    deliver_response(201, "Match inséré avec succès", ["id_match" => $matchId]);
                } catch (Exception $e) {
                    deliver_response(400, $e->getMessage());
                }
            } else {
                deliver_response(400, "Données insuffisantes pour insérer un match");
            }
        }
        elseif ($action === 'assign') {
            // Assigner un joueur à un match
            if (!empty($input['id_match']) && !empty($input['num_licence'])) {
                try {
                    assignPlayerToMatch($input['id_match'], $input['num_licence']);
                    deliver_response(201, "Joueur assigné au match avec succès");
                } catch (Exception $e) {
                    deliver_response(400, $e->getMessage());
                }
            } else {
                deliver_response(400, "Paramètres id_match ou num_licence manquants");
            }
        }
        elseif ($action === 'comment') {
            // Ajouter un commentaire à un joueur
            if (!empty($input['num_licence']) && !empty($input['contenu'])) {
                $ok = ajouterCommentaire($input['num_licence'], $input['contenu']);
                if ($ok) {
                    deliver_response(201, "Commentaire ajouté avec succès");
                } else {
                    deliver_response(400, "Erreur lors de l'ajout du commentaire");
                }
            } else {
                deliver_response(400, "num_licence ou contenu manquant");
            }
        }
        elseif ($action === 'note') {
            // Ajouter une évaluation
            if (!empty($input['num_licence']) && isset($input['evaluation'])) {
                $ok = ajouterNote($input['num_licence'], $input['evaluation']);
                if ($ok) {
                    deliver_response(201, "Évaluation ajoutée avec succès");
                } else {
                    deliver_response(400, "Erreur lors de l'ajout de l'évaluation");
                }
            } else {
                deliver_response(400, "num_licence ou évaluation manquant");
            }
        }
        else {
            deliver_response(400, "Action POST non reconnue ou paramètre manquant");
        }
        break;

    case "PUT":
        /**
         * Exemples d'actions possibles en PUT:
         *  - action=joueur  -> Mise à jour complète d'un joueur (num_licence en param GET ou route)
         *  - action=match   -> Mise à jour d'un match (id_match en param)
         */
        if ($action === 'joueur' && !empty($id)) {
            // On suppose que $id = num_licence
            if (
                !empty($input['nom']) &&
                !empty($input['prenom']) &&
                !empty($input['date_naissance']) &&
                isset($input['taille']) &&
                isset($input['poids']) &&
                !empty($input['statut'])
            ) {
                try {
                    updateJoueur(
                        $id,
                        $input['nom'],
                        $input['prenom'],
                        $input['date_naissance'],
                        $input['taille'],
                        $input['poids'],
                        $input['statut']
                    );
                    deliver_response(200, "Joueur mis à jour avec succès");
                } catch (Exception $e) {
                    deliver_response(400, $e->getMessage());
                }
            } else {
                deliver_response(400, "Données insuffisantes pour la mise à jour du joueur");
            }
        }
        // Vous pouvez ajouter ici un elseif ($action === 'match') pour la mise à jour d'un match
        else {
            deliver_response(400, "Action PUT non reconnue ou ID manquant");
        }
        break;

    case "PATCH":
        /**
         * Le PATCH peut servir aux mises à jour partielles
         * Ex. : action=selection -> mise à jour de la table selection (champs: role, titulaire, poste, etc.)
         */
        if ($action === 'selection') {
            if (
                !empty($input['id_match']) &&
                !empty($input['num_licence']) &&
                isset($input['role']) &&
                isset($input['titulaire']) &&
                isset($input['poste'])
            ) {
                try {
                    updateSelection(
                        $input['role'],
                        $input['titulaire'],
                        $input['poste'],
                        $input['num_licence'],
                        $input['id_match']
                    );
                    deliver_response(200, "Sélection mise à jour avec succès");
                } catch (Exception $e) {
                    deliver_response(400, $e->getMessage());
                }
            } else {
                deliver_response(400, "Paramètres manquants pour PATCH (selection)");
            }
        }
        else {
            deliver_response(400, "Action PATCH non reconnue ou paramètre manquant");
        }
        break;

    case "DELETE":
        /**
         * Exemples d'actions possibles en DELETE:
         *  - action=joueur  -> Suppression d’un joueur (num_licence)
         *  - action=match   -> Suppression d’un match (id_match)
         */
        if ($action === 'joueur' && !empty($id)) {
            try {
                deleteJoueur($id);
                deliver_response(200, "Joueur supprimé avec succès");
            } catch (Exception $e) {
                deliver_response(400, $e->getMessage());
            }
        }
        elseif ($action === 'match' && !empty($id)) {
            try {
                deleteMatch($id);
                deliver_response(200, "Match supprimé avec succès");
            } catch (Exception $e) {
                deliver_response(400, $e->getMessage());
            }
        }
        else {
            deliver_response(400, "Action DELETE non reconnue ou ID manquant");
        }
        break;

    default:
        // Méthode non supportée
        deliver_response(405, "Méthode HTTP non supportée");
        break;
}
?>
