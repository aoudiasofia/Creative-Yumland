<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: panier.php');
    exit();
}

// la clé unique envoyée par le formulaire
$cle_panier = isset($_POST['cle_panier']) ? $_POST['cle_panier'] : '';

// Si la clé existe bien dans le panier, on la supprime
if (!empty($cle_panier) && isset($_SESSION['panier'][$cle_panier])) {
    
    // baisser la quantité de 1 à chaque clic
    $_SESSION['panier'][$cle_panier]['quantite']--;
    
    // Si la quantité tombe à 0 ou moins, on retire complètement la ligne
    if ($_SESSION['panier'][$cle_panier]['quantite'] <= 0) {
        unset($_SESSION['panier'][$cle_panier]);
    }
    
    
}

// Redirection vers la page du panier mise à jour
header('Location: panier.php?succes=supprime');
exit();