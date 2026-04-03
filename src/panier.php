<?php
session_start();
require_once '../includes/fonctions.php';
initialiserPanier();

$total_panier = calculerTotalPanier();
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
    </main>

    <?php include '../includes/footer.php'; ?>

</body>
</html>