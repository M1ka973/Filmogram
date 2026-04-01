<?php
/**
 * change_password.php
 * Change le mot de passe de l'utilisateur connecté.
 * Entrée JSON: { "current_password": "...", "new_password": "..." }
 */

header('Content-Type: application/json; charset=utf-8');

session_start(); //initialisation d'un nouvelle session
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

$current = (string)($data['current_password'] ?? '');
$next = (string)($data['new_password'] ?? '');

if (strlen($next) < 8) {
    http_response_code(400);
    echo json_encode(['error' => 'Le nouveau mot de passe doit contenir au moins 8 caractères']);
    exit();
}
if ($current === $next) {
    http_response_code(400);
    echo json_encode(['error' => 'Le nouveau mot de passe doit être différent de l\'ancien']);
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
    $ok = $isHash ? password_verify($current, $stored) : hash_equals($stored, $current);

    if (!$ok) {
        http_response_code(403);
        echo json_encode(['error' => 'Mot de passe actuel incorrect']);
        exit();
    }

    $hash = password_hash($next, PASSWORD_DEFAULT);
    $upd = $pdo->prepare("UPDATE user SET password = :password WHERE iduser = :iduser");
    $upd->execute([':password' => $hash, ':iduser' => $iduser]);

    echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur serveur']);
}

