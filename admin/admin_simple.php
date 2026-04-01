<?php
/**
 * admin_simple.php — Dashboard très simple pour admin@filmogram.test
 */
require_once 'check_admin_session.php';
require_once '../db_connect.php';

// Vérification de sécurité (optionnelle mais recommandée)
$stmt = $pdo->prepare("SELECT email FROM user WHERE iduser = ?");
$stmt->execute([$_SESSION['admin_id']]);
$admin = $stmt->fetch();

if (!$admin || $admin['email'] !== 'admin@filmogram.test') {
    // Si l'utilisateur n'est pas le testeur, on le redirige vers le vrai dashboard
    header('Location: admin.php');
    exit();
}

// ── TRAITEMENT DES ACTIONS (Suppression) ──
$message = '';
if (isset($_GET['delete_film'])) {
    $id = (int)$_GET['delete_film'];
    $pdo->prepare("DELETE FROM film WHERE idfilm = ?")->execute([$id]);
    $message = "Film supprimé avec succès.";
}
if (isset($_GET['delete_user'])) {
    $id = (int)$_GET['delete_user'];
    // Empêcher la suppression de soi-même ou du super-admin
    if ($id !== 1 && $id !== $_SESSION['admin_id']) {
        $pdo->prepare("DELETE FROM user WHERE iduser = ?")->execute([$id]);
        $message = "Utilisateur supprimé avec succès.";
    } else {
        $message = "❌ Impossible de supprimer cet administrateur.";
    }
}

// ── RÉCUPÉRATION DES DONNÉES ──
$films = $pdo->query("SELECT idfilm, nom, genre, prix FROM film ORDER BY idfilm DESC LIMIT 50")->fetchAll();
$users = $pdo->query("SELECT iduser, prenom, nom, email, role FROM user ORDER BY iduser DESC LIMIT 50")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Menu Simple — FILMOGRAM</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #111;
            color: #eee;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: #1e1e2e;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.5);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #e94560;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        h1, h2 { color: #e94560; margin: 0; }
        .btn-logout {
            background: #444; color: white; text-decoration: none;
            padding: 8px 15px; border-radius: 5px; font-size: 14px;
        }
        .btn-logout:hover { background: #666; }
        
        .msg {
            background: rgba(46, 204, 113, 0.2);
            color: #2ecc71;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background: #2a2a3e;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #333;
        }
        th { background: #3a3a5e; color: #fff; }
        tr:hover { background: #33334e; }
        
        .btn-del {
            background: #e74c3c; color: white; text-decoration: none;
            padding: 5px 10px; border-radius: 3px; font-size: 12px;
        }
        .btn-del:hover { background: #c0392b; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>🎬 Tableau de bord Simple</h1>
        <a href="admin_logout.php" class="btn-logout">Déconnexion</a>
    </div>

    <?php if ($message): ?>
        <div class="msg"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <h2>Liste des Films</h2>
    <table>
        <tr><th>ID</th><th>Titre</th><th>Genre</th><th>Prix</th><th>Action</th></tr>
        <?php foreach ($films as $f): ?>
        <tr>
            <td><?= $f['idfilm'] ?></td>
            <td><?= htmlspecialchars($f['nom']) ?></td>
            <td><?= htmlspecialchars($f['genre']) ?></td>
            <td><?= $f['prix'] ?> €</td>
            <td>
                <a href="?delete_film=<?= $f['idfilm'] ?>" class="btn-del" onclick="return confirm('Supprimer ce film ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>Liste des Utilisateurs</h2>
    <table>
        <tr><th>ID</th><th>Nom complet</th><th>Email</th><th>Rôle</th><th>Action</th></tr>
        <?php foreach ($users as $u): ?>
        <tr>
            <td><?= $u['iduser'] ?></td>
            <td><?= htmlspecialchars($u['prenom'] . ' ' . $u['nom']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= $u['role'] ?></td>
            <td>
                <?php if($u['iduser'] != 1 && $u['iduser'] != $_SESSION['admin_id']): ?>
                    <a href="?delete_user=<?= $u['iduser'] ?>" class="btn-del" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</a>
                <?php else: ?>
                    <span style="color:#888;font-size:12px;">Protégé</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>
