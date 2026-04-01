<!--PAGE login.php !-->
<?php
// Cookie de session : même chemin que l'app (ex. /Filmogram/) pour cohérence avec check_session.php
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
// Démarrer la session
session_start();

require_once 'db_connect.php';

// Message d'erreur
$error_message = '';

// Afficher la popup "Veuillez vous connecter" si redirection depuis Panier/Profil
$show_connect_message = isset($_GET['require_login']);

// Vérifier si le formulaire a été soumis
if (isset($_POST['Connexion'])) {
    
    // Récupérer et sécuriser les données
    $email = trim($_POST['email']);
    $mdp = $_POST['password'];
    
    // Validation basique
    if (empty($email) || empty($mdp)) {
        $error_message = "Veuillez remplir tous les champs.";
    } else {
        try {
            // Préparer la requête SQL (protection contre injection SQL)
            $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            // Récupérer l'utilisateur
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Vérifier le mot de passe (hashé ou en clair, avec upgrade automatique)
            $ok = false;
            if ($user) {
                $stored = (string)$user['password'];
                $isHash = str_starts_with($stored, '$2y$') || str_starts_with($stored, '$argon2');
                $ok = $isHash ? password_verify($mdp, $stored) : hash_equals($stored, $mdp);

                // Upgrade: si ancien mot de passe en clair, on le remplace par un hash
                if ($ok && !$isHash) {
                    $hash = password_hash($mdp, PASSWORD_DEFAULT);
                    $u = $pdo->prepare("UPDATE user SET password = :password WHERE iduser = :iduser");
                    $u->execute([':password' => $hash, ':iduser' => (int)$user['iduser']]);
                }
            }

            if ($user && $ok) {
                // Connexion réussie
                $_SESSION['iduser'] = $user['iduser'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['nom'] = $user['nom'];
                
                // Redirection vers la page d'accueil
                header('Location: index.html');
                exit();
            } else {
                $error_message = "Email ou mot de passe incorrect.";
            }
            
        } catch (PDOException $e) {
            $error_message = "Erreur de connexion : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Styles généraux */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Conteneur principal */
        .login-container {
            display: flex;
            background: white;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            width: 800px;
            max-width: 90%;
        }

        .illustration {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
            flex: 1;
        }

        .illustration img {
            max-width: 100%;
            width: 100px;
            height: auto;
        }

        .login-form {
            flex: 1;
            padding: 40px;
            text-align: center;
            height: 350px;   
        }

        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .input-group {
            text-align: left;
            margin-bottom: 15px;
        }

        .input-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #555;
        }

        .error-message {
            color: red;
            margin-top: 10px;
            margin-bottom: 10px;
            font-size: 14px;
            padding: 8px;
            background-color: #ffe6e6;
            border-radius: 5px;
        }

        .signup {
            margin-top: 20px;
            font-size: 14px;
        }

        .signup a {
            color: #007bff;
            text-decoration: none;
        }

        .signup a:hover {
            text-decoration: underline;
        }

        .connect-popup {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #667eea;
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            z-index: 9999;
            font-weight: 600;
            animation: slideDown 0.3s ease;
        }
        @keyframes slideDown {
            from { opacity: 0; top: -50px; }
            to { opacity: 1; top: 20px; }
        }
    </style>
</head>
<body>
    <?php if ($show_connect_message): ?>
    <div class="connect-popup" id="connectPopup">Veuillez vous connecter</div>
    <script>
        setTimeout(function() {
            var p = document.getElementById('connectPopup');
            if (p) p.style.display = 'none';
        }, 4000);
    </script>
    <?php endif; ?>
    <div class="login-container">
        <div class="illustration">
            <img src="img/batman_001.png" alt="Illustration">
        </div>
        <div class="login-form">
            <h2>Bienvenue sur le Terrain</h2>
            <form method="post" action="login.php">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Entrez votre email" required>
                </div>
                <div class="input-group password-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" placeholder="Entrez votre mot de passe" required>
                </div>
                
                <?php if (!empty($error_message)): ?>
                    <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>
                
                <button type="submit" name="Connexion">Connexion</button>
                <div class="signup">
                    <p>Pas encore de compte ? <a href="signup.php">Inscrivez-vous ici</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>