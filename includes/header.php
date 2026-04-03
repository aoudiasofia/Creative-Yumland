<header class="main-header">
    <div class="logo">
        <a href="accueil.php" style="text-decoration: none; color: inherit;">KØLD</a>
    </div>
    <nav>
        <ul>

            <?php
            //SECTION POUR LA PAGE ACCEUIL
            if ($nom_page === 'acceuil' || $nom_page === 'carte') {
                echo "<li><a href='carte.php'>La carte</a></li>";
                echo "<li><a href='profil.php'>Profil</a></li>";
                if ($nom_page === 'carte') {
                    echo "<li><a href='panier.php'>Panier</a></li>";
                }

            //SECTION POUR LA PAGE PROFIL
            } elseif ($nom_page === 'profil') {
                echo "<li><a href='mes_commandes.php'>Mes commandes</a></li>";

            //SECTION POUR LA PAGE ADMIN
            } elseif ($nom_page === 'admin'|| $nom_page === 'restaurant' || $nom_page === 'livreur' || $nom_page === 'livraison' || $nom_page === 'commandes') {
                echo "<li><a href='restaurant.php'>Restaurant</a></li>";
                echo "<li><a href='livreur.php'>Livreur</a></li>";
                echo "<li><a href='livraison.php'>Livraison</a></li>";
                echo "<li><a href='commandes.php'>Commandes</a></li>";
            }

            //SECTION COMMUNE A TOUTES LES PAGES (sauf l'inscription/connexion)
            if ($nom_page !== 'inscription' && $nom_page !== 'connexion') {
                if (isset($_SESSION['user'])) {

                    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                        echo "<li><a href='admin.php'>[PANNEAU_ADMIN]</a></li>";
                    }
                    
                    echo "<li><a href='logout.php' class='nav-login'>Déconnexion</a></li>";
                    echo "<li class='user-status'>ID: " . strtoupper(htmlspecialchars($_SESSION['prenom'])) . "</li>";
                
                } else {
                    echo "<li><a href='connexion.php' class='nav-login'>Connexion</a></li>";
                    echo "<li class='user-status'>STATUT: INVITÉ</li>";
                }

            } elseif ($nom_page === 'inscription') {
                echo "<li><a href='connexion.php' class='nav-login'>Connexion</a></li>";
                echo "<li class='user-status'>STATUT: INVITÉ</li>";

            } elseif ($nom_page === 'connexion') {
                echo "<li><a href='inscription.php' class='nav-login'>Inscription</a></li>";
                echo "<li class='user-status'>STATUT: INVITÉ</li>";
            } ?>

        </ul>
    </nav>
</header>