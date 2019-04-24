<?php
session_start();
// Détruit toutes les variables de session
$_SESSION = array();
setcookie('myLibrary', 'deconnecte', time() - 42000);
session_destroy();
header('Location: index.php');