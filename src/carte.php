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
                    <button class="btn-brutal cat-btn" data-cat="menus" style="padding: 5px 10px; font-size: 0.75rem;">MENUS</button>
                    <button class="btn-brutal cat-btn" data-cat="bowls" style="padding: 5px 10px; font-size: 0.75rem;">POKÉS</button>
                    <button class="btn-brutal cat-btn" data-cat="wraps" style="padding: 5px 10px; font-size: 0.75rem;">WRAPS/SANDWICHS</button>
                    <button class="btn-brutal cat-btn" data-cat="salades" style="padding: 5px 10px; font-size: 0.75rem;">SALADES/ENTREES</button>
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
                        
                        <?php if (isset($plat['categorie']) && $plat['categorie'] === 'menus'): ?>
                            <span style="position:absolute; top:10px; left:10px; background:#000; color:#fff; font-size:10px; font-weight:bold; padding:2px 5px; border:1px solid #fff;">FORMULE</span>
                        <?php endif; ?>

                        <img src="<?php echo htmlspecialchars($plat['image']); ?>" class="product-image">
                        <h3 class="product-name"><?php echo htmlspecialchars($plat['nom']); ?></h3>
                        <p class="product-desc"><?php echo htmlspecialchars($plat['description']); ?></p>
                        
                        <div class="product-action">
                            <span class="product-price"><?php echo number_format($plat['prix'], 2, '.', ' ') ?> €</span>
                            
                            <?php if (isset($plat['categorie']) && $plat['categorie'] === 'menus'): ?>
                                <button type="button" class="btn-brutal btn-small" onclick="ouvrirModalMenu(<?php echo $plat['id']; ?>, '<?php echo addslashes($plat['nom']); ?>', '<?php echo addslashes($plat['description']); ?>')">
                                    ⚡ CONFIGURER
                                </button>
                            <?php else: ?>
                                <form method="POST" action="traitement_ajouter_panier.php" style="margin:0;">
                                    <input type="hidden" name="action" value="ajouter">
                                    <input type="hidden" name="type" value="plat">
                                    <input type="hidden" name="id_produit" value="<?php echo $plat['id']; ?>">
                                    <button type="submit" class="btn-brutal btn-small">AJOUTER</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <?php include '../includes/footer.html'; ?>

    <div id="menuModal" class="modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:9999; justify-content:center; align-items:center;">
        <div class="modal-content" style="background:#FFF; border:4px solid #000; padding:30px; width:90%; max-width:450px; box-shadow: 8px 8px 0px #000; position:relative;">
            
            <button type="button" onclick="fermerModal()" style="position:absolute; top:10px; right:10px; background:#000; color:#FFF; font-weight:bold; border:none; padding:5px 10px; cursor:pointer;">X</button>
            
            <h2 id="modalMenuNom" style="text-transform:uppercase; font-weight:900; margin-top:0;">Configurer le Menu</h2>
            <p id="modalMenuDesc" style="font-size:14px; color:#555; margin-bottom:20px;"></p>
            
            <form id="formAjoutMenu" method="POST" action="traitement_ajouter_panier.php">
                <input type="hidden" name="id_produit" id="modalMenuId" value="">
                <input type="hidden" name="type" value="menu">

                <div id="zoneChoixFormule"></div>

                <button type="submit" class="btn-brutal" style="display:block; width:100%; background:#000; color:#FFF; border:2px solid #000; padding:12px; font-weight:bold; text-transform:uppercase; margin-top:20px; cursor:pointer; box-shadow: 4px 4px 0 #000;">
                    🥶 VALIDER ET AJOUTER AU PANIER
                </button>
            </form>
        </div>
    </div>

    <script>
    // 1. GESTION DES FILTRES ET TRIS DE LA CARTE
    let activeCategory = 'tous';
    let activeRefine = 'tous';

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

    document.querySelectorAll('.cat-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            activeCategory = this.getAttribute('data-cat');
            updateMenu();
        });
    });

    document.getElementById('refine-filter').addEventListener('change', function() {
        activeRefine = this.value;
        updateMenu();
    });

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
                if (sortType === 'popular') return ordersB - ordersA;
                return 0;
            });

            grid.innerHTML = "";
            cards.forEach(card => grid.appendChild(card));
        });
    });

    // 2. LOGIQUE DE LA POP-UP MENU (ALIMENTÉE PAR TON JSON)
    const optionsBoissons = <?php echo json_encode(getProduitsParCategorie('boissons')); ?>;
    const optionsDesserts = <?php echo json_encode(getProduitsParCategorie('desserts')); ?>;
    const optionsWraps = <?php echo json_encode(getProduitsParCategorie('wraps')); ?>;
    const optionsBowls = <?php echo json_encode(getProduitsParCategorie('bowls')); ?>;

    function ouvrirModalMenu(id, nom, description) {
        document.getElementById('modalMenuId').value = id;
        document.getElementById('modalMenuNom').innerText = nom;
        document.getElementById('modalMenuDesc').innerText = description;
        
        const zoneChoix = document.getElementById('zoneChoixFormule');
        zoneChoix.innerHTML = ""; 

        let htmlFormulaire = "";

        if (id === 17) { // Formule Fjord Express
            htmlFormulaire += genererSelect("choix_plat", "CHOISIS TON WRAP :", optionsWraps);
            htmlFormulaire += genererSelect("choix_boisson", "CHOISIS TA BOISSON GIVRÉE :", optionsBoissons);
        } 
        else if (id === 18) { // Menu Toundra XL
            htmlFormulaire += genererSelect("choix_plat", "CHOISIS TON POKE BOWL :", optionsBowls);
            htmlFormulaire += genererSelect("choix_dessert", "CHOISIS TON DESSERT :", optionsDesserts);
            htmlFormulaire += genererSelect("choix_boisson", "CHOISIS TA BOISSON :", optionsBoissons);
        } 
        else if (id === 19) { // Le Pack Banquise Végé
            htmlFormulaire += `<p style='font-weight:bold; margin-bottom:5px; margin-top:15px; font-size:12px;'>PLAT INCLUS :</p>
                               <p style='background:#EEE; padding:10px; border:2px solid #000; font-weight:bold;'>🥗 Le Veggie Ice (Imposé)</p>
                               <input type='hidden' name='choix_plat' value='3'>`;
            htmlFormulaire += genererSelect("choix_dessert", "CHOISIS TON DESSERT :", optionsDesserts);
        }

        zoneChoix.innerHTML = htmlFormulaire;
        document.getElementById('menuModal').style.display = 'flex';
    }

    function genererSelect(name, label, options) {
        if(!options || options.length === 0) return ""; 
        
        let selectHtml = `<div style="margin-top:15px;">
            <label style="display:block; font-weight:bold; margin-bottom:5px; font-size:12px; text-transform:uppercase;">${label}</label>
            <select name="${name}" required style="width:100%; padding:10px; border:2px solid #000; font-weight:bold; background:#FFF; cursor:pointer;">`;
        
        options.forEach(opt => {
            selectHtml += `<option value="${opt.id}">${opt.nom}</option>`;
        });
        
        selectHtml += `</select></div>`;
        return selectHtml;
    }

    function fermerModal() {
        document.getElementById('menuModal').style.display = 'none';
    }
    </script>
</body>
</html>