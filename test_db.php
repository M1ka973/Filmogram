<?php
$pdo = new PDO("mysql:host=localhost;dbname=filmogram_dtb;charset=utf8", "root", "");
$stmt = $pdo->query("SELECT email, role FROM user LIMIT 5");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
