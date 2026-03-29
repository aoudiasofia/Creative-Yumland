<?php
session_start();
require_once '../includes/fonctions.php';
initialiserPanier();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter') {
    if (isset($_POST['id_produit'])) {
        ajouterAuPanier($_POST['id_produit'], 1);
    }
    header('Location: presentation.php');
    exit;
}

$tous_les_plats = getTousLesPlats();
$plats_tries = [];

foreach ($tous_les_plats as $plat) {
    $categorie = $plat['categorie'];
    if (!isset($plats_tries[$categorie])) {
        $plats_tries[$categorie] = [];
    }
    $plats_tries[$categorie][] = $plat;
}

$nom_categories = [
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
    include '../includes/head.php';
?>


<body class="kold-mode">

    <?php 
        $nom_page = "carte";
        include '../includes/header.php';
    ?>

    <main class="menu-container">
        <h1 class="main-title">LA CARTE</h1>

        <div class="filter-controls">
            <div class="search-unit" style="margin: 0; flex-grow: 1;">
                <form class="search-form" style="box-shadow: none;">
                    <input type="text" placeholder="RECHERCHER UN PRODUIT..." class="search-input">
                    <button type="submit" class="search-submit">FILTRER</button>
                </form>
            </div>
            <div class="category-filters">
                <button class="filter-btn active">TOUT</button>
                <button class="filter-btn">BOWLS</button>
                <button class="filter-btn">WRAPS</button>
                <button class="filter-btn">SALADES</button>
                <button class="filter-btn">DESSERTS</button>
                <button class="filter-btn">BOISSONS</button>
            </div>
        </div>

        <?php foreach ($plats_tries as $categorie => $plats) : ?>
            <?php if (empty($plats)) continue; ?>
            <section class="menu-category">
                <h2 class="category-title"><?= htmlspecialchars($nom_categories[$categorie] ?? ucfirst($categorie)) ?></h2>
                <div class="product-grid">
                    <?php foreach ($plats as $plat) : ?>
                        <div class="product-card">
                            <img src="<?= htmlspecialchars($plat['image']) ?>" alt="<?= htmlspecialchars($plat['nom']) ?>" class="product-image">
                            <h3 class="product-name"><?= htmlspecialchars($plat['nom']) ?></h3>
                            <p class="product-desc"><?= htmlspecialchars($plat['description']) ?></p>
                            <div class="product-action">
                                <form method="POST" action="presentation.php" style="margin:0; display:flex;">
                                    <input type="hidden" name="action" value="ajouter">
                                    <input type="hidden" name="id_produit" value="<?= htmlspecialchars($plat['id']) ?>">
                                    <button type="submit" class="btn-brutal btn-small" style="margin-right:10px;">AJOUTER</button>
                                </form>
                                <span class="product-price"><?= number_format($plat['prix'], 2, '.', ' ') ?> €</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>

    </main>

    <?php include '../includes/footer.php'; ?>

</body>
</html>