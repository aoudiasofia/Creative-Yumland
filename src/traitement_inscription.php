<?php
// 1. Définir les chemins
$dossier_data = __DIR__ . '/../data';
$fichier_final = $dossier_data . '/user.json';

// 2. CRÉATION AUTOMATIQUE DU DOSSIER (si absent)
if (!is_dir($dossier_data)) {
    mkdir($dossier_data, 0777, true); 
    // Crée le dossier 'data' avec les droits d'écriture
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $new_user = [
        "id"       => time(),
        "login"    => $_POST['nom'] . "_" . $_POST['prenom'],
        "nom"      => strtoupper($_POST['nom']),
        "prenom"   => $_POST['prenom'],
        "email"    => $_POST['email'],
        "password" => $_POST['password'],
        "tel"      => $_POST['telephone'],
        "role"     => "client"
    ];

    $users_array = [];

    // 3. LECTURE SÉCURISÉE
    if (file_exists($fichier_final)) {
        $json_contenu = file_get_contents($fichier_final);
        $users_array = json_decode($json_contenu, true);
        if (!is_array($users_array)) $users_array = [];
    }

    // 4. AJOUT
    $users_array[] = $new_user;

    // 5. SAUVEGARDE (Crée le fichier s'il n'existe pas)
    if (file_put_contents($fichier_final, json_encode($users_array, JSON_PRETTY_PRINT))) {
        header("Location: login.php?signup_success=1");
        exit();
    } else {
        echo "ERREUR_FATALE : Impossible de générer le fichier de base.";
    }
}