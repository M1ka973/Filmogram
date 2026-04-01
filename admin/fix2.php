<?php
require_once '../db_connect.php';

$newHash    = password_hash('Admin123!', PASSWORD_DEFAULT);
$answerHash = password_hash('filmogram', PASSWORD_DEFAULT);

// Force update password + role
$pdo->prepare("UPDATE user SET password=?, role='admin' WHERE email='admin@filmogram.com'")
    ->execute([$newHash]);

// Delete and recreate secret question for admin ID 1
$pdo->exec("DELETE FROM admin_security WHERE admin_id=1");
$pdo->prepare("INSERT INTO admin_security (admin_id, question, answer_hash) VALUES (1,?,?)")
    ->execute(['Quel est le nom de cette plateforme ?', $answerHash]);

// Check
$u = $pdo->query("SELECT email, role FROM user WHERE email='admin@filmogram.com'")->fetch();
echo "OK — Email: {$u['email']} | Role: {$u['role']}<br>";
echo "Hash verify: " . (password_verify('Admin123!', $newHash) ? 'YES' : 'NO') . "<br>";
echo '<a href="admin_login.php">→ Connexion admin</a>';
?>
