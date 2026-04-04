<?php 
session_start(); 
?>
<!DOCTYPE html>
<html lang="fr">

<?php 
    $titre_page = "KØLD | STAY KØLD";
    include '../includes/head.html';
?>
 
<body class="kold-mode">

    <?php 
        $nom_page = "acceuil";
        include '../includes/header.php';
    ?>

    <main>
        <section class="hero-brutal">
            <div class="hero-content">
                <h1 class="main-title">STAY KØLD.</h1>
                <div class="search-unit">
                    <form class="search-form">
                        <input type="text" placeholder="RECHERCHER UN PRODUIT " class="search-input">
                        <button type="submit" class="search-submit">SCANNER</button>
                    </form>
                </div>
                <div class="hero-banner"></div>
                <div class="cta-group">
                    <a href="carte.php" class="btn-brutal">ACCÉDER A LA CARTE</a>
                </div>
            </div>
        </section>

        <section class="menu-category" style="padding: 40px 5%; margin: 0 auto;">
            <h2 class="category-title" style="text-align: center; display: block; font-size: 2rem; margin-bottom: 30px;">TOP COMMANDES</h2>
            
            <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 20px;">
                <div class="product-card" style="width: 280px; padding: 15px;">
                    <img src="../images/poke_saumon.png" alt="Le Poke Saumon" class="product-image" style="width: 100%; height: 150px; object-fit: contain; margin-bottom: 10px; border: none;">
                    <h3 class="product-name" style="font-size: 1.2rem; margin-bottom: 5px;">Le Poke Saumon</h3>
                    <p class="product-desc" style="font-size: 0.8rem; margin-bottom: 15px;">Notre best-seller. Saumon frais, avocat, concombre.</p>
                    <div class="product-action">
                        <button class="btn-brutal btn-small" style="padding: 10px 15px; font-size: 0.9rem;">AJOUTER</button>
                        <span class="product-price" style="font-size: 1.2rem;">14.90 €</span>
                    </div>
                </div>

                <div class="product-card" style="width: 280px; padding: 15px;">
                    <img src="../images/wrap_banquise.png" alt="Le Wrap Banquise" class="product-image" style="width: 100%; height: 150px; object-fit: contain; margin-bottom: 10px; border: none;">
                    <h3 class="product-name" style="font-size: 1.2rem; margin-bottom: 5px;">Le Wrap Banquise</h3>
                    <p class="product-desc" style="font-size: 0.8rem; margin-bottom: 15px;">Le choix rapide. Thon mayo, maïs, salade croquante.</p>
                    <div class="product-action">
                        <button class="btn-brutal btn-small" style="padding: 10px 15px; font-size: 0.9rem;">AJOUTER</button>
                        <span class="product-price" style="font-size: 1.2rem;">8.90 €</span>
                    </div>
                </div>

                <div class="product-card" style="width: 280px; padding: 15px;">
                    <img src="../images/iceberg.png" alt="Le Spécial Iceberg" class="product-image" style="width: 100%; height: 150px; object-fit: contain; margin-bottom: 10px; border: none;">
                    <h3 class="product-name" style="font-size: 1.2rem; margin-bottom: 5px;">L'Iceberg</h3>
                    <p class="product-desc" style="font-size: 0.8rem; margin-bottom: 15px;">Le dessert du moment. Sorbet menthe et pépites chocolat.</p>
                    <div class="product-action">
                        <button class="btn-brutal btn-small" style="padding: 10px 15px; font-size: 0.9rem;">AJOUTER</button>
                        <span class="product-price" style="font-size: 1.2rem;">5.50 €</span>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <section class="about-simple">
        <div class="about-content-simple">
            <div class="about-text">
                <p>
                    <strong>KØLD</strong> EST NÉ SUR LE CAMPUS DE <strong>CY TECH</strong>.
                    IL RÉINVENTE LA PAUSE DÉJEUNER. FINI LES REPAS LOURDS QUI VOUS RALENTISSENT EN PLEINE JOURNÉE.
                </p>
                <p>
                    NOUS UTILISONS LE POUVOIR DU FROID POUR PRÉSERVER TOUTE LA SAVEUR DE NOS INGRÉDIENTS.
                </p>
                <div class="motto-simple">"LE TIÈDE EST NOTRE ENNEMI."</div>
            </div>
            <div class="about-image">
                <img src="../images/IMG_2648.jpg" alt="KOLD Team">
            </div>
        </div>
    </section>

    <?php include '../includes/footer.html'; ?>
    
</body>
</html>