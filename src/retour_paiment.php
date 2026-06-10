<?php
session_start();
require_once '../includes/fonctions.php';
require_once '../includes/getapikey.php';

$vendeur = "MEF-2_A"; 
$api_key = getAPIKey($vendeur);

// 1. Récupération des données envoyées par CY Bank via l'URL (GET)
$transaction  = $_GET['transaction'] ?? '';
$montant      = $_GET['montant'] ?? '';
$vendeur_recu = $_GET['vendeur'] ?? '';
$status       = $_GET['status'] ?? ''; // 'accepted' ou 'declined'
$control_recu = $_GET['control'] ?? '';

// 2. Vérification de sécurité (Recalcul du MD5 avec le statut renvoyé)
$chaine_controle = $api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur_recu . "#" . $status . "#";
$control_attendu = md5($chaine_controle);

if ($control_recu === $control_attendu && $vendeur_recu === $vendeur) {
    // La sécurité est validée ! On retrouve l'ID de la commande d'origine
    $id_commande = intval(str_replace('CMD', '', $transaction));
    
    // 3. Mise à jour manuelle du JSON des commandes
    $commandes = getToutesLesCommandes();
    $trouve = false;

    foreach ($commandes as &$c) {
        if ($c['id'] === $id_commande) {
            if ($status === 'accepted') {
                $c['statut_paiement'] = 'payé';
                $c['statut_commande'] = 'en attente'; // Prêt à être préparé
            } else {
                $c['statut_paiement'] = 'refusé';
                $c['statut_commande'] = 'abandonnée'; // On annule la commande
            }
            $trouve = true;
            break;
        }
    }

    if ($trouve) {
        enregistrerToutesLesCommandes($commandes);
    }

    // 4. Redirection de l'utilisateur vers son historique
    header('Location: historique_commandes_client.php');
    exit;

} else {
    // Si les données ont été altérées
    die("Erreur de sécurité : transaction invalide.");
}
?>