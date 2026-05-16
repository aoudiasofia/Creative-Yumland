<?php
// INITIALISATION ET SÉCURITÉ (SOFIA)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../includes/fonctions.php';

// Vérification immédiate : si l'utilisateur est bloqué, il est éjecté ici
verifierUtilisateurBloque(); 
?>

<header class="main-header">
    <div class="logo">
        <a href="accueil.php" style="text-decoration: none; color: inherit;">KØLD</a>
    </div>
    <nav>
        <ul>

            <?php
            // LIENS CONDITIONNELS SELON LA PAGE
            if ($nom_page === 'acceuil') {
                // Pour la page accueil : carte et profil pour tous
                echo "<li><a href='carte.php'>La carte</a></li>";
                echo "<li><a href='profil.php'>Profil</a></li>";

                // Liens spécifiques aux rôles
                if (isset($_SESSION['role'])) {
                    if ($_SESSION['role'] === 'admin') {
                        echo "<li><a href='admin.php'>[PANNEAU_ADMIN]</a></li>";
                    } elseif ($_SESSION['role'] === 'livreur') {
                        echo "<li><a href='livraison.php'>Livraison</a></li>";
                    } elseif ($_SESSION['role'] === 'restaurateur') {
                        echo "<li><a href='restaurant.php'>Restaurant</a></li>";
                    }
                }
            }

            // Pour inscription : juste connexion
            elseif ($nom_page === 'inscription') {
                echo "<li><a href='connexion.php' class='nav-login'>Connexion</a></li>";
            }

            // Pour connexion : juste inscription
            elseif ($nom_page === 'connexion') {
                echo "<li><a href='inscription.php' class='nav-login'>Inscription</a></li>";
            }

            // Pour toutes les autres pages : rien d'autre

            // Bouton panier dynamique si connecté
            if (isset($_SESSION['user']) && $nom_page === 'carte') {
                initialiserPanier();
                $total_panier = calculerTotalPanier();
                // Utilisation de $_SESSION['user'] pour l'ID
                $user_info = getInfoUser($_SESSION['user']);
                $remise = floatval($user_info['remise'] ?? 0);
                $total_final = $total_panier - $remise;
                echo "<li><a href='panier.php' class='nav-panier'>PANIER (" . number_format($total_final, 2, '.', ' ') . " €)</a></li>";
            }

            // TOUJOURS PRÉSENT : CONNEXION/DÉCONNEXION ET STATUT/ID
            if (isset($_SESSION['user'])) {
                echo "<li><a href='traitement_deconnexion.php' class='nav-login'>Déconnexion</a></li>";
                echo "<li class='user-status'>ID: " . strtoupper(htmlspecialchars($_SESSION['prenom'])) . "</li>";
            } else {
                echo "<li><a href='connexion.php' class='nav-login'>Connexion</a></li>";
                echo "<li class='user-status'>STATUT: INVITÉ</li>";
            }
            ?>
            <li class="theme-controls" style="display: flex; gap: 5px; margin-left: 10px; align-items: center;">
                <button onclick="changerTheme('kold-mode')" title="Mode KØLD" style="cursor: pointer; background: none; border: 1px solid white; color: white;">❄️</button>
                <button onclick="changerTheme('light-mode')" title="Mode Clair" style="cursor: pointer; background: none; border: 1px solid white; color: white;">☀️</button>
            </li>

        </ul>
    </nav>
</header>