<?php
/**
 * setup_admin.php — Script one-shot pour initialiser le compte admin
 * Accéder une seule fois via : http://localhost/Filmogram/admin/setup_admin.php
 * SUPPRIMER ou PROTÉGER ce fichier après utilisation !
 */
require_once '../db_connect.php';

$messages = [];

try {
    // 1. Ajouter la colonne role si elle n'existe pas
    try {
        $pdo->exec("ALTER TABLE user ADD COLUMN role ENUM('user','admin') DEFAULT 'user'");
        $messages[] = "✅ Colonne 'role' ajoutée à la table user.";
    } catch (PDOException $e) {
        $messages[] = "ℹ️ Colonne 'role' existe déjà (OK).";
    }

    // 2. Ajouter les colonnes synopsis, image, poster à film
    foreach (['synopsis TEXT', "image VARCHAR(10) DEFAULT '🎬'", 'poster VARCHAR(255)'] as $col) {
        try {
            $pdo->exec("ALTER TABLE film ADD COLUMN $col");
            $messages[] = "✅ Colonne '$col' ajoutée à film.";
        } catch (PDOException $e) {
            $messages[] = "ℹ️ Colonne '$col' existe déjà (OK).";
        }
    }

    // 3. Créer la table admin_security si elle n'existe pas
    $pdo->exec("CREATE TABLE IF NOT EXISTS admin_security (
        id INT AUTO_INCREMENT PRIMARY KEY,
        admin_id INT NOT NULL,
        question TEXT NOT NULL,
        answer_hash TEXT NOT NULL,
        FOREIGN KEY (admin_id) REFERENCES user(iduser) ON DELETE CASCADE
    )");
    $messages[] = "✅ Table 'admin_security' créée/vérifiée.";

    // 4. Créer le compte admin si inexistant
    $check = $pdo->prepare("SELECT iduser FROM user WHERE email = 'admin@filmogram.com'");
    $check->execute();
    $adminUser = $check->fetch();

    if (!$adminUser) {
        $passwordHash = password_hash('Admin123!', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO user (prenom, nom, adresse, email, tel, password, role) VALUES (?, ?, ?, ?, ?, ?, 'admin')");
        $stmt->execute(['Admin', 'Filmogram', '1 rue du Cinéma', 'admin@filmogram.com', '0600000000', $passwordHash]);
        $adminId = $pdo->lastInsertId();
        $messages[] = "✅ Compte admin créé (ID: $adminId).";
    } else {
        $adminId = $adminUser['iduser'];
        // Mettre à jour le rôle ET régénérer le mot de passe avec un hash PHP frais
        $freshHash = password_hash('Admin123!', PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE user SET role='admin', password=? WHERE iduser=?")->execute([$freshHash, $adminId]);
        $messages[] = "ℹ️ Compte admin existe déjà (ID: $adminId) — rôle et mot de passe réinitialisés.";
    }

    // 5. Créer la question secrète si inexistante
    $checkSec = $pdo->prepare("SELECT id FROM admin_security WHERE admin_id = ?");
    $checkSec->execute([$adminId]);
    if (!$checkSec->fetch()) {
        $answerHash = password_hash('filmogram', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO admin_security (admin_id, question, answer_hash) VALUES (?, ?, ?)");
        $stmt->execute([$adminId, 'Quel est le nom de cette plateforme ?', $answerHash]);
        $messages[] = "✅ Question secrète 2FA créée. Réponse par défaut : <strong>filmogram</strong>";
    } else {
        $messages[] = "ℹ️ Question secrète 2FA déjà configurée.";
    }

    $messages[] = "<br><strong>🎉 Setup terminé ! Vous pouvez maintenant vous connecter.</strong>";
    $messages[] = "<br>📧 Email : <code>admin@filmogram.com</code>";
    $messages[] = "🔑 Mot de passe : <code>Admin123!</code>";
    $messages[] = "❓ Réponse secrète : <code>filmogram</code>";
    $messages[] = "<br><a href='admin_login.php'>→ Aller à la connexion admin</a>";

} catch (PDOException $e) {
    $messages[] = "❌ Erreur : " . htmlspecialchars($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Setup Admin — FILMOGRAM</title>
<style>
body { font-family: Arial, sans-serif; background:#111; color:#eee; padding:40px; }
.box { background:#1a1a2e; border-radius:12px; padding:30px; max-width:600px; margin:auto; }
h1 { color:#e94560; }
p { line-height:1.8; }
code { background:#2a2a4a; padding:2px 6px; border-radius:4px; color:#f39c12; }
a { color:#667eea; }
</style>
</head>
<body>
<div class="box">
    <h1>⚙️ Setup Admin — FILMOGRAM</h1>
    <?php foreach ($messages as $m): ?>
        <p><?= $m ?></p>
    <?php endforeach; ?>
</div>
</body>
</html>
