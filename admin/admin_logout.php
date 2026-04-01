<?php
/**
 * admin_logout.php — Déconnexion de l'administrateur
 */

// Récupère le chemin du script
$path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/';
if ($path === '//')
    $path = '/';

// Configure les paramètres du cookie de session
// - path : '/' pour que le cookie soit accessible partout sur le site
// - httponly : empêche l’accès au cookie via JavaScript → plus sécurisé
// - samesite=Lax : limite les risques de vols de session (CSRF)
session_set_cookie_params([
    'path' => '/',
    'httponly' => true,
    'samesite' => 'Lax'
]);

// Démarre la session pour pouvoir la détruire ensuite
session_start();

// Détruit toutes les données de la session (déconnexion)
session_destroy();

// Redirige l’administrateur vers la page de connexion
header('Location: admin_login.php');
exit();