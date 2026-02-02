<?php
/**
 * FICHIER : get_user_profile.php
 * DESCRIPTION : Récupère les informations du profil de l'utilisateur connecté
 * PROJET : FILMOGRAM
 */

// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['iduser'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Utilisateur non connecté']);
    exit();
}

// Inclure le fichier de connexion à la base de données
require_once 'db_connect.php';

try {
    // Récupérer l'ID de l'utilisateur depuis la session
    $iduser = $_SESSION['iduser'];
    
    // Préparer la requête SQL pour récupérer toutes les informations de l'utilisateur
    $stmt = $pdo->prepare("SELECT prenom, nom, adresse, email, tel FROM user WHERE iduser = :iduser");
    $stmt->bindParam(':iduser', $iduser, PDO::PARAM_INT);
    $stmt->execute();
    
    // Récupérer les données de l'utilisateur
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        // Retourner les données en JSON
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($user, JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Utilisateur non trouvé']);
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur de base de données : ' . $e->getMessage()]);
}
?>


