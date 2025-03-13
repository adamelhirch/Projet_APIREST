<?php
require_once 'db_connect.php';
require_once 'queries.php';

$message = "";

try {
    // Si un numéro de licence est fourni dans l'URL, récupérer les informations du joueur
    if (isset($_GET['numero_licence'])) {
        $numero_licence = $_GET['numero_licence'];
        $player = getJoueurByLicence($pdo, $numero_licence);
    }

    // Si le formulaire est soumis, mettre à jour les informations du joueur
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $numero_licence = $_POST['numero_licence'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $date_naissance = $_POST['date_naissance'];
        $taille = $_POST['taille'];
        $poids = $_POST['poids'];
        $statut = $_POST['statut'];

        updateJoueur($pdo, $numero_licence, $nom, $prenom, $date_naissance, $taille, $poids, $statut);

        $message = "Les informations du joueur ont été mises à jour avec succès.";
        header("Location: page_joueur.php?numero_licence=$numero_licence&success=1");
        exit();
    }
} catch (PDOException $e) {
    $message = "Erreur : " . $e->getMessage();
}
?>
