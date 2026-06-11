<?php
session_start();
include '../includes/fonctions.php';

// Sécurité : Seul le restaurateur ou l'admin peut exécuter ce traitement
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'restaurateur' && $_SESSION['role'] !== 'admin')) {
    header('Location: connexion.php');
    exit();
}


$chemin_menu = __DIR__ . '/../data/produits.json';

// Vérification de sécurité pour être sûr que le fichier existe avant d'essayer de le lire
if (!file_exists($chemin_menu)) {
    die("Erreur critique de chemin : Le fichier JSON est introuvable à cet emplacement : " . htmlspecialchars($chemin_menu));
}

$data = json_decode(file_get_contents($chemin_menu), true);

if (!isset($data['plats']) || !is_array($data['plats'])) {
    $data['plats'] = [];
}

// ==========================================
// 1. ACTION : AJOUTER UN PLAT
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter') {
    $nom = htmlspecialchars($_POST['nom']);
    $description = htmlspecialchars($_POST['description']);
    $prix = floatval($_POST['prix']);
    $categorie = htmlspecialchars($_POST['categorie']);
    $regime = htmlspecialchars($_POST['regime']);

    // Trouver l'ID le plus élevé pour incrémenter de 1
    $nouvel_id = 1;
    if (count($data['plats']) > 0) {
        $nouvel_id = max(array_column($data['plats'], 'id')) + 1;
    }

    // --- GESTION DE L'IMAGE COMPLÈTE ---
    $chemin_image_bdd = "../images/default.png"; // Image par défaut si rien n'est envoyé

    if (isset($_FILES['image_plat']) && $_FILES['image_plat']['error'] === 0) {
        $dossier_destination = __DIR__ . '/../images/';
        
        $nom_fichier = $_FILES['image_plat']['name'];
        $extension = strtolower(pathinfo($nom_fichier, PATHINFO_EXTENSION));
        $extensions_autorisees = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($extension, $extensions_autorisees)) {
            // On renomme le fichier de manière unique
            $nouveau_nom_image = 'produit_' . $nouvel_id . '.' . $extension;
            $chemin_complet_stockage = $dossier_destination . $nouveau_nom_image;

            if (move_uploaded_file($_FILES['image_plat']['tmp_name'], $chemin_complet_stockage)) {
                $chemin_image_bdd = "../images/" . $nouveau_nom_image;
            }
        }
    }

    // Structure exacte insérée dans ton JSON
    $nouveau_plat = [
        "id" => $nouvel_id,
        "nom" => $nom,
        "description" => $description,
        "prix" => $prix,
        "categorie" => $categorie,
        "regime" => $regime,
        "commandes" => 0, 
        "image" => $chemin_image_bdd
    ];

    $data['plats'][] = $nouveau_plat;
    
    // Sauvegarde dans le JSON
    file_put_contents($chemin_menu, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    header('Location: restaurant_carte.php?statut=ajoute');
    exit();
}

// ==========================================
// 2. ACTION : SUPPRIMER UN PLAT (RÉINTRODUITE ET FIABILISÉE)
// ==========================================
if (isset($_GET['action']) && $_GET['action'] === 'supprimer' && isset($_GET['id'])) {
    $id_a_supprimer = (int)$_GET['id'];
    
    $nouveaux_plats = [];
    
    // On passe en revue les plats et on exclut celui qui doit sauter
    foreach ($data['plats'] as $plat) {
        if ((int)$plat['id'] !== $id_a_supprimer) {
            $nouveaux_plats[] = $plat;
        }
    }

    // On écrase l'ancienne liste par la nouvelle liste filtrée
    $data['plats'] = $nouveaux_plats;

    // Enregistrement forcé dans ton fichier JSON
    $sauvegarde = file_put_contents($chemin_menu, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    if ($sauvegarde === false) {
        die("Erreur : Impossible d'écrire ou de modifier le fichier JSON. Vérifie ses droits d'écriture.");
    }
    
    header('Location: restaurant_carte.php?statut=supprime');
    exit();
}

// Si aucune action ne correspond, retour sécurité vers l'espace carte
header('Location: restaurant_carte.php');
exit();