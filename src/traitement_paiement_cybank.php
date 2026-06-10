<?php
session_start();
require_once '../includes/fonctions.php';
require_once '../includes/getapikey.php';

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

$id_commande = isset($_GET['id_commande']) ? intval($_GET['id_commande']) : 0;
if ($id_commande <= 0) {
    header('Location: historique_commandes_client.php');
    exit;
}

// Récupération de la commande mise à jour avec la réduction éventuelle
$commandes = getToutesLesCommandes();
$commande_trouvee = null;
foreach ($commandes as $c) {
    if ($c['id'] == $id_commande && $c['user_id'] == $_SESSION['user']) {
        $commande_trouvee = $c;
        break;
    }
}

if (!$commande_trouvee) {
    header('Location: historique_commandes_client.php');
    exit;
}

// On récupère le montant final à payer
$montant = 0;
if (isset($commande_trouvee['montant_payé'])) {
    $montant = floatval($commande_trouvee['montant_payé']);
} elseif (isset($commande_trouvee['total'])) {
    $montant = floatval($commande_trouvee['total']);
} elseif (isset($commande_trouvee['prix_total'])) {
    $montant = floatval($commande_trouvee['prix_total']);
}

$vendeur = "MEF-2_A"; 
$api_key = getAPIKey($vendeur);

$transaction = "CMD" . str_pad($id_commande, 7, "0", STR_PAD_LEFT); 
$montant_format = number_format($montant, 2, '.', '');
$retour = "http://localhost/CREATIVE-YUMLAND/src/retour_paiment.php"; 

$control = md5($api_key . "#" . $transaction . "#" . $montant_format . "#" . $vendeur . "#" . $retour . "#");

echo "<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <title>Redirection vers le paiement</title>
</head>
<body style='background-color: #000; color: #fff; text-align: center; padding-top: 50px; font-family: sans-serif;'>
    <h2>Redirection vers la plateforme de paiement sécurisée CY Bank...</h2>
    <p>Veuillez patienter.</p>
    <form id='cybank_form' action='https://www.plateforme-smc.fr/cybank/index.php' method='POST'>
        <input type='hidden' name='transaction' value='$transaction'>
        <input type='hidden' name='montant' value='$montant_format'>
        <input type='hidden' name='vendeur' value='$vendeur'>
        <input type='hidden' name='retour' value='$retour'>
        <input type='hidden' name='control' value='$control'>
    </form>
    <script>
        setTimeout(function() {
            document.getElementById('cybank_form').submit();
        }, 1500);
    </script>
</body>
</html>";
?>