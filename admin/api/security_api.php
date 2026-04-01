<?php
/**
 * security_api.php — API REST pour la gestion de la double authentification admin
 */
require_once '../../check_admin_session.php';
require_once '../../db_connect.php';

header('Content-Type: application/json; charset=utf-8');

$method  = $_SERVER['REQUEST_METHOD'];
$adminId = (int)$_SESSION['admin_id'];

switch ($method) {

    // ── GET : retourne la question actuelle ────────────────────────────────
    case 'GET':
        $stmt = $pdo->prepare("SELECT question FROM admin_security WHERE admin_id = ?");
        $stmt->execute([$adminId]);
        $sec = $stmt->fetch();
        echo json_encode(['success' => true, 'question' => $sec ? $sec['question'] : '']);
        break;

    // ── PUT : changer question + réponse ────────────────────────────────────
    case 'PUT':
        $data     = json_decode(file_get_contents('php://input'), true);
        $question = trim($data['question'] ?? '');
        $answer   = trim(strtolower($data['answer'] ?? ''));

        if (!$question || !$answer) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'La question et la réponse sont obligatoires.']);
            break;
        }

        $hash = password_hash($answer, PASSWORD_DEFAULT);

        // Vérifier si une entrée existe
        $chk = $pdo->prepare("SELECT id FROM admin_security WHERE admin_id = ?");
        $chk->execute([$adminId]);

        if ($chk->fetch()) {
            $stmt = $pdo->prepare("UPDATE admin_security SET question=?, answer_hash=? WHERE admin_id=?");
            $stmt->execute([$question, $hash, $adminId]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO admin_security (admin_id, question, answer_hash) VALUES (?,?,?)");
            $stmt->execute([$adminId, $question, $hash]);
        }

        echo json_encode(['success' => true, 'message' => 'Question secrète mise à jour.']);
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}
