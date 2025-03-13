<?php
    function getAllJoueurs($pdo) {
        try {
            $query = "SELECT Numero_de_licence, Nom, Prenom, Date_de_naissance, Taille, Poids, Statut FROM joueur";
            return $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erreur lors de la récupération des joueurs : " . $e->getMessage());
        }
    }

    function deleteJoueur($pdo, $numero_licence) {
        try {
            $query = "DELETE FROM joueur WHERE Numero_de_licence = :numero_licence";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['numero_licence' => $numero_licence]);
        } catch (PDOException $e) {
            die("Erreur lors de la suppression : " . $e->getMessage());
        }
    }

    function insertJoueur($pdo, $numero_de_licence, $nom, $prenom, $date_de_naissance, $taille, $poids, $statut) {
        try {
            $sql = "INSERT INTO joueur (Numero_de_licence, Nom, Prenom, Date_de_naissance, Taille, Poids, Statut)
                    VALUES (:numero_de_licence, :nom, :prenom, :date_de_naissance, :taille, :poids, :statut)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':numero_de_licence' => $numero_de_licence,
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':date_de_naissance' => $date_de_naissance,
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
    function getAllJoueursMinimal($pdo) {
        try {
            $query = "SELECT Numero_de_licence, Nom, Prenom FROM joueur";
            $stmt = $pdo->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des joueurs : " . $e->getMessage());
        }
    }
    
    function insertMatch($pdo, $dateMatch, $equipeAdverse, $lieu, $resultat) {
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
    
    function assignPlayerToMatch($pdo, $matchId, $numeroDeLicence) {
        try {
            $query = "INSERT INTO selection (ID_Match, Numero_de_licence) 
                      VALUES (:ID_Match, :Numero_de_licence)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':ID_Match' => $matchId,
                ':Numero_de_licence' => $numeroDeLicence,
            ]);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de l'assignation des joueurs : " . $e->getMessage());
        }
    }
    
    function getJoueurByLicence($pdo, $numero_licence) {
        try {
            $query = "SELECT Numero_de_licence, Nom, Prenom, Date_de_naissance, Taille, Poids, Statut 
                      FROM joueur WHERE Numero_de_licence = :numero_licence";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['numero_licence' => $numero_licence]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération du joueur : " . $e->getMessage());
        }
    }
    function updateJoueur($pdo, $numero_licence, $nom, $prenom, $date_naissance, $taille, $poids, $statut) {
        try {
            $query = "UPDATE joueur 
                      SET Nom = :nom, Prenom = :prenom, Date_de_naissance = :date_naissance, 
                          Taille = :taille, Poids = :poids, Statut = :statut 
                      WHERE Numero_de_licence = :numero_licence";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                'nom' => $nom,
                'prenom' => $prenom,
                'date_naissance' => $date_naissance,
                'taille' => $taille,
                'poids' => $poids,
                'statut' => $statut,
                'numero_licence' => $numero_licence
            ]);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la mise à jour du joueur : " . $e->getMessage());
        }
    }
    function getAllMatches($pdo) {
        try {
            $query = "
                SELECT 
                    ID_Match, 
                    Date_match, 
                    Equipe_adverse, 
                    Lieux, 
                    Resultat 
                FROM match_foot
            ";
            $stmt = $pdo->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des matchs : " . $e->getMessage());
        }
    }

    function ajouterCommentaire($pdo, $numeroDeLicence, $commentaire) {
        try {
            $stmt = $pdo->prepare("INSERT INTO commentaire (Numero_de_licence, Texte) VALUES (:numeroDeLicence, :texte)");
            $stmt->bindParam(':numeroDeLicence', $numeroDeLicence);
            $stmt->bindParam(':texte', $commentaire);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de l'ajout du commentaire : " . $e->getMessage());
            return false;
        }
    }
    function ajouterNote($pdo, $numeroDeLicence, $Evaluation) {
        try {
            $stmt = $pdo->prepare("INSERT INTO selection (Numero_de_licence, Evaluation) VALUES (:numeroDeLicence, :Evaluation)");
            $stmt->bindParam(':numeroDeLicence', $numeroDeLicence);
            $stmt->bindParam(':Evaluation', $Evaluation);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de l'ajout de la Evaluation : " . $e->getMessage());
            return false;
        }
    }
    
    // Supprimer un match
    function deleteMatch($pdo, $ID_Match) {
        try {
            // Supprimer d'abord les enregistrements dépendants dans la table selection
            $queryDeleteSelection = "DELETE FROM selection WHERE ID_Match = :ID_Match";
            $stmtDeleteSelection = $pdo->prepare($queryDeleteSelection);
            $stmtDeleteSelection->execute(['ID_Match' => $ID_Match]);
    
            // Ensuite, supprimer le match dans la table match_foot
            $queryDeleteMatch = "DELETE FROM match_foot WHERE ID_Match = :ID_Match";
            $stmtDeleteMatch = $pdo->prepare($queryDeleteMatch);
            $stmtDeleteMatch->execute(['ID_Match' => $ID_Match]);
    
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la suppression du match : " . $e->getMessage());
        }
    }
    
    function getSelectionByMatch($pdo, $ID_Match) {
        try {
            $query = "SELECT 
                        j.Numero_de_licence, 
                        j.Nom, 
                        j.Prenom, 
                        mj.Role, 
                        mj.Titulaire, 
                        mj.Evaluation
                      FROM selection mj
                      INNER JOIN joueur j ON mj.Numero_de_licence = j.Numero_de_licence
                      WHERE mj.ID_Match = :ID_Match";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':ID_Match' => $ID_Match]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des joueurs pour le match : " . $e->getMessage());
        }
    }
    function getMatchesWithoutComposition($pdo) {
        try {
            $query = "
                SELECT 
                    ID_Match, 
                    Date_match, 
                    Equipe_adverse, 
                    Lieux, 
                    Resultat 
                FROM match_foot m
                WHERE NOT EXISTS (
                    SELECT 1 
                    FROM selection c
                    WHERE c.ID_Match = m.ID_Match
                )
            ";
            $stmt = $pdo->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des matchs sans composition : " . $e->getMessage());
        }
    }
    function getMatchResults($pdo) {
        try {
            $query = "SELECT resultat, COUNT(*) AS total FROM match_foot GROUP BY resultat";
            $stmt = $pdo->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des résultats des matchs : " . $e->getMessage());
        }
    }
    function getTopPlayersStats($pdo) {
        try {
            $query = "
                SELECT Nom, Prenom, AVG(Evaluation) AS moyenne
                FROM joueur
                JOIN selection ON joueur.Numero_de_licence = selection.Numero_de_licence
                WHERE Evaluation IS NOT NULL
                GROUP BY joueur.Numero_de_licence, Nom, Prenom
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
    function getSelectionWithComments($pdo) {
        $query = "
            SELECT 
                s.Numero_de_licence, 
                j.Nom, 
                j.Prenom, 
                s.Role, 
                s.Titulaire, 
                c.Contenu AS Commentaire
            FROM selection s
            LEFT JOIN joueur j ON s.Numero_de_licence = j.Numero_de_licence
            LEFT JOIN appartenir a ON s.Numero_de_licence = a.Numero_de_licence
            LEFT JOIN commentaire c ON a.ID_Commentaire = c.ID_Commentaire
            WHERE s.ID_Match = :id_match
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id_match' => $idMatch]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    function updateSelection($pdo, $role, $titulaire, $poste, $numeroDeLicence, $idMatch) {
        try {
            $query = "
                UPDATE selection 
                SET Role = :role, Titulaire = :titulaire, Poste = :poste 
                WHERE Numero_de_licence = :numeroDeLicence AND ID_Match = :idMatch
            ";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':role' => $role,
                ':titulaire' => $titulaire,
                ':poste' => $poste,
                ':numeroDeLicence' => $numeroDeLicence,
                ':idMatch' => $idMatch,
            ]);
        } catch (PDOException $e) {
            die("Erreur lors de la mise à jour de la sélection : " . $e->getMessage());
        }
        
    }
    function getMatchById($pdo, $ID_Match) {
        $stmt = $pdo->prepare("SELECT * FROM Match_foot WHERE ID_Match = :ID_Match");
        $stmt->bindParam(':ID_Match', $ID_Match, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    function getActiveJoueurs($pdo) {
        $stmt = $pdo->prepare("SELECT * FROM Joueur WHERE Statut = 'Actif'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    function getStatsJoueurs($pdo) {
        $query = "
            SELECT 
                j.Nom,
                j.Prenom,
                j.Statut,
                COALESCE(s.Role, 'Non défini') AS Poste,
                SUM(CASE WHEN s.Titulaire = 1 THEN 1 ELSE 0 END) AS Titularisations,
                SUM(CASE WHEN s.Titulaire = 0 THEN 1 ELSE 0 END) AS Remplacements,
                AVG(CASE WHEN s.Evaluation IS NOT NULL THEN s.Evaluation ELSE NULL END) AS Moyenne_Note,
                CASE 
                    WHEN COUNT(s.ID_Match) = 0 THEN 0 -- Si aucun match, le pourcentage est 0
                    ELSE 
                        (SUM(CASE WHEN m.Resultat = 'Victoire' THEN 1 ELSE 0 END) * 100) / COUNT(s.ID_Match) -- Calcul du % de victoires
                END AS Pourcentage_Gagnes,
                (
                    SELECT MAX(Consecutive_Selections)
                    FROM (
                        SELECT 
                            Numero_de_licence,
                            @row_number := IF(@prev_licence = Numero_de_licence, @row_number + 1, 1) AS Consecutive_Selections,
                            @prev_licence := Numero_de_licence
                        FROM selection s2, (SELECT @row_number := 0, @prev_licence := NULL) vars
                        ORDER BY s2.Numero_de_licence, s2.ID_Match
                    ) sub
                    WHERE sub.Numero_de_licence = j.Numero_de_licence
                ) AS Selections_Consecutives
            FROM joueur j
            LEFT JOIN selection s ON j.Numero_de_licence = s.Numero_de_licence
            LEFT JOIN match_foot m ON s.ID_Match = m.ID_Match
            GROUP BY j.Numero_de_licence
        ";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

?>