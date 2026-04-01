<?php
$host = 'localhost';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sqlContent = file_get_contents(__DIR__ . '/SQL/filmogram_dtb.sql');
    
    // Split queries by semicolon
    $queries = explode(';', $sqlContent);

    foreach ($queries as $i => $query) {
        $q = trim($query);
        if (!empty($q)) {
            try {
                $pdo->exec($q);
            } catch (PDOException $e) {
                die("ERREUR SUR LA REQUETE " . ($i+1) . " : \n" . $q . "\n\nMESSAGE: " . $e->getMessage());
            }
        }
    }

    echo "SUCCES: La base de données a été réinitialisée et mise à jour avec succès.";
    unlink(__FILE__);

} catch (PDOException $e) {
    die("ERREUR DE CONNEXION: " . $e->getMessage());
} catch (Exception $e) {
    die("ERREUR: " . $e->getMessage());
}
?>
