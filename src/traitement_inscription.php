<?php
include '../includes/fonctions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $password = $_POST['password'] ?? '';
    $adresse = trim($_POST['adresse'] ?? '');
    $infos = trim($_POST['infos'] ?? '');
    
    if (empty($nom) || empty($prenom) || empty($email) || empty($telephone) || empty($password) || empty($adresse)) {
        header("Location: inscription.php?error=champs_vides");
        exit();
    }
    
    if (existeDeja($email)) {
        header("Location: inscription.php?error=email_existe");
        exit();
    }
    
    if (creerNouvelUtilisateur($nom, $prenom, $password, $email, $telephone, $adresse, $infos)) {
        header("Location: connexion.php?signup_success=1");
        exit();
    } else {
        header("Location: inscription.php?error=erreur_creation");
        exit();
    }
    
} else {
    header("Location: inscription.php");
    exit();
}
?>