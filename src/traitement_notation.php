<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include '../includes/fonctions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_commande = isset($_POST['id_commande']) ? intval($_POST['id_commande']) : 0;
    $notation = isset($_POST['notation']) ? intval($_POST['notation']) : 0;
    $commentaire = isset($_POST['commentaire']) ? trim($_POST['commentaire']) : '';

    // Validation
    if ($id_commande <= 0 || $notation < 1 || $notation > 5) {
        header("Location: historique_commandes_client.php");
        exit();
    }

    // Vérifier que la commande existe et appartient à l'utilisateur
    $commande = getCommandeById($id_commande);
    if (!$commande || $commande['user_id'] != $_SESSION['user']) {
        header("Location: historique_commandes_client.php");
        exit();
    }

    // Ajouter la notation
    if (ajouterNotationCommande($id_commande, $notation, $commentaire)) {
        header("Location: historique_commandes_client.php?success=1");
        exit();
    } else {
        header("Location: historique_commandes_client.php?error=1");
        exit();
    }
} else {
    header("Location: historique_commandes_client.php");
    exit();
}

?>
