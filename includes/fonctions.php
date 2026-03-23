<?php

function getToutesLesDonnees() {
    $fichier_chemin = __DIR__ . '/../data/products.json';
    
    if (file_exists($fichier_chemin)) {
        $contenu_json = file_get_contents($fichier_chemin);
        
        return json_decode($contenu_json, true);
    } else {
        return ['plats' => [], 'menus' => []];
    }
}


function getTousLesPlats() {
    $donnees = getToutesLesDonnees();
    return $donnees['plats'];
}


function getTousLesMenus() {
    $donnees = getToutesLesDonnees();
    return $donnees['menus'];
}


function getPlatById($id_recherche) {
    $plats = getTousLesPlats();
    
    foreach ($plats as $plat) {
        if ($plat['id'] === $id_recherche) {
            return $plat;
        }
    }
    
    return null;
}


function initialiserPanier() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }
}

function ajouterAuPanier($id_produit, $quantite = 1) {
    initialiserPanier();
    
    if (isset($_SESSION['panier'][$id_produit])) {
        $_SESSION['panier'][$id_produit] += $quantite;
    } else {
        $_SESSION['panier'][$id_produit] = $quantite;
    }
}

function calculerTotalPanier() {
    initialiserPanier();
    $total = 0.0;
    
    foreach ($_SESSION['panier'] as $id_produit => $quantite) {
        $plat = getPlatById($id_produit);
        if ($plat) {
            $total += $plat['prix'] * $quantite;
        }
    }
    
    return $total;
}

function viderPanier() {
    initialiserPanier();
    $_SESSION['panier'] = [];
}
?>