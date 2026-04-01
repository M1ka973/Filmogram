<?php
/**
 * films_api.php — API REST pour la gestion des films (admin)
 */
require_once '../../check_admin_session.php';
require_once '../../db_connect.php';

header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    // ── GET : liste tous les films ──────────────────────────────────────────
    case 'GET':
        $stmt = $pdo->query("SELECT * FROM film ORDER BY idfilm ASC");
        echo json_encode(['success' => true, 'films' => $stmt->fetchAll()]);
        break;

    // ── POST : ajouter un film ──────────────────────────────────────────────
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $nom     = trim($data['nom']     ?? '');
        $age     = (int)($data['age']    ?? 0);
        $genre   = trim($data['genre']   ?? '');
        $prix    = (float)($data['prix'] ?? 0);
        $synopsis = trim($data['synopsis'] ?? '');
        $image   = trim($data['image']   ?? '🎬');
        $poster  = trim($data['poster']  ?? '');

        if (!$nom || !$genre || $prix <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Données invalides.']);
            break;
        }

        $stmt = $pdo->prepare("INSERT INTO film (nom, age, genre, prix, synopsis, image, poster) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([$nom, $age, $genre, $prix, $synopsis, $image, $poster]);
        $newId = $pdo->lastInsertId();
        echo json_encode(['success' => true, 'message' => 'Film ajouté.', 'idfilm' => $newId]);
        break;

    // ── PUT : modifier un film ──────────────────────────────────────────────
    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $id      = (int)($data['idfilm'] ?? 0);
        $nom     = trim($data['nom']     ?? '');
        $age     = (int)($data['age']    ?? 0);
        $genre   = trim($data['genre']   ?? '');
        $prix    = (float)($data['prix'] ?? 0);
        $synopsis = trim($data['synopsis'] ?? '');
        $image   = trim($data['image']   ?? '🎬');
        $poster  = trim($data['poster']  ?? '');

        if (!$id || !$nom || !$genre || $prix <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Données invalides.']);
            break;
        }

        $stmt = $pdo->prepare("UPDATE film SET nom=?, age=?, genre=?, prix=?, synopsis=?, image=?, poster=? WHERE idfilm=?");
        $stmt->execute([$nom, $age, $genre, $prix, $synopsis, $image, $poster, $id]);
        echo json_encode(['success' => true, 'message' => 'Film mis à jour.']);
        break;

    // ── DELETE : supprimer un film ──────────────────────────────────────────
    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);
        $id   = (int)($data['idfilm'] ?? 0);

        if (!$id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID invalide.']);
            break;
        }

        $pdo->prepare("DELETE FROM film WHERE idfilm = ?")->execute([$id]);
        echo json_encode(['success' => true, 'message' => 'Film supprimé.']);
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}
