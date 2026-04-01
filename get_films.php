<?php
/**
 * get_films.php — API publique pour afficher les films sur film.html
 * Pas de protection de session (accès public)
 */
require_once 'db_connect.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

try {
    $stmt = $pdo->query("SELECT idfilm, nom, age, genre, prix, synopsis, image, poster FROM film ORDER BY idfilm ASC");
    $films = $stmt->fetchAll();
    echo json_encode(['success' => true, 'films' => $films]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur base de données.']);
}
