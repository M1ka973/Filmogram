 <?php
/**
 * users_api.php — API REST pour la gestion des utilisateurs (admin)
 * Méthodes : GET | POST | PUT | PATCH | DELETE
 */
require_once '../../check_admin_session.php';
require_once '../../db_connect.php';

header('Content-Type: application/json; charset=utf-8');

// Support _method override for PATCH (certains clients HTTP)
$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {

    // ── GET : liste tous les utilisateurs ──────────────────────────────────
    case 'GET':
        $stmt = $pdo->query("SELECT iduser, prenom, nom, email, adresse, tel, role FROM user ORDER BY iduser ASC");
        echo json_encode(['success' => true, 'users' => $stmt->fetchAll()]);
        break;

    // ── POST : créer un utilisateur ─────────────────────────────────────────
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $prenom = trim($data['prenom'] ?? '');
        $nom = trim($data['nom'] ?? '');
        $email = trim($data['email'] ?? '');
        $adresse = trim($data['adresse'] ?? '');
        $tel = trim($data['tel'] ?? '');
        $mdp = $data['password'] ?? '';
        $role = in_array($data['role'] ?? 'user', ['user', 'admin']) ? $data['role'] : 'user';

        if (!$prenom || !$nom || !$email || !$mdp) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Champs obligatoires manquants.']);
            break;
        }

        // Vérifier email unique
        $chk = $pdo->prepare("SELECT iduser FROM user WHERE email = ?");
        $chk->execute([$email]);
        if ($chk->fetch()) {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => 'Cet email est déjà utilisé.']);
            break;
        }

        $hash = password_hash($mdp, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO user (prenom, nom, adresse, email, tel, password, role) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([$prenom, $nom, $adresse, $email, $tel, $hash, $role]);
        echo json_encode(['success' => true, 'message' => 'Utilisateur créé.', 'iduser' => $pdo->lastInsertId()]);
        break;

    // ── PUT : modifier un utilisateur ──────────────────────────────────────
    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $id = (int)($data['iduser'] ?? 0);
        $prenom = trim($data['prenom'] ?? '');
        $nom = trim($data['nom'] ?? '');
        $email = trim($data['email'] ?? '');
        $adresse = trim($data['adresse'] ?? '');
        $tel = trim($data['tel'] ?? '');
        $role = in_array($data['role'] ?? 'user', ['user', 'admin']) ? $data['role'] : 'user';
        $mdp = $data['password'] ?? '';

        if (!$id || !$prenom || !$nom || !$email) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Champs obligatoires manquants.']);
            break;
        }

        // Vérifier email unique (sauf pour cet utilisateur lui-même)
        $chk = $pdo->prepare("SELECT iduser FROM user WHERE email = ? AND iduser != ?");
        $chk->execute([$email, $id]);
        if ($chk->fetch()) {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => 'Cet email est déjà utilisé par un autre compte.']);
            break;
        }

        if ($mdp) {
            // Mise à jour avec nouveau mot de passe
            $hash = password_hash($mdp, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE user SET prenom=?, nom=?, adresse=?, email=?, tel=?, role=?, password=? WHERE iduser=?");
            $stmt->execute([$prenom, $nom, $adresse, $email, $tel, $role, $hash, $id]);
        }
        else {
            // Mise à jour sans changer le mot de passe
            $stmt = $pdo->prepare("UPDATE user SET prenom=?, nom=?, adresse=?, email=?, tel=?, role=? WHERE iduser=?");
            $stmt->execute([$prenom, $nom, $adresse, $email, $tel, $role, $id]);
        }
        echo json_encode(['success' => true, 'message' => 'Utilisateur mis à jour.']);
        break;

    // ── PATCH : changer uniquement le rôle (Grant / Revoke) ───────────────
    case 'PATCH':
        $data = json_decode(file_get_contents('php://input'), true);
        $id = (int)($data['iduser'] ?? 0);

        if (!$id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID invalide.']);
            break;
        }

        // Empêcher de modifier son propre rôle
        if ($id === (int)$_SESSION['admin_id']) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Vous ne pouvez pas modifier votre propre rôle.']);
            break;
        }

        // Récupérer le rôle actuel et basculer
        $cur = $pdo->prepare("SELECT role FROM user WHERE iduser = ?");
        $cur->execute([$id]);
        $row = $cur->fetch();
        if (!$row) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Utilisateur introuvable.']);
            break;
        }

        $newRole = ($row['role'] === 'admin') ? 'user' : 'admin';
        $pdo->prepare("UPDATE user SET role = ? WHERE iduser = ?")->execute([$newRole, $id]);
        $label = ($newRole === 'admin') ? 'Administrateur' : 'Utilisateur';
        echo json_encode(['success' => true, 'message' => "Rôle mis à jour : $label.", 'new_role' => $newRole]);
        break;

    // ── DELETE : supprimer un utilisateur ──────────────────────────────────
    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);
        $id = (int)($data['iduser'] ?? 0);

        if (!$id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID invalide.']);
            break;
        }

        // Empêcher la suppression du compte admin principal
        if ($id === (int)$_SESSION['admin_id']) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Impossible de supprimer votre propre compte.']);
            break;
        }

        $pdo->prepare("DELETE FROM user WHERE iduser = ?")->execute([$id]);
        echo json_encode(['success' => true, 'message' => 'Utilisateur supprimé.']);
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}
