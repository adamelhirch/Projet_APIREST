<?php
include 'connexionDB.php';

    function getAllJoueurs() {
        global $pdo;
        try {
            $query = "SELECT num_licence, nom, prenom, date_naissance, taille, poids, statut FROM joueur";
            return $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erreur lors de la récupération des joueurs : " . $e->getMessage());
        }
    }

    function deleteJoueur($numLicence) {
        global $pdo;
        try {
            $query = "DELETE FROM joueur WHERE num_licence = :num_licence";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['num_licence' => $numLicence]);
        } catch (PDOException $e) {
            die("Erreur lors de la suppression : " . $e->getMessage());
        }
    }

    function insertJoueur($numLicence, $nom, $prenom, $dateNaissance, $taille, $poids, $statut) {
        global $pdo;
        try {
            $sql = "INSERT INTO joueur (num_licence, nom, prenom, date_naissance, taille, poids, statut)
                    VALUES (:num_licence, :nom, :prenom, :date_naissance, :taille, :poids, :statut)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':num_licence' => $numLicence,
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':date_naissance' => $dateNaissance,
                ':taille' => $taille,
                ':poids' => $poids,
                ':statut' => $statut,
            ]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de l'insertion : " . $e->getMessage());
        }
    }

    // Nouvelle fonction : Récupérer les joueurs de base
    function getAllJoueursMinimal() {
        global $pdo;
        try {
            $query = "SELECT num_licence, nom, prenom FROM joueur";
            $stmt = $pdo->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des joueurs : " . $e->getMessage());
        }
    }
    
    function insertMatch($dateMatch, $equipeAdverse, $lieu, $resultat) {
        global $pdo;
        try {
            $query = "INSERT INTO match_foot (date_match, equipe_adverse, lieux, resultat)
                      VALUES (:date_match, :equipe_adverse, :lieu, :resultat)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':date_match' => $dateMatch,
                ':equipe_adverse' => $equipeAdverse,
                ':lieu' => $lieu,
                ':resultat' => $resultat,
            ]);
            return $pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de l'ajout du match : " . $e->getMessage());
        }
    }
    
    function assignPlayerToMatch($idMatch, $numLicence) {
        global $pdo;
        try {
            $query = "INSERT INTO selection (id_match, num_licence) 
                      VALUES (:id_match, :num_licence)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':id_match' => $idMatch,
                ':num_licence' => $numLicence,
            ]);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de l'assignation des joueurs : " . $e->getMessage());
        }
    }
    
    function getJoueurByLicence($numLicence) {
        global $pdo;
        try {
            $query = "SELECT num_licence, nom, prenom, date_naissance, taille, poids, statut 
                      FROM joueur WHERE num_licence = :num_licence";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['num_licence' => $numLicence]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération du joueur : " . $e->getMessage());
        }
    }
    function updateJoueur($numLicence, $nom, $prenom, $dateNaissance, $taille, $poids, $statut) {
        global $pdo;
        try {
            $query = "UPDATE joueur 
                      SET nom = :nom, prenom = :prenom, date_naissance = :date_naissance, 
                          taille = :taille, poids = :poids, statut = :statut 
                      WHERE num_licence = :num_licence";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                'nom' => $nom,
                'prenom' => $prenom,
                'date_naissance' => $dateNaissance,
                'taille' => $taille,
                'poids' => $poids,
                'statut' => $statut,
                'num_licence' => $numLicence
            ]);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la mise à jour du joueur : " . $e->getMessage());
        }
    }
    function getAllMatches() {
        global $pdo;
        try {
            $stmt = $pdo->query(" SELECT id_match, date_match, equipe_adverse, lieux, resultat FROM match_foot");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des matchs : " . $e->getMessage());
        }
    }

    function ajouterCommentaire($numLicence, $contenu) {
        global $pdo;
        try {
            $stmt = $pdo->prepare("INSERT INTO commentaire (num_licence, contenu) VALUES (:num_licence, :contenu)");
            $stmt->bindParam(':num_licence', $numLicence);
            $stmt->bindParam(':contenu', $contenu);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de l'ajout du commentaire : " . $e->getMessage());
            return false;
        }
    }
    
    function ajouterNote($idMatch, $numLicence, $evaluation) {
        global $pdo;
        try {
            $stmt = $pdo->prepare("
                UPDATE selection
                SET evaluation = :evaluation
                WHERE id_match = :id_match
                  AND num_licence = :num_licence
            ");
            $stmt->execute([
                ':evaluation' => $evaluation,
                ':id_match'   => $idMatch,
                ':num_licence'=> $numLicence,
            ]);
            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour de l'évaluation : " . $e->getMessage());
            return false;
        }
    }    
    
    // Supprimer un match
    function deleteMatch($idMatch) {
        global $pdo;
        try {
            // Supprimer d'abord les enregistrements dépendants dans la table selection
            $queryDeleteSelection = "DELETE FROM selection WHERE id_match = :id_match";
            $stmtDeleteSelection = $pdo->prepare($queryDeleteSelection);
            $stmtDeleteSelection->execute(['id_match' => $idMatch]);
    
            // Ensuite, supprimer le match dans la table match_foot
            $queryDeleteMatch = "DELETE FROM match_foot WHERE id_match = :id_match";
            $stmtDeleteMatch = $pdo->prepare($queryDeleteMatch);
            $stmtDeleteMatch->execute(['id_match' => $idMatch]);
    
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la suppression du match : " . $e->getMessage());
        }
    }
    
    function getSelectionByMatch($idMatch) {
        global $pdo;
        try {
            $query = "SELECT 
                        j.num_licence, 
                        j.nom, 
                        j.prenom, 
                        mj.role, 
                        mj.titulaire, 
                        mj.evaluation
                      FROM selection mj
                      INNER JOIN joueur j ON mj.num_licence = j.num_licence
                      WHERE mj.id_match = :id_match";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':id_match' => $idMatch]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des joueurs pour le match : " . $e->getMessage());
        }
    }
    function getMatchesWithoutComposition() {
        global $pdo;
        try {
            $query = "
                SELECT 
                    id_match, 
                    date_match, 
                    equipe_adverse, 
                    lieux, 
                    resultat 
                FROM match_foot m
                WHERE NOT EXISTS (
                    SELECT 1 
                    FROM selection c
                    WHERE c.id_match = m.id_match
                )
            ";
            $stmt = $pdo->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des matchs sans composition : " . $e->getMessage());
        }
    }
    function getMatchResults() {
        global $pdo;
        try {
            $query = "SELECT resultat, COUNT(*) AS total FROM match_foot GROUP BY resultat";
            $stmt = $pdo->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des résultats des matchs : " . $e->getMessage());
        }
    }
    function getTopPlayersStats() {
        global $pdo;
        try {
            $query = "
                SELECT nom, prenom, AVG(evaluation) AS moyenne
                FROM joueur
                JOIN selection ON joueur.num_licence = selection.num_licence
                WHERE evaluation IS NOT NULL
                GROUP BY joueur.num_licence, nom, prenom
                ORDER BY moyenne DESC
                LIMIT 5
            ";
            $stmt = $pdo->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des statistiques des joueurs : " . $e->getMessage());
        }
    }
    /*--------------requette ajout du commentaire-----------------*/
    function getSelectionWithComments($idMatch) {
        global $pdo;
        $query = "
            SELECT 
                s.num_licence, 
                j.nom, 
                j.prenom, 
                s.role, 
                s.titulaire, 
                c.contenu AS commentaire
            FROM selection s
            LEFT JOIN joueur j ON s.num_licence = j.num_licence
            LEFT JOIN appartenir a ON s.num_licence = a.num_licence
            LEFT JOIN commentaire c ON a.id_commentaire = c.id_commentaire
            WHERE s.id_match = :id_match
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':id_match' => $idMatch]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    function updateSelection($role, $titulaire, $poste, $numLicence, $idMatch) {
        global $pdo;
        try {
            $query = "
                UPDATE selection 
                SET role = :role, titulaire = :titulaire, oste = :poste 
                WHERE num_licence = :num_licence AND id_match = :id_match
            ";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':role' => $role,
                ':titulaire' => $titulaire,
                ':poste' => $poste,
                ':num_licence' => $numLicence,
                ':id_match' => $idMatch,
            ]);
        } catch (PDOException $e) {
            die("Erreur lors de la mise à jour de la sélection : " . $e->getMessage());
        }
        
    }
    function getMatchById($idMatch) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM match_foot WHERE id_match = :id_match");
        $stmt->bindParam(':id_match', $idMatch, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    function getActiveJoueurs() {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM joueur WHERE statut = 'Actif'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    function getStatsJoueurs() {
        global $pdo;
        $query = "
            SELECT 
                j.nom,
                j.prenom,
                j.statut,
                COALESCE(s.role, 'Non défini') AS poste,
                SUM(CASE WHEN s.titulaire = 1 THEN 1 ELSE 0 END) AS titularisations,
                SUM(CASE WHEN s.titulaire = 0 THEN 1 ELSE 0 END) AS remplacements,
                AVG(CASE WHEN s.evaluation IS NOT NULL THEN s.evaluation ELSE NULL END) AS moyenne_note,
                CASE 
                    WHEN COUNT(s.id_match) = 0 THEN 0 -- Si aucun match, le pourcentage est 0
                    ELSE 
                        (SUM(CASE WHEN m.Resultat = 'Victoire' THEN 1 ELSE 0 END) * 100) / COUNT(s.id_match) -- Calcul du % de victoires
                END AS pourcentage_gagnes,
                (
                    SELECT MAX(consecutive_selections)
                    FROM (
                        SELECT 
                            num_licence,
                            @row_number := IF(@prev_licence = num_licence, @row_number + 1, 1) AS consecutive_selections,
                            @prev_licence := num_licence
                        FROM selection s2, (SELECT @row_number := 0, @prev_licence := NULL) vars
                        ORDER BY s2.num_licence, s2.id_match
                    ) sub
                    WHERE sub.num_licence = j.num_licence
                ) AS selections_consecutives
            FROM joueur j
            LEFT JOIN selection s ON j.num_licence = s.num_licence
            LEFT JOIN match_foot m ON s.id_match = m.id_match
            GROUP BY j.num_licence
        ";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


?>
