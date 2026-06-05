<?php
/**
 * upload_poster_api.php — Upload d'un poster film vers Google Drive
 * Implémente le skill google-drive-upload (https://github.com/msmobileapps/google-drive-upload-plugin)
 *
 * Prérequis : créer %USERPROFILE%\.cowork-gdrive-config.json avec :
 * {
 *   "scriptUrl": "https://script.google.com/macros/s/XXX/exec",
 *   "apiKey": "votre-cle-api",
 *   "folderPath": "Filmogram/Posters"  (optionnel)
 * }
 */
require_once '../check_admin_session.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

// ── 1. Lire la config Google Drive ──────────────────────────────────────────
$userProfile = getenv('USERPROFILE') ?: getenv('HOME');
$configPath  = $userProfile . DIRECTORY_SEPARATOR . '.cowork-gdrive-config.json';

if (!file_exists($configPath)) {
    echo json_encode([
        'success' => false,
        'message' => 'Config Google Drive introuvable. Créez le fichier : ' . $configPath,
        'help'    => 'Voir : https://github.com/msmobileapps/google-drive-upload-plugin',
    ]);
    exit;
}

$config = json_decode(file_get_contents($configPath), true);
if (!isset($config['scriptUrl'], $config['apiKey'])) {
    echo json_encode(['success' => false, 'message' => 'Config invalide : les clés "scriptUrl" et "apiKey" sont requises.']);
    exit;
}

// ── 2. Valider le fichier reçu ──────────────────────────────────────────────
if (!isset($_FILES['poster']) || $_FILES['poster']['error'] !== UPLOAD_ERR_OK) {
    $codes = [1=>'trop grand (ini)',2=>'trop grand (form)',3=>'partiel',4=>'absent',6=>'tmp manquant',7=>'écriture impossible'];
    $errCode = $_FILES['poster']['error'] ?? -1;
    echo json_encode(['success' => false, 'message' => 'Erreur upload : ' . ($codes[$errCode] ?? 'inconnue (' . $errCode . ')')]);
    exit;
}

$file    = $_FILES['poster'];
$allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
$maxSize = 50 * 1024 * 1024; // 50 Mo — limite Google Apps Script

if ($file['size'] > $maxSize) {
    echo json_encode(['success' => false, 'message' => 'Fichier trop volumineux (max 50 Mo).']);
    exit;
}

// Vérification du type MIME réel (pas seulement l'extension)
$finfo    = finfo_open(FILEINFO_MIME_TYPE);
$realMime = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!in_array($realMime, $allowed)) {
    echo json_encode(['success' => false, 'message' => 'Type de fichier non autorisé (' . $realMime . '). JPG, PNG, WebP, GIF uniquement.']);
    exit;
}

// ── 3. Encoder en base64 ────────────────────────────────────────────────────
$fileContent = file_get_contents($file['tmp_name']);
$b64         = base64_encode($fileContent);
$fileName    = preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($file['name']));
$folderPath  = $config['folderPath'] ?? 'Filmogram/Posters';

// ── 4. Envoyer vers Google Apps Script ─────────────────────────────────────
$payload = json_encode([
    'fileName'        => $fileName,
    'content'         => $b64,
    'mimeType'        => $realMime,
    'apiKey'          => $config['apiKey'],
    'folderPath'      => $folderPath,
    'replaceExisting' => false,
]);

if (!function_exists('curl_init')) {
    echo json_encode(['success' => false, 'message' => 'cURL non disponible sur ce serveur PHP.']);
    exit;
}

$ch = curl_init($config['scriptUrl']);
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
    CURLOPT_TIMEOUT        => 60,
    CURLOPT_SSL_VERIFYPEER => true,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlErr  = curl_error($ch);
curl_close($ch);

if ($curlErr) {
    echo json_encode(['success' => false, 'message' => 'Erreur réseau : ' . $curlErr]);
    exit;
}

if ($httpCode < 200 || $httpCode >= 300) {
    echo json_encode(['success' => false, 'message' => "Le serveur Drive a répondu HTTP $httpCode.", 'raw' => $response]);
    exit;
}

$result = json_decode($response, true);

// L'Apps Script peut retourner {"url":...} ou {"fileUrl":...} ou {"webViewLink":...}
$driveUrl = $result['url'] ?? $result['fileUrl'] ?? $result['webViewLink'] ?? null;

if (!$driveUrl) {
    echo json_encode(['success' => false, 'message' => 'Réponse inattendue du serveur Google Drive.', 'raw' => $response]);
    exit;
}

// Convertir le lien webView en lien direct d'image si possible
// https://drive.google.com/file/d/FILE_ID/view  →  https://drive.google.com/uc?export=view&id=FILE_ID
if (preg_match('/\/file\/d\/([a-zA-Z0-9_-]+)/', $driveUrl, $m)) {
    $directUrl = 'https://drive.google.com/uc?export=view&id=' . $m[1];
} else {
    $directUrl = $driveUrl;
}

echo json_encode([
    'success'    => true,
    'url'        => $directUrl,
    'driveUrl'   => $driveUrl,
    'message'    => 'Poster uploadé sur Google Drive avec succès !',
]);
