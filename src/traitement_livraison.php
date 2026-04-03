<?php
session_start();
include '../includes/fonctions.php';

// Autorisé : livreur, restaurateur ou admin
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['livreur', 'restaurateur', 'admin'])) {
    header('Location: connexion.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: livraison.php');
    exit();
}

$id_commande = isset($_POST['id_commande']) ? intval($_POST['id_commande']) : 0;
$commande = getCommandeById($id_commande);

if (!$commande) {
    header('Location: ' . ($_SESSION['role'] === 'livreur' ? 'livraison.php' : 'restaurant.php'));
    exit();
}

// Pour un livreur, vérifier qu'il est bien le livreur de la commande
if ($_SESSION['role'] === 'livreur') {
    $livreur_id = isset($_SESSION['id']) ? intval($_SESSION['id']) : 0;
    if (!isset($commande['livreur']) || intval($commande['livreur']) !== $livreur_id) {
        header('Location: livraison.php');
        exit();
    }
}

$redirect = ($_SESSION['role'] === 'livreur') ? 'livraison.php' : 'restaurant.php';

$ok = false;

// Mise à jour du statut de commande
if (isset($_POST['nouveau_statut']) && strlen(trim($_POST['nouveau_statut'])) > 0) {
    $nouveau_statut = trim($_POST['nouveau_statut']);

    // Pour livreur, on n'autorise que terminée ou abandonnée
    if ($_SESSION['role'] === 'livreur') {
        if (!in_array($nouveau_statut, ['terminée', 'abandonnée'])) {
            header('Location: ' . $redirect . '?erreur=statut');
            exit();
        }
    }

    if (mettreAJourStatutCommande($id_commande, $nouveau_statut)) {
        $ok = true;
    }
}

// Attribution d'un livreur (restaurateur/admin)
if (isset($_POST['id_livreur']) && in_array($_SESSION['role'], ['restaurateur', 'admin'])) {
    $id_livreur = intval($_POST['id_livreur']);

    // Si valeur vide, on retire l'attribution
    if ($id_livreur === 0) {
        if (attribuerLivreurCommande($id_commande, null)) {
            $ok = true;
        }
    } else {
        if (attribuerLivreurCommande($id_commande, $id_livreur)) {
            $ok = true;
        }
    }
}

if ($ok) {
    header('Location: ' . $redirect . '?success=1');
} else {
    header('Location: ' . $redirect . '?success=0');
}
exit();
