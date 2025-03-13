<?php
require_once 'db_connect.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];

    // Hash du mot de passe saisi
    $hashedPassword = hash('sha256', $inputPassword);

    try {
        // Requête pour récupérer l'utilisateur
        $sql = "SELECT * FROM entraineur WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $inputUsername);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Vérification du mot de passe
            if ($hashedPassword === $user['password']) {
                // Redirection en cas de succès
                header("Location: index.php");
                exit();
            } else {
                $error = "Mot de passe incorrect.";
            }
        } else {
            $error = "Nom d'utilisateur incorrect.";
        }
    } catch (PDOException $e) {
        $error = "Erreur de connexion : " . $e->getMessage();
    }
}
?>
