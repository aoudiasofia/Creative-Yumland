<header class="main-header">
    <div class="logo">
        <a href="accueil.php" style="text-decoration: none; color: inherit;">KØLD</a>
    </div>
    <nav>
        <ul>
            <?php if (isset($_SESSION['user'])): ?>    
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li><a href="admin.php">[PANNEAU_ADMIN]</a></li>
                <?php endif; ?>

                <li><a href="presentation.php">La Carte</a></li>
                <li><a href="mes_commandes.php">Mes Commandes</a></li>
                <li><a href="logout.php" class="nav-login">Déconnexion</a></li>
                <li class="user-status">ID: <?php echo strtoupper(htmlspecialchars($_SESSION['user'])); ?></li>
            
            <?php else: ?>
                <li><a href="presentation.php">La Carte</a></li>
                <li><a href="login.php" class="nav-login">Connexion</a></li>
                <li class="user-status">STATUT: INVITÉ</li>
            <?php endif; ?>
        </ul>
    </nav>
</header>