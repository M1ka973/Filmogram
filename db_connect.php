<?php
/*******************************************************
 *  FICHIER : db_connect.php
 *  DESCRIPTION : Connexion à la base de données MySQL
 *  PROJET : FILMOGRAM
 *******************************************************/

// Informations de connexion
$host = 'localhost';        // Serveur MySQL (ne pas changer sous XAMPP)
$dbname = 'filmogram_dtb';  // Nom de la base créée
$username = 'root';         // Utilisateur par défaut de XAMPP
$password = '';             // Mot de passe vide par défaut

try {
    // Création d'une nouvelle connexion PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    // Configuration des attributs PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Active les erreurs
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Retourne les résultats en tableau associatif

    // Test visuel facultatif (à supprimer en production)
    // echo "✅ Connexion réussie à la base de données $dbname";
} catch (PDOException $e) {
    // En cas d’erreur, on affiche un message clair
    die("❌ Erreur de connexion : " . $e->getMessage());
}
?>
