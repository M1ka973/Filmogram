<?php
/**
 * delete_account.php
 * Supprime le compte de l'utilisateur connecté.
 * Entrée JSON: { "password": "..." }
 */

header('Content-Type: application/json; charset=utf-8');

session_start();
if (!isset($_SESSION['iduser'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Utilisateur non connecté']);
    exit();
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['error' => 'Données invalides']);
    exit();
}

$password = (string)($data['password'] ?? '');
if ($password === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Mot de passe requis']);
    exit();
}

require_once 'db_connect.php';

try {
    $iduser = (int)$_SESSION['iduser'];

    $stmt = $pdo->prepare("SELECT password FROM user WHERE iduser = :iduser");
    $stmt->execute([':iduser' => $iduser]);
    $row = $stmt->fetch();
    if (!$row) {
        http_response_code(404);
        echo json_encode(['error' => 'Utilisateur introuvable']);
        exit();
    }

    $stored = (string)$row['password'];
    $isHash = str_starts_with($stored, '$2y$') || str_starts_with($stored, '$argon2');
    $ok = $isHash ? password_verify($password, $stored) : hash_equals($stored, $password);

    if (!$ok) {
        http_response_code(403);
        echo json_encode(['error' => 'Mot de passe incorrect']);
        exit();
    }

    // Supprimer l'utilisateur
    $del = $pdo->prepare("DELETE FROM user WHERE iduser = :iduser");
    $del->execute([':iduser' => $iduser]);

    // Déconnecter
    session_unset();
    session_destroy();

    echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur serveur']);
}

