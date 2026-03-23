<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KØLD | LA CARTE</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Space+Mono:wght@400;700&display=swap"
        rel="stylesheet">
</head>

<body class="kold-mode">

    <header class="main-header">
        <div class="logo">
            <a href="accueil.php" style="text-decoration: none; color: inherit;">KØLD</a>
        </div>
        <nav>
            <ul>
                
                <li><a href="presentation.php" class="active">La Carte</a></li>
                <li><a href="inscription.php">Inscription</a></li>
                <li><a href="login.php">Connexion</a></li>
            </ul>
        </nav>
    </header>

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

        <section class="menu-category">
            <h2 class="category-title">Les Bowls Givrés</h2>
            <div class="product-grid">
                <div class="product-card">
                    <img src="../images/poke_saumon.png" alt="Le Poke Saumon" class="product-image">
                    <h3 class="product-name">Le Poke Saumon</h3>
                    <p class="product-desc">Saumon frais, avocat, concombre et sauce soja onctueuse.</p>
                    <div class="product-action">
                        <button class="btn-brutal btn-small">AJOUTER</button>
                        <span class="product-price">14.90 €</span>
                    </div>
                </div>
                <div class="product-card">
                    <img src="../images/poke_poulet.png" alt="Le Poke Poulet" class="product-image">
                    <h3 class="product-name">Le Poke Poulet</h3>
                    <p class="product-desc">Poulet croustillant (froid), maïs, mangue et sauce sésame.</p>
                    <div class="product-action">
                        <button class="btn-brutal btn-small">AJOUTER</button>
                        <span class="product-price">13.50 €</span>
                    </div>
                </div>
                <div class="product-card">
                    <img src="../images/veggie_ice.png" alt="Le Veggie Ice" class="product-image">
                    <h3 class="product-name">Le Veggie Ice</h3>
                    <p class="product-desc">Tofu mariné, edamames, carottes râpées et éclats de cacahuètes.</p>
                    <div class="product-action">
                        <button class="btn-brutal btn-small">AJOUTER</button>
                        <span class="product-price">12.90 €</span>
                    </div>
                </div>
                <div class="product-card">
                    <img src="../images/poke_crevette.png" alt="La Poke Crevette" class="product-image">
                    <h3 class="product-name">La Poke Crevette</h3>
                    <p class="product-desc">Riz froid, crevettes roses, radis noir, edamame et sauce citronnée.</p>
                    <div class="product-action">
                        <button class="btn-brutal btn-small">AJOUTER</button>
                        <span class="product-price">15.50 €</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="menu-category">
            <h2 class="category-title">Les Wraps & Sandwichs Froids</h2>
            <div class="product-grid">
                <div class="product-card">
                    <img src="../images/wrap_banquise.png" alt="Le Wrap Banquise" class="product-image">
                    <h3 class="product-name">Le Wrap Banquise</h3>
                    <p class="product-desc">Tortilla de blé, thon mayo, maïs, salade croquante et tomates.</p>
                    <div class="product-action">
                        <button class="btn-brutal btn-small">AJOUTER</button>
                        <span class="product-price">8.90 €</span>
                    </div>
                </div>
                <div class="product-card">
                    <img src="../images/wrap_arctique.png" alt="Le Wrap Arctique" class="product-image">
                    <h3 class="product-name">Le Wrap Arctique</h3>
                    <p class="product-desc">Poulet végé, sauce césar froide, parmesan et feuilles de romaine.</p>
                    <div class="product-action">
                        <button class="btn-brutal btn-small">AJOUTER</button>
                        <span class="product-price">9.50 €</span>
                    </div>
                </div>
                <div class="product-card">
                    <img src="../images/sandwich_nordique.png" alt="Le Sandwich Nordique" class="product-image">
                    <h3 class="product-name">Le Sandwich Nordique</h3>
                    <p class="product-desc">Pain de mie complet, saumon fumé, fromage frais à l'aneth et concombre.</p>
                    <div class="product-action">
                        <button class="btn-brutal btn-small">AJOUTER</button>
                        <span class="product-price">9.90 €</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="menu-category">
            <h2 class="category-title">Salades</h2>
            <div class="product-grid">
                <div class="product-card">
                    <img src="../images/salade_cristal.png" alt="La Salade Cristal" class="product-image">
                    <h3 class="product-name">La Salade Cristal</h3>
                    <p class="product-desc">Pâtes froides, mozzarella, tomates cerises et pesto.</p>
                    <div class="product-action">
                        <button class="btn-brutal btn-small">AJOUTER</button>
                        <span class="product-price">11.50 €</span>
                    </div>
                </div>
                <div class="product-card">
                    <img src="../images/salade_fraiche.png" alt="La Salade fraîche" class="product-image">
                    <h3 class="product-name">La Salade fraîche</h3>
                    <p class="product-desc">Mélange de crevettes roses, avocat, pamplemousse, assaisonné à l'aneth fraîche.</p>
                    <div class="product-action">
                        <button class="btn-brutal btn-small">AJOUTER</button>
                        <span class="product-price">12.90 €</span>
                    </div>
                </div>
                <div class="product-card">
                    <img src="../images/salade_blanche.png" alt="Salade Blanche" class="product-image">
                    <h3 class="product-name">Salade Blanche</h3>
                    <p class="product-desc">Endives croquantes, pomme, noix et copeaux de fromage de chèvre.</p>
                    <div class="product-action">
                        <button class="btn-brutal btn-small">AJOUTER</button>
                        <span class="product-price">10.90 €</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="menu-category">
            <h2 class="category-title">Desserts</h2>
            <div class="product-grid">
                <div class="product-card">
                    <img src="../images/iceberg.png" alt="Le Spécial Iceberg" class="product-image">
                    <h3 class="product-name">Le Spécial "Iceberg"</h3>
                    <p class="product-desc">Énorme boule de sorbet menthe avec des pépites de chocolat noir.</p>
                    <div class="product-action">
                        <button class="btn-brutal btn-small">AJOUTER</button>
                        <span class="product-price">5.50 €</span>
                    </div>
                </div>
                <div class="product-card">
                    <img src="../images/cheesecake.png" alt="Cheesecake aux Fruits Rouges" class="product-image">
                    <h3 class="product-name">Cheesecake aux Fruits Rouges</h3>
                    <p class="product-desc">Gâteau au fromage blanc très frais sur un biscuit croquant, coulis glacé.</p>
                    <div class="product-action">
                        <button class="btn-brutal btn-small">AJOUTER</button>
                        <span class="product-price">6.90 €</span>
                    </div>
                </div>
                <div class="product-card">
                    <img src="../images/dome_de_neige.png" alt="Le Dôme de Neige" class="product-image">
                    <h3 class="product-name">Le Dôme de Neige</h3>
                    <p class="product-desc">Coque de glace vanille, noisettes croquantes et cœur de meringue.</p>
                    <div class="product-action">
                        <button class="btn-brutal btn-small">AJOUTER</button>
                        <span class="product-price">6.50 €</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="menu-category">
            <h2 class="category-title">Boissons</h2>
            <div class="product-grid">
                <div class="product-card">
                    <img src="../images/cafe_glace.png" alt="Café Glacé" class="product-image">
                    <h3 class="product-name">Café Glacé</h3>
                    <p class="product-desc">Un café classique servi avec des glaçons.</p>
                    <div class="product-action">
                        <button class="btn-brutal btn-small">AJOUTER</button>
                        <span class="product-price">3.50 €</span>
                    </div>
                </div>
                <div class="product-card">
                    <img src="../images/blue_lagoon.png" alt="Blue Lagoon" class="product-image">
                    <h3 class="product-name">Blue Lagoon</h3>
                    <p class="product-desc">Cocktail bleu (Vodka, curaçao, citron) très rafraîchissant.</p>
                    <div class="product-action">
                        <button class="btn-brutal btn-small">AJOUTER</button>
                        <span class="product-price">8.50 €</span>
                    </div>
                </div>
                <div class="product-card">
                    <img src="../images/margarita_givree.png" alt="Margarita Givrée" class="product-image">
                    <h3 class="product-name">Margarita Givrée</h3>
                    <p class="product-desc">Tequila et citron, mixés avec de la glace pilée.</p>
                    <div class="product-action">
                        <button class="btn-brutal btn-small">AJOUTER</button>
                        <span class="product-price">9.00 €</span>
                    </div>
                </div>
                <div class="product-card">
                    <img src="../images/biere_arctique.png" alt="Bière Arctique" class="product-image">
                    <h3 class="product-name">Bière Arctique</h3>
                    <p class="product-desc">Une bière blonde servie dans un verre sorti du congélateur.</p>
                    <div class="product-action">
                        <button class="btn-brutal btn-small">AJOUTER</button>
                        <span class="product-price">5.00 €</span>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <footer class="kold-footer">
        <p> KØLD // PROJET PREING2 - 2025-2026</p>
    </footer>

</body>

</html>