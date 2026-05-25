<?php
/**
 * login.php — Point d'entrée de l'authentification utilisateur.
 *
 * Ce fichier traite uniquement les soumissions POST du formulaire situé dans Vue/login.html.
 * Tout accès direct via GET redirige vers la nouvelle page de sélection de rôle (Vue/login.html).
 */

// Configuration du cookie de session (cohérence avec check_session.php)
$path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/';
if ($path === '//') $path = '/';
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => $path,
    'domain'   => '',
    'secure'   => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

// ── Accès direct GET → redirection vers la nouvelle page de connexion ──────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $redirect = 'Vue/login.html';
    if (isset($_GET['require_login'])) {
        $redirect .= '?require_login=1';
    }
    header('Location: ' . $redirect);
    exit();
}

// ── Traitement du formulaire POST ───────────────────────────────────────────────
require_once 'db_connect.php';

$email         = trim($_POST['email'] ?? '');
$mdp           = $_POST['password'] ?? '';
$error_message = '';

if (empty($email) || empty($mdp)) {
    $error_message = "Veuillez remplir tous les champs.";
} else {
    try {
        $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $ok = false;
        if ($user) {
            $stored = (string)$user['password'];
            $isHash = str_starts_with($stored, '$2y$') || str_starts_with($stored, '$argon2');
            $ok     = $isHash ? password_verify($mdp, $stored) : hash_equals($stored, $mdp);

            // Upgrade du mot de passe en clair vers un hash bcrypt
            if ($ok && !$isHash) {
                $hash = password_hash($mdp, PASSWORD_DEFAULT);
                $u    = $pdo->prepare("UPDATE user SET password = :password WHERE iduser = :iduser");
                $u->execute([':password' => $hash, ':iduser' => (int)$user['iduser']]);
            }
        }

        if ($user && $ok) {
            $_SESSION['iduser'] = $user['iduser'];
            $_SESSION['email']  = $user['email'];
            $_SESSION['nom']    = $user['nom'];
            header('Location: index.html');
            exit();
        } else {
            $error_message = "Email ou mot de passe incorrect.";
        }

    } catch (PDOException $e) {
        $error_message = "Erreur de connexion : " . $e->getMessage();
    }
}

// En cas d'erreur, retour vers le formulaire avec le message d'erreur dans l'URL
header('Location: Vue/login.html?login_error=' . urlencode($error_message));
exit();