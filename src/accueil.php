<?php 
session_start(); 
include '../includes/fonctions.php';

$top_plats = getPlatsPopulaires(3);
?>
<!DOCTYPE html>
<html lang="fr">

<?php 
    $titre_page = "KØLD | STAY KØLD";
    include '../includes/head.php';
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
                <div class="hero-banner"></div>
                <div class="cta-group">
                    <a href="carte.php" class="btn-brutal">ACCÉDER A LA CARTE</a>
                </div>
            </div>
        </section>

        <section class="menu-category" style="padding: 40px 5%; margin: 0 auto;">
            <h2 class="category-title" style="text-align: center; display: block; font-size: 2rem; margin-bottom: 30px; text-transform: uppercase; font-weight: 900;">
                🔥 TOP COMMANDES 
            </h2>
            
            <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 20px;">
                <?php foreach ($top_plats as $plat): ?>
                    <div class="product-card" style="width: 280px; padding: 15px; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <span style="background: #000; color: #fff; padding: 2px 6px; font-weight: bold; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.05em; display: inline-block; margin-bottom: 10px;">
                                ⭐ <?= $plat['commandes'] ?> commandés
                            </span>
                            
                            <img src="<?= htmlspecialchars($plat['image']) ?>" alt="<?= htmlspecialchars($plat['nom']) ?>" class="product-image" style="width: 100%; height: 150px; object-fit: contain; margin-bottom: 10px; border: none;">
                            <h3 class="product-name" style="font-size: 1.2rem; margin-bottom: 5px; text-transform: uppercase; font-weight: 900;"><?= htmlspecialchars($plat['nom']) ?></h3>
                            <p class="product-desc" style="font-size: 0.8rem; margin-bottom: 15px; color: #555;"><?= htmlspecialchars($plat['description']) ?></p>
                        </div>
                        <div class="product-action" style="margin-top: auto; display: flex; justify-content: space-between; align-items: center;">
                            <a href="carte.php" class="btn-brutal btn-small" style="padding: 10px 15px; font-size: 0.9rem; text-decoration: none; text-align: center;">VOIR</a>
                            <span class="product-price" style="font-size: 1.2rem; font-weight: 900;"><?= number_format($plat['prix'], 2, ',', ' ') ?> €</span>
                        </div>
                    </div>
                <?php endforeach; ?>
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