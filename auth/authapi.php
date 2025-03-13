<?php
require_once "connexionDB.php";
require_once "jwt_utils.php";

header("Content-Type: application/json; charset=UTF-8");
$linkPDO = $pdo;
    
$method = $_SERVER["REQUEST_METHOD"];

if ($method !== "POST") {
    http_response_code(405);
    echo json_encode(["message" => "Méthode non autorisée"]);
    exit;
}

// Lire les données envoyées en JSON
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['login']) || !isset($data['password'])) {
    http_response_code(400);
    echo json_encode(["message" => "Login et mot de passe requis"]);
    exit;
}

// Vérifier l'utilisateur en base de données
$stmt = $linkPDO->prepare("SELECT * FROM user WHERE login = :login");
$stmt->bindParam(':login', $data['login']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($data['password'], $user['password'])) {
    http_response_code(401);
    echo json_encode(["message" => "Identifiants incorrects"]);
    exit;
}

// Générer le JWT
$secret_key = "your_secret_key";  // Change ça en une vraie clé secrète
$headers = ['alg' => 'HS256', 'typ' => 'JWT'];
$payload = [
    'login' => $user['login'],
    'role' => $user['role'],
    'exp' => time() + 3600, // Expire en 1h
    'id' => $user['id']
];

$jwt = generate_jwt($headers, $payload, $secret_key);

// Retourner le token
http_response_code(200);
echo json_encode(["token" => $jwt, "role" => $user['role']]);
?>