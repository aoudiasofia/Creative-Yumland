<?php
session_start();
require_once '../includes/fonctions.php';
initialiserPanier();

if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');
    exit;
}

// On récupère l'ID depuis la session de manière sécurisée
$user_id = $_SESSION['user']; 
$user_info = getInfoUser($user_id);
$total_panier = calculerTotalPanier();

// On récupère tous les plats du JSON pour pouvoir afficher le nom des options du menu
$tous_les_plats = getTousLesPlats();
$plats_par_id = [];
foreach ($tous_les_plats as $p) {
    $plats_par_id[$p['id']] = $p;
}
?>

<!DOCTYPE html>
<html lang="fr">

<?php 
    $titre_page = "KØLD | PANIER";
    include '../includes/head.php';
?>

<body class="kold-mode">

    <?php 
        $nom_page = "panier";
        include '../includes/header.php';
    ?>
    <main>
        <section class="panier-section">
            <div class="container">
                <h1 class="main-title">PANIER</h1>
                
                <?php if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])): ?>
                    <p>Votre panier est vide.</p>
                <?php else: ?>
                    <div class="panier-items">
                        <?php foreach ($_SESSION['panier'] as $cle => $item): 
                            $id_produit = $item['id'];
                            $plat_principal = $plats_par_id[$id_produit] ?? null;
                            if (!$plat_principal) continue; // Sécurité si le produit n'existe plus
                            
                            $prix_unitaire = (float)$plat_principal['prix'];
                            $sous_total_item = $prix_unitaire * (int)$item['quantite'];
                        ?>
                            <div class="panier-item" style="border: 3px solid #000; padding: 15px; margin-bottom: 15px; background: #fff; box-shadow: 4px 4px 0px #000; display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <h3 style="margin-top: 0; text-transform: uppercase; font-weight: 900;">
                                        <?php echo htmlspecialchars($plat_principal['nom']); ?>
                                    </h3>
                                    
                                    <?php if ($item['type'] === 'menu' && isset($item['choix'])): ?>
                                        <ul style="font-size: 0.85rem; color: #555; padding-left: 20px; margin: 5px 0;">
                                            <?php if(!empty($item['choix']['plat'])): ?>
                                                <li>Plat : <?php echo htmlspecialchars($plats_par_id[$item['choix']['plat']]['nom'] ?? 'Inconnu'); ?></li>
                                            <?php endif; ?>
                                            <?php if(!empty($item['choix']['boisson'])): ?>
                                                <li>Boisson : <?php echo htmlspecialchars($plats_par_id[$item['choix']['boisson']]['nom'] ?? 'Aucune'); ?></li>
                                            <?php endif; ?>
                                            <?php if(!empty($item['choix']['dessert'])): ?>
                                                <li>Dessert : <?php echo htmlspecialchars($plats_par_id[$item['choix']['dessert']]['nom'] ?? 'Aucun'); ?></li>
                                            <?php endif; ?>
                                        </ul>
                                    <?php endif; ?>

                                    <p style="margin: 5px 0 0 0; font-size: 0.9rem;">
                                        Prix: <?php echo number_format($prix_unitaire, 2, '.', ' '); ?> € 
                                        | Quantité: <strong><?php echo $item['quantite']; ?></strong>
                                    </p>
                                </div>

                                <div style="text-align: right; display: flex; flex-direction: column; gap: 10px; align-items: flex-end;">
                                    <span style="font-weight: bold; font-size: 1.1rem;"><?php echo number_format($sous_total_item, 2, '.', ' '); ?> €</span>
                                    
                                    <form method="POST" action="traitement_supprimer_panier.php" style="margin:0;">
                                        <input type="hidden" name="cle_panier" value="<?php echo htmlspecialchars($cle); ?>">
                                        <button type="submit" class="btn-brutal btn-small" style="background: #FF4444; color: #FFF; font-size: 0.75rem; padding: 4px 8px; cursor:pointer;">
                                            ❌ RETIRER
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="total-section" style="margin-top: 30px; border-top: 2px solid #000; padding-top: 15px;">
                        <?php $remise = floatval($user_info['remise'] ?? 0); ?>
                        <?php if ($remise > 0): ?>
                            <div class="prix-ligne" style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <span>Sous-total</span>
                                <span><?php echo number_format($total_panier, 2, '.', ' '); ?> €</span>
                            </div>
                            <div class="prix-ligne remise" style="display: flex; justify-content: space-between; color: green; font-weight: bold; margin-bottom: 5px;">
                                <span>Remise Fidélité</span>
                                <span>-<?php echo number_format($remise, 2, '.', ' '); ?> €</span>
                            </div>
                        <?php endif; ?>
                        <h2 style="text-align: right; font-weight: 900; text-transform: uppercase;">
                            Total Final: <?php echo number_format(max(0, $total_panier - $remise), 2, '.', ' '); ?> €
                        </h2>
                    </div>
                    
                    <form action="traitement_validation_panier.php" method="post" class="validation-form" style="margin-top: 30px; background: #f9f9f9; padding: 20px; border: 2px solid #000;">
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label style="font-weight: bold; display: block; margin-bottom: 5px;">Quand souhaitez-vous être livré ?</label>
                            <div class="radio-group">
                                <label style="margin-right: 15px;">
                                    <input type="radio" name="quand" value="maintenant" checked> Maintenant
                                </label>
                                <label>
                                    <input type="radio" name="quand" value="plus tard"> Plus tard
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-bottom: 20px;">
                            <label for="adresse_livraison" style="font-weight: bold; display: block; margin-bottom: 5px;">Adresse de livraison</label>
                            <input type="text" id="adresse_livraison" name="adresse_livraison" value="<?php echo htmlspecialchars($user_info['adresse'] ?? ''); ?>" required style="width: 100%; padding: 8px; border: 2px solid #000;">
                        </div>
                        
                        <div class="paiement-buttons" style="display: flex; gap: 15px;">
                            <button type="submit" name="paiement" value="cy_bank" class="btn-brutal" style="flex: 1; padding: 12px; font-weight: bold;">PAYER AVEC CY BANK</button>
                            <button type="submit" name="paiement" value="plus_tard" class="btn-brutal" style="flex: 1; padding: 12px; font-weight: bold; background: #aaa;">PAYER PLUS TARD</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.html'; ?>

</body>
</html>