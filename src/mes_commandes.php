<?php
session_start();

// 1. SÉCURITÉ : Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

$file = '../data/commandes.json';

// 2. VÉRIFICATION DES DONNÉES REÇUES
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    
    $order_id = $_POST['order_id'];
    $new_status = $_POST['new_status']; // Récupère le statut envoyé par le bouton
    
    if (file_exists($file)) {
        $data = json_decode(file_get_contents($file), true);
        
        if (is_array($data)) {
            foreach ($data as &$order) {
                if ($order['id'] === $order_id) {
                    
                    // MISE À JOUR DU STATUT
                    $order['status'] = $new_status;

                    // SI ATTRIBUTION D'UN LIVREUR (Phase 2 Camille)
                    if (isset($_POST['livreur_id']) && !empty($_POST['livreur_id'])) {
                        $order['livreur'] = $_POST['livreur_id'];
                    }

                    // OPTIONNEL : Ajout d'une date de mise à jour
                    $order['last_update'] = date('d/m/Y H:i');
                    break;
                }
            }
            
            // 3. SAUVEGARDE DANS LE JSON
            file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
        }
    }

    // 4. REDIRECTION INTELLIGENTE SELON LE RÔLE
    // On renvoie l'utilisateur là d'où il vient
    if ($_SESSION['role'] === 'restaurateur') {
        header("Location: restaurant.php?update=success");
    } elseif ($_SESSION['role'] === 'livreur') {
        header("Location: livreur.php?update=success");
    } elseif ($_SESSION['role'] === 'admin') {
        header("Location: admin.php?update=success");
    } else {
        header("Location: mes_commandes.php");
    }
    exit();

} else {
    // Si on tente d'accéder au fichier sans formulaire
    header("Location: accueil.php");
    exit();
}