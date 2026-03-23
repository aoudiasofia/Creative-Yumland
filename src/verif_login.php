<?php
// 1. On démarre la session pour pouvoir créer le "badge" de l'utilisateur
session_start();

// 2. On définit le chemin BLINDÉ vers ton fichier de données
// __DIR__ est le dossier 'src', donc on remonte d'un cran pour aller dans 'data'
$chemin_json = __DIR__ . '/../data/user.json'; 

// 3. On vérifie si les données arrivent bien du formulaire login.php
if (isset($_POST['login']) && isset($_POST['password'])) {
    
    $login_saisi = $_POST['login'];
    $mdp_saisi = $_POST['password'];

    // 4. On vérifie si le fichier user.json existe bien dans le dossier data
    if (file_exists($chemin_json)) {
        // On lit le fichier
        $contenu_json = file_get_contents($chemin_json);
        $users = json_decode($contenu_json, true);

        $utilisateur_trouve = null;

        // 5. On cherche si un utilisateur dans le JSON correspond à ce qui a été saisi
        if (is_array($users)) {
            foreach ($users as $u) {
                // Vérification du Login ET du Mot de passe
                if ($u['login'] === $login_saisi && $u['password'] === $mdp_saisi) {
                    $utilisateur_trouve = $u;
                    break;
                }
            }
        }

        // 6. Verdict final
        if ($utilisateur_trouve) {
            // SUCCÈS : On remplit la session avec les infos de l'utilisateur
            $_SESSION['user'] = $utilisateur_trouve['login'];
            $_SESSION['role'] = $utilisateur_trouve['role'];
            $_SESSION['id']   = $utilisateur_trouve['id'];

            // On redirige vers l'accueil
            header("Location: accueil.php");
            exit();
        } else {
            // ÉCHEC : Mauvais identifiants -> on renvoie au login avec une erreur
            header("Location: login.php?error=1");
            exit();
        }
    } else {
        // Si le fichier n'est pas trouvé, on affiche un message d'aide pour toi
        die("ERREUR_SYSTEME : Le fichier " . realpath(__DIR__ . '/../') . "/data/user.json est introuvable. Vérifie le dossier 'data'.");
    }
} else {
    // Si quelqu'un essaie d'accéder à ce fichier directement sans le formulaire
    header("Location: login.php");
    exit();
}