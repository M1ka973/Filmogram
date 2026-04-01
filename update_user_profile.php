<?php
/**
 * update_user_profile.php
 * Met à jour le profil de l'utilisateur connecté (version SIMPLE).
 */

header('Content-Type: application/json; charset=utf-8');

// 1) Session obligatoire (on ne peut modifier que son propre profil)
session_start();
if (!isset($_SESSION['iduser'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Utilisateur non connecté']);
    exit();
}

// 2) Lecture du JSON envoyé par fetch()
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['error' => 'Données invalides']);
    exit();
}

// 3) Récupération + nettoyage très simple des champs
$prenom  = trim((string)($data['prenom'] ?? ''));
$nom     = trim((string)($data['nom'] ?? ''));
$email   = trim((string)($data['email'] ?? ''));
$tel     = trim((string)($data['tel'] ?? ''));
$adresse = trim((string)($data['adresse'] ?? ''));

// 4) Validation minimale
if ($prenom === '' || $nom === '' || $email === '' || $adresse === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Veuillez remplir prénom, nom, email et adresse']);
    exit();
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Email invalide']);
    exit();
}

// 5) Update en base (table : user)
require_once 'db_connect.php';

try {
    $iduser = (int)$_SESSION['iduser'];

    $stmt = $pdo->prepare("
        UPDATE user
        SET prenom = :prenom,
            nom = :nom,
            adresse = :adresse,
            email = :email,
            tel = :tel
        WHERE iduser = :iduser
    ");

    $stmt->execute([
        ':prenom' => $prenom,
        ':nom' => $nom,
        ':adresse' => $adresse,
        ':email' => $email,
        ':tel' => ($tel === '' ? null : $tel),
        ':iduser' => $iduser,
    ]);

    // 6) Retourner les nouvelles infos au front
    echo json_encode([
        'prenom' => $prenom,
        'nom' => $nom,
        'adresse' => $adresse,
        'email' => $email,
        'tel' => ($tel === '' ? null : $tel),
    ], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    // Erreur simple : email déjà utilisé (contrainte UNIQUE)
    if ((string)$e->getCode() === '23000') {
        http_response_code(409);
        echo json_encode(['error' => 'Cet email est déjà utilisé']);
        exit();
    }

    http_response_code(500);
    echo json_encode(['error' => 'Erreur serveur']);
}
