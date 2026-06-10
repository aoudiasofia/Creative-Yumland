<?php
session_start();
require_once '../includes/fonctions.php';
initialiserPanier();

if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');
    exit;
}

$user_id = $_SESSION['id'];

if (empty($_SESSION['panier'])) {
    header('Location: panier.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: panier.php');
    exit;
}

$quand = $_POST['quand'] ?? 'maintenant';
$adresse_livraison = trim($_POST['adresse_livraison'] ?? '');
$paiement = $_POST['paiement'] ?? '';

if (empty($adresse_livraison)) {
    header('Location: panier.php?error=adresse');
    exit;
}

if (!in_array($paiement, ['cy_bank', 'plus_tard'])) {
    header('Location: panier.php?error=paiement');
    exit;
}

$articles = [];
foreach ($_SESSION['panier'] as $id_produit => $quantite) {
    $articles[] = [
        'type' => 'plats',
        'id_produit' => $id_produit,
        'quantite' => $quantite
    ];
}

$total = calculerTotalPanier();

$user_info = getInfoUser($user_id);
$remise = floatval($user_info['remise'] ?? 0);
$total_apres_remise = $total - $remise;

// La commande est toujours créée "en attente" au début
$id_commande = creerNouvelleCommande($user_id, $articles, $quand, $adresse_livraison, 'en attente', $total_apres_remise, $remise);

if ($id_commande) {
    viderPanier();

    // Redirection vers la roulette pour TOUS les paiements (on joue avant de payer)
    header('Location: roulette.php?id_commande=' . $id_commande . '&paiement=' . urlencode($paiement));
    exit;
} else {
    header('Location: panier.php?error=creation');
    exit;
}
?>