            <!--PAGE signup.php !-->
<?php
// On Démarrer la session de l'utilisateur
session_start();

// Configuration de la base de données
$host = 'localhost';
$dbname = 'filmogram_dtb';
$username = 'root';
$password = '';

// Messages
$error_message = '';
$success_message = '';

// On Vérifie si le formulaire a été soumis
if (isset($_POST['Inscription'])) {
    
    // On Récupère et sécurise les données
    $prenom = trim($_POST['prenom']);
    $nom = trim($_POST['nom']);
    $adresse = trim($_POST['adresse']);
    $email = trim($_POST['email']);
    $tel = trim($_POST['tel']);
    $mdp = $_POST['password'];
    $mdp_confirm = $_POST['password_confirm'];
    
    //          <!--LE CONDITIONS DE Validation --!>

    //SI TOUT LES CHAMPS NE SONT PAS REMPLIS ! 
    if (empty($prenom) || empty($nom) || empty($adresse) || empty($email) || empty($mdp) || empty($mdp_confirm)) {
        $error_message = "Tous les champs obligatoires doivent être remplis.";
    } 
    //SI l'email n'est pas correct
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "L'email n'est pas valide.";
    }
    // SI les conditions de mdp ne sont pas rescpecter 
    elseif (strlen($mdp) < 6) {
        $error_message = "Le mot de passe doit contenir au moins 6 caractères.";
    }
    //SI les mots de passes ne sont pas identiques
    elseif ($mdp !== $mdp_confirm) {
        $error_message = "Les mots de passe ne correspondent pas.";
    }
    else {
        try {
            // Connexion à la base de données
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Vérifier si l'email existe déjà
            $stmt = $pdo->prepare("SELECT iduser FROM user WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $error_message = "Cet email est déjà utilisé.";
            } else {
                // Insérer le nouvel utilisateur (mot de passe hashé)
                $hash = password_hash($mdp, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("
                    INSERT INTO user (prenom, nom, adresse, email, tel, password) 
                    VALUES (:prenom, :nom, :adresse, :email, :tel, :mdp)
                ");
                
                $stmt->bindParam(':prenom', $prenom);
                $stmt->bindParam(':nom', $nom);
                $stmt->bindParam(':adresse', $adresse);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':tel', $tel);
                $stmt->bindParam(':mdp', $hash);
                
                if ($stmt->execute()) {
                    $success_message = "Inscription réussie ! Vous allez être redirigé vers la page de connexion...";
                    // Redirection après 2 secondes
                    header("refresh:2;url=login.php");
                }
            }
            
        } catch (PDOException $e) {
            $error_message = "Erreur : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Filmogram</title>

                <!--PARTIE CSS-->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .signup-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 500px;
            padding: 40px;
        }

        .signup-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .signup-header h2 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .signup-header p {
            color: #666;
            font-size: 14px;
        }

        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .input-group {
            flex: 1;
            margin-bottom: 20px;
        }

        .form-row .input-group {
            margin-bottom: 0;
        }

        .input-group label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .input-group label .required {
            color: #e74c3c;
        }

        .input-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s;
        }

        .input-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
            text-align: center;
            font-weight: 500;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
            text-align: center;
            font-weight: 500;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }

        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .password-hint {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
        }
    </style>



</head>
<body>
    <div class="signup-container">
        <div class="signup-header">
            <h2>📽️ Créer un compte</h2>
            <p>Rejoignez Filmogram dès maintenant</p>
        </div>

        <?php if (!empty($success_message)): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="signup.php">
            <div class="form-row">
                <div class="input-group">
                    <label for="prenom">Prénom <span class="required">*</span></label>
                    <input type="text" name="prenom" id="prenom" 
                           placeholder="Jean" required 
                           value="<?php echo isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : ''; ?>">
                </div>
                <div class="input-group">
                    <label for="nom">Nom <span class="required">*</span></label>
                    <input type="text" name="nom" id="nom" 
                           placeholder="Dupont" required
                           value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>">
                </div>
            </div>

            <div class="input-group">
                <label for="email">Email <span class="required">*</span></label>
                <input type="email" name="email" id="email" 
                       placeholder="jean.dupont@email.com" required
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>

            <div class="input-group">
                <label for="adresse">Adresse <span class="required">*</span></label>
                <input type="text" name="adresse" id="adresse" 
                       placeholder="123 rue du Cinéma, 75000 Paris" required
                       value="<?php echo isset($_POST['adresse']) ? htmlspecialchars($_POST['adresse']) : ''; ?>">
            </div>

            <div class="input-group">
                <label for="tel">Téléphone</label>
                <input type="tel" name="tel" id="tel" 
                       placeholder="0612345678"
                       value="<?php echo isset($_POST['tel']) ? htmlspecialchars($_POST['tel']) : ''; ?>">
            </div>

            <div class="input-group">
                <label for="password">Mot de passe <span class="required">*</span></label>
                <input type="password" name="password" id="password" 
                       placeholder="••••••••" required minlength="6">
                <p class="password-hint">Minimum 6 caractères</p>
            </div>

            <div class="input-group">
                <label for="password_confirm">Confirmer le mot de passe <span class="required">*</span></label>
                <input type="password" name="password_confirm" id="password_confirm" 
                       placeholder="••••••••" required minlength="6">
            </div>

            <button type="submit" name="Inscription" class="btn-submit">
                S'inscrire
            </button>

            <div class="login-link">
                Vous avez déjà un compte ? <a href="login.php">Connectez-vous ici</a>
            </div>
        </form>
    </div>
</body>
</html>