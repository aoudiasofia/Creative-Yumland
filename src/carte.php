<?php
session_start();
require_once '../includes/fonctions.php';
initialiserPanier();

$tous_les_plats = getTousLesPlats();
$tous_les_menus = getTousLesMenus();
$plats_par_categorie = getPlatsParCategorie();

// Noms des catégories
$noms_categories = [
    'bowls' => 'Les Bowls Givrés',
    'wraps' => 'Les Wraps & Sandwichs Froids',
    'salades' => 'Salades',
    'desserts' => 'Desserts',
    'boissons' => 'Boissons'
];
?>

<!DOCTYPE html>
<html lang="fr">

<?php 
    $titre_page = "KØLD | CARTE";
    include '../includes/head.html';
?>

<body class="kold-mode">

    <?php 
        $nom_page = "carte";
        include '../includes/header.php';
    ?>

    <main class="menu-container">
        <h1 class="main-title">LA CARTE</h1>

        <!-- SECTION MENUS -->
        <?php if (!empty($tous_les_menus)): ?>
            <section class="menu-category">
                <h2 class="category-title">NOS MENUS</h2>
                <div class="product-grid">
                    <?php foreach ($tous_les_menus as $menu): ?>
                        <div class="product-card menu-card">
                            <div class="product-image-placeholder">
                                <span>MENU</span>
                            </div>
                            <h3 class="product-name"><?php echo htmlspecialchars($menu['nom']); ?></h3>
                            <p class="product-desc"><?php echo htmlspecialchars($menu['description']); ?></p>
                            <div class="product-action">
                                <span class="product-price"><?php echo number_format($menu['prix'], 2, '.', ' ') ?> €</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <!-- SECTION PLATS PAR CATÉGORIE -->
        <?php foreach ($plats_par_categorie as $categorie => $plats): ?>
            <?php if (empty($plats)) continue; ?>
            <section class="menu-category">
                <h2 class="category-title"><?php echo htmlspecialchars($noms_categories[$categorie] ?? ucfirst($categorie)); ?></h2>
                <div class="product-grid">
                    <?php foreach ($plats as $plat): ?>
                        <div class="product-card">
                            <img src="<?php echo htmlspecialchars($plat['image']); ?>" alt="<?php echo htmlspecialchars($plat['nom']); ?>" class="product-image">
                            <h3 class="product-name"><?php echo htmlspecialchars($plat['nom']); ?></h3>
                            <p class="product-desc"><?php echo htmlspecialchars($plat['description']); ?></p>
                            <div class="product-action">
                                <form method="POST" action="traitement_ajouter_panier.php" style="margin:0; display:flex;">
                                    <input type="hidden" name="action" value="ajouter">
                                    <input type="hidden" name="id_produit" value="<?php echo htmlspecialchars($plat['id']); ?>">
                                    <button type="submit" class="btn-brutal btn-small" style="margin-right:10px;">AJOUTER</button>
                                </form>
                                <span class="product-price"><?php echo number_format($plat['prix'], 2, '.', ' ') ?> €</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>

    </main>

    <?php include '../includes/footer.html'; ?>

</body>
</html>