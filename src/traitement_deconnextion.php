<?php
session_start();
session_destroy(); // On détruit la session
header("Location: accueil.php"); // On repart à zéro
exit();
?>