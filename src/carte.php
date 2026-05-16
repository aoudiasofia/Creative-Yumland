<?php
session_start();
require_once '../includes/fonctions.php';
initialiserPanier();

// Chargement initial
$tous_les_plats = getTousLesPlats(); 
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

    <div class="controls-container" style="background: #fff; border-bottom: 2px solid #000; padding: 10px 0; position: sticky; top: 0; z-index: 100;">
    <div style="max-width: 1000px; margin: 0 auto; padding: 0 15px;">
        
        <div style="display: flex; flex-direction: column; gap: 8px; align-items: center;">
            
            <div class="category-tabs" style="display: flex; gap: 5px; flex-wrap: wrap; justify-content: center;">
                <button class="btn-brutal cat-btn active" data-cat="tous" style="padding: 5px 10px; font-size: 0.75rem;">TOUT</button>
                <button class="btn-brutal cat-btn" data-cat="bowls" style="padding: 5px 10px; font-size: 0.75rem;">POKÉS</button>
                <button class="btn-brutal cat-btn" data-cat="wraps" style="padding: 5px 10px; font-size: 0.75rem;">WRAPS</button>
                <button class="btn-brutal cat-btn" data-cat="salades" style="padding: 5px 10px; font-size: 0.75rem;">SALADES</button>
                <button class="btn-brutal cat-btn" data-cat="desserts" style="padding: 5px 10px; font-size: 0.75rem;">DESSERTS</button>
                <button class="btn-brutal cat-btn" data-cat="boissons" style="padding: 5px 10px; font-size: 0.75rem;">BOISSONS</button>
            </div>

            <div style="display: flex; gap: 15px; flex-wrap: wrap; justify-content: center; align-items: center; width: 100%; border-top: 1px solid #eee; padding-top: 8px;">
                
                <div class="refine-group" style="display: flex; align-items: center; gap: 5px;">
                    <label style="font-weight: bold; font-size: 0.7rem;">OPTION :</label>
                    <select id="refine-filter" class="btn-brutal" style="padding: 3px 8px; font-size: 0.7rem; height: auto;">
                        <option value="tous">Tout</option>
                        <option value="vegetarien">Végétarien 🍃</option>
                    </select>
                </div>

                <div class="sort-group" style="display: flex; gap: 5px; align-items: center;">
                    <label style="font-weight: bold; font-size: 0.7rem;">TRI :</label>
                    <button class="btn-brutal sort-btn" data-sort="price-asc" style="padding: 3px 8px; font-size: 0.7rem;">€ ↑</button>
                    <button class="btn-brutal sort-btn" data-sort="popular" style="padding: 3px 8px; font-size: 0.7rem;">★ TOP</button>
                </div>

            </div>
        </div>
    </div>
</div>

    <main class="menu-container" style="padding-top: 30px;">
        
        <div id="display-zone">
            <div class="product-grid">
                <?php foreach ($tous_les_plats as $plat): ?>
                    <div class="product-card" 
                         data-price="<?php echo $plat['prix']; ?>" 
                         data-orders="<?php echo $plat['commandes'] ?? 0; ?>">
                        
                        <img src="<?php echo htmlspecialchars($plat['image']); ?>" class="product-image">
                        <h3 class="product-name"><?php echo htmlspecialchars($plat['nom']); ?></h3>
                        <p class="product-desc"><?php echo htmlspecialchars($plat['description']); ?></p>
                        
                        <div class="product-action">
                            <span class="product-price"><?php echo number_format($plat['prix'], 2, '.', ' ') ?> €</span>
                            <form method="POST" action="traitement_ajouter_panier.php" style="margin:0;">
                                <input type="hidden" name="action" value="ajouter">
                                <input type="hidden" name="id_produit" value="<?php echo $plat['id']; ?>">
                                <button type="submit" class="btn-brutal btn-small">AJOUTER</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <?php include '../includes/footer.html'; ?>

    <script>
    // Variables d'état pour combiner les filtres
    let activeCategory = 'tous';
    let activeRefine = 'tous';

    /**
     * FONCTION DE MISE À JOUR (ASYNCHRONE)
     * Combine la catégorie + le filtre spécifique
     */
    function updateMenu() {
        const zone = document.getElementById('display-zone');
        zone.style.opacity = '0.4';

        const fd = new FormData();
        fd.append('action', 'filter_menu');
        fd.append('categorie', activeCategory);
        fd.append('filtre', activeRefine);

        fetch('ajax_handler.php', {
            method: 'POST',
            body: fd
        })
        .then(res => res.text())
        .then(html => {
            zone.innerHTML = html;
            zone.style.opacity = '1';
        });
    }

    // Clic sur les catégories principales (Pokés, Wraps...)
    document.querySelectorAll('.cat-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            activeCategory = this.getAttribute('data-cat');
            updateMenu();
        });
    });

    // Changement du filtre spécifique (Végé, Gluten...)
    document.getElementById('refine-filter').addEventListener('change', function() {
        activeRefine = this.value;
        updateMenu();
    });

    /**
     * TRIS (LOCAUX)
     * Réorganise les cartes déjà présentes sans appel serveur
     */
    document.querySelectorAll('.sort-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const sortType = this.getAttribute('data-sort');
            const grid = document.querySelector('.product-grid');
            if (!grid) return;

            const cards = Array.from(grid.querySelectorAll('.product-card'));

            cards.sort((a, b) => {
                const priceA = parseFloat(a.dataset.price);
                const priceB = parseFloat(b.dataset.price);
                const ordersA = parseInt(a.dataset.orders);
                const ordersB = parseInt(b.dataset.orders);

                if (sortType === 'price-asc') return priceA - priceB;
                if (sortType === 'price-desc') return priceB - priceA;
                if (sortType === 'popular') return ordersB - ordersA;
                return 0;
            });

            grid.innerHTML = "";
            cards.forEach(card => grid.appendChild(card));
        });
    });
    </script>
</body>
</html>