<!DOCTYPE html>
<html lang="fr">

<?php 
    $titre_page = "KØLD | ADMIN";
    include '../includes/head.php';
?>


<body class="kold-mode">

    <?php 
        $nom_page = "admin";
        include '../includes/header.php';
    ?>


    <main class="livraison-container">
        <h1 class="main-title" style="font-size: 3rem;">LIVRAISON<br>EN COURS</h1>

        <div class="delivery-card">
            <div class="delivery-section">
                <div class="delivery-label">COMMANDE_ID</div>
                <div class="delivery-data">#KB-8922</div>
            </div>

            <div class="delivery-section">
                <div class="delivery-label">CLIENT</div>
                <div class="delivery-data">MARC_G</div>
            </div>

            <div class="delivery-section">
                <div class="delivery-label">ADRESSE</div>
                <div class="delivery-data">15 RUE DU CODE, 95000 CERGY</div>
            </div>

            <div class="delivery-section">
                <div class="delivery-label">INFOS_COMPLÉMENTAIRES</div>
                <div class="delivery-data">BÂTIMENT B, INTERPHONE 42</div>
            </div>

            <div class="delivery-section">
                <div class="delivery-label">TÉLÉPHONE</div>
                <div class="delivery-data">06 12 34 56 78</div>
            </div>

            <div class="delivery-actions">
                <a href="https://www.google.com/maps/search/?api=1&query=15+RUE+DU+CODE,+95000+CERGY" target="_blank" class="btn-brutal btn-full" style="text-align: center; text-decoration: none;">OUVRIR GPS</a>
                <button class="btn-brutal btn-full btn-confirm">LIVRAISON EFFECTUÉE</button>
            </div>
        </div>
    </main>

    <?php include '../includes/footer.php'; ?>

</body>
</html>