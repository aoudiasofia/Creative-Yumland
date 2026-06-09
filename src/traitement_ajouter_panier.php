<?php
session_start();

// SÉCURITÉ : On vérifie que les données viennent bien d'un formulaire en POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: carte.php');
    exit();
}

// On crée le panier en session s'il n'existe pas encore
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// On récupère l'ID du produit et le type (plat ou menu)
$id_produit = isset($_POST['id_produit']) ? (int)$_POST['id_produit'] : 0;
// Si le formulaire n'envoie pas de type, on considère par défaut que c'est un 'plat'
$type = isset($_POST['type']) ? $_POST['type'] : 'plat';

if ($id_produit <= 0) {
    header('Location: carte.php?erreur=id_invalide');
    exit();
}

// Traitement différencié selon le type d'article
if ($type === 'menus' || $type === 'menu') {
    
    // CAS A : C'EST UN MENU CONFIGURÉ 
    // On récupère les choix (ID des plats/boissons/desserts sélectionnés)
    $choix_plat = isset($_POST['choix_plat']) ? (int)$_POST['choix_plat'] : null;
    $choix_boisson = isset($_POST['choix_boisson']) ? (int)$_POST['choix_boisson'] : null;
    $choix_dessert = isset($_POST['choix_dessert']) ? (int)$_POST['choix_dessert'] : null;

    // On génère une clé de panier unique basée sur la composition du menu
    // Exemple : "menu_17_5_14_0" (Menu 17, avec le wrap 5 et la boisson 14)
    $cle_unique = 'menu_' . $id_produit . '_' . $choix_plat . '_' . $choix_boisson . '_' . $choix_dessert;

    if (isset($_SESSION['panier'][$cle_unique])) {
        // Si ce menu exact existe déjà dans le panier, on augmente la quantité
        $_SESSION['panier'][$cle_unique]['quantite']++;
    } else {
        // Sinon, on crée la ligne dans le panier avec ses options
        $_SESSION['panier'][$cle_unique] = [
            'id' => $id_produit,
            'type' => 'menu',
            'quantite' => 1,
            'choix' => [
                'plat' => $choix_plat,
                'boisson' => $choix_boisson,
                'dessert' => $choix_dessert
            ]
        ];
    }

} else {
    
    // CAS B : C'EST UN PLAT SIMPLE 
    // Pour ne pas casser le code existant de ton équipier sur la page du panier,
    $cle_unique = (string)$id_produit;

    if (isset($_SESSION['panier'][$cle_unique])) {
        // Si c'est déjà un tableau structuré
        if (is_array($_SESSION['panier'][$cle_unique])) {
            $_SESSION['panier'][$cle_unique]['quantite']++;
        } else {
            // Si ancien code stockait juste un nombre entier (la quantité directe)
            $_SESSION['panier'][$cle_unique]++;
        }
    } else {
        // Premier ajout du plat simple dans le panier
        // On le stocke sous forme de tableau complet 
        $_SESSION['panier'][$cle_unique] = [
            'id' => $id_produit,
            'type' => 'plat',
            'quantite' => 1
        ];
    }
}

// Retour sur la carte 
header('Location: carte.php?succes=ajoute');
exit();