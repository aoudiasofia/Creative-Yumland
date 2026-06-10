<?php
session_start(); 
include '../includes/fonctions.php';

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

$user_id = $_SESSION['user'];
$commandes = array_reverse(getCommandesByUserId($user_id));
?>

<!DOCTYPE html>
<html lang="fr">
<?php 
    $titre_page = "KØLD | HISTORIQUE COMMANDE";
    include '../includes/head.php';
?>


<body class="kold-mode">

    <?php 
        $nom_page = "historique_commandes_client";
        include '../includes/header.php';
    ?>

    <main class="historique-container">
        <h1 class="main-title">HISTORIQUE DES COMMANDES</h1>

        <?php if (empty($commandes)): ?>
            <p style="text-align: center; font-size: 1.2rem; margin-top: 40px;">Aucune commande trouvée.</p>
        <?php else: ?>
            <?php foreach ($commandes as $commande): ?>
                <?php $details = calculerDetailCommande($commande); ?>
                <?php
                    $details = calculerDetailCommande($commande);
                    if (!is_array($details)) $details = [];
                    // si la fonction retourne 'items' au lieu de 'articles', normaliser
                    if (isset($details['items']) && !isset($details['articles'])) {
                        $details['articles'] = $details['items'];
                    }
                    // valeurs par défaut pour éviter warnings
                    $details['prix_total_avant_remise'] = floatval($details['prix_total_avant_remise'] ?? ($details['total'] ?? 0));
                    $details['remise'] = floatval($details['remise'] ?? 0);
                    $details['prix_apres_remise'] = floatval($details['prix_apres_remise'] ?? ($details['prix_total_avant_remise'] - $details['remise']));
                    $articles = isset($details['articles']) && is_array($details['articles']) ? $details['articles'] : [];
                    ?>
                 <div class="historique-commande-detail">
                     <div class="detail-header">
                         <h2>Commande #<?php echo htmlspecialchars($commande['id']); ?></h2>
                     </div>

                    <!-- En-tête de la commande -->
                    <div class="detail-header">
                        
                        <div>
                            <h2>Commande #<?php echo htmlspecialchars($commande['id']); ?></h2>
                            <p style="color: var(--accent); font-weight: bold; margin-top: 5px;">
                                <?php echo $commande['date_heure']; ?>
                            </p>
                            <p style="margin-top: 5px;"><strong>Livraison:</strong> <?php echo $commande['quand'] === 'maintenant' ? 'Dès que possible' : 'Plus tard'; ?></p>
                        </div>
                        <div style="text-align: right;">
                            <p style="margin-bottom: 5px;"><strong>Statut de commande:</strong> <?php echo htmlspecialchars($commande['statut_commande']); ?></p>
                            <p><strong>Statut paiement:</strong> <?php echo htmlspecialchars($commande['statut_paiement']); ?></p>
                        </div>
                    </div>

                    <!-- Articles de la commande -->
                    <div class="detail-section articles-section">
                        <h3 style="font-family: 'Archivo Black', sans-serif; font-size: 1.5rem; color: var(--text); margin-bottom: 20px; border-bottom: 2px solid var(--accent); padding-bottom: 10px;">
                            Articles
                        </h3>
                        
                        <div class="articles-list">
                            <?php foreach ($details['articles'] as $article): ?>
                                <div class="article-card">
                                    <div class="article-header">
                                        <h3><?php echo htmlspecialchars($article['nom']); ?></h3>
                                        <span class="article-type"><?php echo $article['type'] === 'menu' ? 'MENU' : 'PLAT'; ?></span>
                                    </div>
                                    
                                    <p class="article-desc"><?php echo htmlspecialchars($article['description']); ?></p>
                                    
                                    <div class="article-quantite">
                                        <span class="label">Quantité</span>
                                        <span class="valeur">
                                            <?php if ($commande['statut_commande'] === 'en attente'): ?>
                                                <button class="btn-brutal btn-modif-qty" data-cmd="<?php echo $commande['id']; ?>" data-prod="<?php echo $article['id_produit']; ?>" data-change="-1" style="padding: 2px 10px; margin-right: 10px; font-size:1rem;">-</button>
                                            <?php endif; ?>
                                            
                                            <?php echo $article['quantite']; ?>
                                            
                                            <?php if ($commande['statut_commande'] === 'en attente'): ?>
                                                <button class="btn-brutal btn-modif-qty" data-cmd="<?php echo $commande['id']; ?>" data-prod="<?php echo $article['id_produit']; ?>" data-change="1" style="padding: 2px 10px; margin-left: 10px; font-size:1rem;">+</button>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                    
                                    <!-- Si c'est un menu, afficher les plats inclus -->
                                    <?php if ($article['type'] === 'menu' && isset($article['plats_inclus'])): ?>
                                        <div class="plats-inclus">
                                            <h4>Plats inclus:</h4>
                                            <ul>
                                                <?php foreach ($article['plats_inclus'] as $plat): ?>
                                                    <li>
                                                        <strong><?php echo htmlspecialchars($plat['nom']); ?></strong>
                                                        <span class="prix-petit"><?php echo number_format($plat['prix'], 2, ',', ' '); ?> €</span>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="article-prix">
                                        <div class="prix-ligne">
                                            <span>Prix unitaire</span>
                                            <span><?php echo number_format($article['prix_unitaire'], 2, ',', ' '); ?> €</span>
                                        </div>
                                        <div class="prix-ligne prix-total-article">
                                            <span>Total article</span>
                                            <span><?php echo number_format($article['prix_total'], 2, ',', ' '); ?> €</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- RÉSUMÉ PRIX -->
                        <div class="prix-summary">
                            <div class="prix-ligne">
                                <span class="label">Sous-total</span>
                                <span class="valeur"><?php echo number_format($details['prix_total_avant_remise'], 2, ',', ' '); ?> €</span>
                            </div>
                            <?php if ($details['remise'] > 0): ?>
                                <div class="prix-ligne remise">
                                    <span class="label">Remise</span>
                                    <span class="valeur">-<?php echo number_format($details['remise'], 2, ',', ' '); ?> €</span>
                                </div>
                            <?php endif; ?>
                            <div class="prix-ligne prix-final">
                                <span class="label">Prix final</span>
                                <span class="valeur"><?php echo number_format($details['prix_apres_remise'], 2, ',', ' '); ?> €</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bouton notation -->
                    <div style="text-align: center; margin-top: 30px;">
                        <?php if ($commande['notation'] === null): ?>
                            <a href="notation.php?id=<?php echo $commande['id']; ?>" class="btn-brutal">NOTER CETTE COMMANDE</a>
                        <?php else: ?>
                            <p style="color: var(--accent); font-weight: bold;">✓ Commande notée</p>
                        <?php endif; ?>
                    </div>

                    <hr style="border: none; border-top: 2px dashed var(--text); margin: 40px 0;">
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </main>

    <?php include '../includes/footer.html'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-modif-qty').forEach(btn => {
                btn.addEventListener('click', function() {
                    const idCommande = this.dataset.cmd;
                    const idProduit = this.dataset.prod;
                    const changement = this.dataset.change; // 1 ou -1

                    const fd = new FormData();
                    fd.append('action', 'modify_order_quantity');
                    fd.append('id_commande', idCommande);
                    fd.append('id_produit', idProduit);
                    fd.append('changement', changement);

                    fetch('ajax_handler.php', { method: 'POST', body: fd })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) {
                            if (data.montant_supplementaire > 0) {
                                alert('Votre commande a été modifiée.\nUn paiement additionnel de ' + data.montant_supplementaire.toFixed(2) + ' € est requis.');
                            } else {
                                alert('Commande modifiée avec succès. Vous bénéficiez d\'un avoir pour la différence.');
                            }
                            location.reload(); // Recharge pour afficher le nouveau panier recalculé
                        } else {
                            alert('Erreur : ' + data.message);
                        }
                    });
                });
            });
        });
    </script>

</body>
</html>