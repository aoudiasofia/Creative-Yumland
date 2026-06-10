<?php 
session_start();
include '../includes/fonctions.php';

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'livreur')) {
    header("Location: connexion.php");
    exit();
}


$livreur_id = isset($_SESSION['user']) ? intval($_SESSION['user']) : (isset($_SESSION['id']) ? intval($_SESSION['id']) : 0);

$commandes = getToutesLesCommandes();
if (!is_array($commandes)) $commandes = [];

$commandes_attribuees = array_filter($commandes, function($commande) use ($livreur_id) {

    $est_le_bon_livreur = isset($commande['livreur']) && ($commande['livreur'] == $livreur_id);
    
    
    $statut_valide = false;
    if (isset($commande['statut_commande'])) {
        $statut = $commande['statut_commande'];
        $statut_valide = ($statut === 'a livrée' || $statut === 'en livraison');
    }
    
    return $est_le_bon_livreur && $statut_valide;
});
?>

<!DOCTYPE html>
<html lang="fr">
<?php 
    $titre_page = "KØLD | LIVRAISON";
    include '../includes/head.php';
?>


<body class="kold-mode">

    <?php 
        $nom_page = "livraison";
        include '../includes/header.php';
    ?>

    <main class="livraison-container">
        <h1 class="main-title">Mes missions de livraison</h1>

        <?php if (!empty($commandes_attribuees)): ?>
            <div class="delivery-grid">
                <?php foreach ($commandes_attribuees as $commande): ?>
                    <article class="delivery-card">
                        <div class="delivery-header">
                            <h2>Commande #<?php echo htmlspecialchars($commande['id']); ?></h2>
                            <span>Statut : <?php echo htmlspecialchars($commande['statut_commande']); ?></span>
                        </div>
                        <p><strong>Date :</strong> <?php echo htmlspecialchars($commande['date_heure']); ?></p>
                        <p><strong>Adresse :</strong> <?php echo htmlspecialchars($commande['adresse_livraison'] ?: 'Non précisée'); ?></p>
                        <p><strong>Montant payé :</strong> <?php echo number_format($commande['montant_payé'], 2, ',', ' '); ?> €</p>
                        <a href="detail_commande.php?id=<?php echo htmlspecialchars($commande['id']); ?>" class="btn-brutal">Voir le détail</a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Aucune commande en livraison pour le moment.</p>
        <?php endif; ?>

    </main>

    <?php include '../includes/footer.html'; ?>

</body>
</html>