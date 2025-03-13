<?php
require 'db_connect.php';
require 'queries.php';

$joueurs = [];
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Si une suppression est demandée
    if (isset($_GET['delete']) && isset($_GET['numero_licence'])) {
        deleteJoueur($pdo, $_GET['numero_licence']);
        header('Location: page_joueur.php');
        exit();
    }

    // Récupération des joueurs
    $joueurs = getAllJoueurs($pdo);
}
?>

<?php
require 'db_connect.php';
require 'queries.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Récupérer les données du formulaire
        $numero_de_licence = $_POST['numero_de_licence'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $date_de_naissance = $_POST['date_de_naissance'];
        $taille = $_POST['taille'];
        $poids = $_POST['poids'];
        $statut = $_POST['statut'];

        // Insérer le joueur dans la base de données
        insertJoueur($pdo, $numero_de_licence, $nom, $prenom, $date_de_naissance, $taille, $poids, $statut);

        // Redirection avec un message de succès
        header('Location: Ajouter_joueur.php?success=1');
        exit();
    } catch (Exception $e) {
        // Redirection avec un message d'erreur
        header('Location: Ajouter_joueur.php?error=' . urlencode($e->getMessage()));
        exit();
    }
}
?>



