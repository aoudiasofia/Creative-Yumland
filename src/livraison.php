<?php
session_start();

// 1. SÉCURITÉ : Seul le livreur
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'livreur')) {
    header("Location: connexion.php");
    exit();
}

include '../includes/fonctions.php';

$livreur_id = isset($_SESSION['id']) ? intval($_SESSION['id']) : 0;
$commandes = getToutesLesCommandes();
$commandes_attribuees = array_filter($commandes, function($commande) use ($livreur_id) {
    return isset($commande['livreur']) && $commande['livreur'] === $livreur_id && isset($commande['statut_commande']) && $commande['statut_commande'] === 'en livraison';
});
?>

<!DOCTYPE html>
<html lang="fr">
<?php 
    $titre_page = "KØLD | Livraison";
    include '../includes/head.html';
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