<?php
session_start();
require_once '../includes/fonctions.php';
verifierUtilisateurBloque();

require_once '../includes/fonctions.php';

if (isset($_POST['email']) && isset($_POST['password'])) {
    
    $email_saisi = $_POST['email'];
    $mdp_saisi = $_POST['password'];

    $id_trouve = verifierConnexion($email_saisi, $mdp_saisi);

    if ($id_trouve) {
        
        $user = getInfoUser($id_trouve);
        if($user['bloqué'] === true){
            header("Location: connexion.php?error=utilisateur_bloqué");
            exit();
        }


        $_SESSION['user'] = $user['id']; // On stocke l'ID (ex: 2, 5, 12) et pas juste "true"
        
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['id'] = $user['id'];
        $_SESSION['tel'] = $user['tel'];
        $_SESSION['adresse'] = $user['adresse'];
        $_SESSION['infosup'] = $user['infosup'];
        $_SESSION['statut'] = $user['statut'];
        $_SESSION['remise'] = $user['remise'];
        $_SESSION['bloqué'] = $user['bloqué'];

        header("Location: accueil.php");
        exit();
        
    } else {
        header("Location: connexion.php?error=1");
        exit();
    }

} else {
// Si quelqu'un essaie d'accéder à ce fichier directement sans le formulaire
header("Location: connexion.php");
exit();
}
?>