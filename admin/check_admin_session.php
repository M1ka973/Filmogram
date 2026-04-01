<?php
/**
 * check_admin_session.php — Middleware de protection admin
 * À inclure en haut de chaque page admin protégée
 */
$path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/';
if ($path === '//') $path = '/';
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'domain'   => '',
    'secure'   => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit();
}
