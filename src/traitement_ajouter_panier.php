<?php
session_start();
require_once '../includes/fonctions.php';
initialiserPanier();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter') {
    if (isset($_POST['id_produit'])) {
        ajouterAuPanier($_POST['id_produit'], 1);
    }
    header('Location: carte.php');
    exit;
}
?>