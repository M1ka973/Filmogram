<?php
// setup_db_2.php
$host = 'localhost';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sqlContent = file_get_contents(__DIR__ . '/SQL/filmogram_dtb.sql');
    
    // Split queries by semicolon properly without splitting inside strings
    // A simple explode by ';' might break if there are semicolons in data.
    // Instead we can use $pdo->exec($sqlContent) inside a try-catch for the whole block,
    // or just let it crash. Wait, let's just use regular $pdo->exec and output error:
    $pdo->exec($sqlContent);

    echo "SUCCES: La base de données a été réinitialisée et mise à jour avec succès.";
    unlink(__FILE__);

} catch (PDOException $e) {
    die("CRASH SUR PDO.EXEC : " . $e->getMessage());
} catch (Exception $e) {
    die("ERREUR: " . $e->getMessage());
}
?>
