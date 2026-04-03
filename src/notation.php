<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include '../includes/fonctions.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: historique_commandes_client.php");
    exit();
}

$id_commande = intval($_GET['id']);
$commande = getCommandeById($id_commande);

if (!$commande) {
    header("Location: historique_commandes_client.php");
    exit();
}

if ($commande['user_id'] != $_SESSION['user']) {
    header("Location: historique_commandes_client.php");
    exit();
}

$details = calculerDetailCommande($commande);

?>

<!DOCTYPE html>
<html lang="fr">

<?php 
    $titre_page = "KØLD | NOTATION";
    include '../includes/head.php';
?>


<body class="kold-mode">

    <?php 
        $nom_page = "notation";
        include '../includes/header.php';
    ?>

    <main class="notation-container">
        <h1 class="main-title">NOTER VOTRE COMMANDE</h1>

        <div class="notation-card">
            <div class="commande-preview">
                <p><strong>Commande #<?php echo htmlspecialchars($commande['id']); ?></strong> - <?php echo $commande['date_heure']; ?></p>
                <p style="font-size: 0.9rem; color: var(--accent);">Montant total: <?php echo number_format($details['prix_apres_remise'], 2, ',', ' '); ?> €</p>
            </div>

            <form method="post" action="traitement_notation.php" class="notation-form">
                <input type="hidden" name="id_commande" value="<?php echo htmlspecialchars($commande['id']); ?>" />

                <!-- NOTATION -->
                <div class="form-group">
                    <label class="form-label">Notez votre commande (de 1 à 5)</label>
                    <div class="rating-grid">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <label class="rate-box">
                                <input type="radio" name="notation" value="<?php echo $i; ?>" <?php echo $i === 3 ? 'checked' : ''; ?> />
                                <span><?php echo $i; ?></span>
                            </label>
                        <?php endfor; ?>
                    </div>
                </div>

                <!-- COMMENTAIRE -->
                <div class="form-group">
                    <label class="form-label">Commentaire (optionnel)</label>
                    <textarea name="commentaire" class="kold-textarea" placeholder="Partagez votre retour..."></textarea>
                </div>

                <!-- BOUTONS -->
                <div class="notation-actions">
                    <button type="submit" class="btn-brutal">ENVOYER VOTRE NOTE</button>
                    <a href="historique_commandes_client.php" class="btn-retour">← Retour</a>
                </div>
            </form>
        </div>
    </main>

    <?php include '../includes/footer.php'; ?>

</body>
</html>