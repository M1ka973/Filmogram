<?php

//  logout.php

//démarre la session pour pouvoir la fermer
session_start();

//supprime toutes les variables de session
session_unset();

//détruit complètement la session côté serveur
session_destroy();

//Redirige l'utilisateur vers la page de connexion

header('Location: index.html?logged_out=1');
exit;
