<?php

// ---------------------------------------------------------
//  logout.php
//  Script TRÈS SIMPLE de déconnexion utilisateur
// ---------------------------------------------------------

// 1) On démarre la session pour pouvoir la fermer
session_start();

// 2) On supprime toutes les variables de session
//    (par exemple : $_SESSION['iduser'], $_SESSION['email'], etc.)
session_unset();

// 3) On détruit complètement la session côté serveur
session_destroy();

// 4) On redirige l'utilisateur vers la page de connexion
//    Tu peux changer "login.php" par "index.html" si tu préfères
header('Location: login.php?logged_out=1');
exit;
