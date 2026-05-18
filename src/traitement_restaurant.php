<?php
session_start();
include '../includes/fonctions.php';

// Sécurité : Seul le restaurateur ou l'admin peut exécuter ce traitement
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'restaurateur' && $_SESSION['role'] !== 'admin')) {
    header('Location: connexion.php');
    exit();
}

$chemin_menu = __DIR__ . '/../data/produits.json';
$data = json_decode(file_get_contents($chemin_menu), true);

if (!isset($data['plats'])) {
    $data['plats'] = [];
}

// 1. ACTION : AJOUTER UN PLAT
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
        
        // Sécurité : Vérifier l'extension du fichier
        $nom_fichier = $_FILES['image_plat']['name'];
        $extension = strtolower(pathinfo($nom_fichier, PATHINFO_EXTENSION));
        $extensions_autorisees = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($extension, $extensions_autorisees)) {
            // On renomme le fichier de manière unique pour éviter les doublons ou bugs d'accents
            $nouveau_nom_image = 'produit_' . $nouvel_id . '.' . $extension;
            $chemin_complet_stockage = $dossier_destination . $nouveau_nom_image;

            // Déplacement du fichier temporaire vers ton vrai dossier images
            if (move_uploaded_file($_FILES['image_plat']['tmp_name'], $chemin_complet_stockage)) {
                $chemin_image_bdd = "../images/" . $nouveau_nom_image;
            }
        }
    }
    // ------------------------------------

    // Structure exacte insérée dans ton JSON
    $nouveau_plat = [
        "id" => $nouvel_id,
        "nom" => $nom,
        "description" => $description,
        "prix" => $prix,
        "categorie" => $categorie,
        "regime" => $regime,
        "commandes" => 0, 
        "image" => $chemin_image_bdd // Utilise le chemin généré dynamiquement !
    ];

    $data['plats'][] = $nouveau_plat;
    
    // Sauvegarde dans le JSON
    file_put_contents($chemin_menu, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    header('Location: restaurant_carte.php?statut=ajoute');
    exit();
}

// Si aucune action ne correspond, retour sécurité
header('Location: restaurant.php');
exit();