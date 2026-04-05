<?php
session_start();
require_once '../includes/fonctions.php';
initialiserPanier();

if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');
    exit;
}

$user_id = $_SESSION['id'];
$user_info = getInfoUser($user_id);
$total_panier = calculerTotalPanier();
$articles_panier = getPanier();

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
                
                <?php if (empty($articles_panier)): ?>
                    <p>Votre panier est vide.</p>
                <?php else: ?>
                    <div class="panier-items">
                        <?php foreach ($articles_panier as $item): ?>
                            <div class="panier-item">
                                <h3><?php echo htmlspecialchars($item['plat']['nom']); ?></h3>
                                <p>Prix unitaire: <?php echo number_format($item['plat']['prix'], 2); ?> €</p>
                                <p>Quantité: <?php echo $item['quantite']; ?></p>
                                <p>Total: <?php echo number_format($item['prix_total'], 2); ?> €</p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="total-section">
                        <?php $remise = floatval($user_info['remise'] ?? 0); ?>
                        <?php if ($remise > 0): ?>
                            <div class="prix-ligne">
                                <span>Sous-total</span>
                                <span><?php echo number_format($total_panier, 2); ?> €</span>
                            </div>
                            <div class="prix-ligne remise">
                                <span>Remise</span>
                                <span>-<?php echo number_format($remise, 2); ?> €</span>
                            </div>
                        <?php endif; ?>
                        <h2>Total: <?php echo number_format($total_panier - $remise, 2); ?> €</h2>
                    </div>
                    
                    <form action="traitement_validation_panier.php" method="post" class="validation-form">
                        <div class="form-group">
                            <label for="quand">Quand souhaitez-vous être livré ?</label>
                            <div class="radio-group">
                                <label>
                                    <input type="radio" name="quand" value="maintenant" checked> Maintenant
                                </label>
                                <label>
                                    <input type="radio" name="quand" value="plus tard"> Plus tard
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="adresse_livraison">Adresse de livraison</label>
                            <input type="text" id="adresse_livraison" name="adresse_livraison" value="<?php echo htmlspecialchars($user_info['adresse'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="paiement-buttons">
                            <button type="submit" name="paiement" value="cy_bank" class="btn-brutal">PAYER AVEC CY BANK</button>
                            <button type="submit" name="paiement" value="plus_tard" class="btn-brutal">PAYER PLUS TARD</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.html'; ?>

</body>
</html>