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

    if ($paiement === 'cy_bank') {
        require_once '../includes/getapikey.php';
        
        $vendeur = "MEF-2_A"; 
        $api_key = getAPIKey($vendeur);
        
        // L'identifiant doit faire entre 10 et 24 caractères alphanumériques
        $transaction = "CMD" . str_pad($id_commande, 7, "0", STR_PAD_LEFT); 
        $montant = number_format($total_apres_remise, 2, '.', '');
        
        $retour = "http://localhost/CREATIVE-YUMLAND/src/retour_paiement.php"; 

        // Calcul du hash de sécurité
        $control = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $retour . "#");

        // On génère un formulaire HTML invisible qui se soumet tout seul vers CY Bank
        echo "<form id='cybank_form' action='https://www.plateforme-smc.fr/cybank/index.php' method='POST'>
                <input type='hidden' name='transaction' value='$transaction'>
                <input type='hidden' name='montant' value='$montant'>
                <input type='hidden' name='vendeur' value='$vendeur'>
                <input type='hidden' name='retour' value='$retour'>
                <input type='hidden' name='control' value='$control'>
              </form>
              <script>document.getElementById('cybank_form').submit();</script>";
        exit;
    } else {
        // Si le client a choisi "Payer plus tard"
        header('Location: historique_commandes_client.php?success=1&id=' . $id_commande);
        exit;
    }
} else {
    header('Location: panier.php?error=creation');
    exit;
}
?>