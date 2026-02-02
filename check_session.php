<?php
/**
 * Vérifie si l'utilisateur est connecté (session active).
 * Retourne un JSON : { "logged_in": true|false }
 */
// Cookie de session : même chemin que l'app (ex. /Filmogram/) pour que fetch() le reçoive
$path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/';
if ($path === '//') $path = '/';
session_set_cookie_params([
    'lifetime' => 0,
    'path' => $path,
    'domain' => '',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate');

$logged_in = isset($_SESSION['iduser']);
echo json_encode(['logged_in' => $logged_in]);
