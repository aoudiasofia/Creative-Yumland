<?php
session_start();
include '../includes/fonctions.php';

// Seul le restaurateur ou l'admin peut changer le statut ici
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'restaurateur' && $_SESSION['role'] !== 'admin')) {
    header("Location: connexion.php");
    exit();
}

// Vérification des paramètres requis dans l'URL
if (isset($_GET['id']) && isset($_GET['nouveau_statut'])) {
    $id_commande = $_GET['id'];
    $nouveau_statut = $_GET['nouveau_statut'];

    // Récupérer toutes les commandes 
    // Si pas de fonction de sauvegarde globale, on va directement lire le JSON des commandes.
    // REMPLACE 'commandes.json' PAR LE VRAI NOM DU FICHIER DE COMMANDES
    $chemin_commandes = __DIR__ . '/../data/commandes.json';

    if (file_exists($chemin_commandes)) {
        $commandes = json_decode(file_get_contents($chemin_commandes), true);

        // Parcourir les commandes pour trouver la bonne et modifier son statut
        foreach ($commandes as &$commande) {
            if ($commande['id'] == $id_commande) {
                $commande['statut_commande'] = $nouveau_statut;
                break;
            }
        }

        // Sauvegarder les modifications dans le fichier JSON
        file_put_contents($chemin_commandes, json_encode($commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

// Redirection instantanée vers la page de gestion des commandes
header("Location: restaurant.php");
exit();