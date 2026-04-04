<?php
session_start();

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'restaurateur' && $_SESSION['role'] !== 'admin')) {
    header("Location: connexion.php");
    exit();
}

include '../includes/fonctions.php';

$commandes = getToutesLesCommandes();
$livreurs = getTousLesLivreurs();

// Grouper les commandes par statut
$grouped_commandes = [];
foreach ($commandes as $commande) {
    $statut = $commande['statut_commande'];
    if (!isset($grouped_commandes[$statut])) {
        $grouped_commandes[$statut] = [];
    }
    $grouped_commandes[$statut][] = $commande;
}

?>

<!DOCTYPE html>
<html lang="fr">
<?php 
    $titre_page = "KØLD | restaurant";
    include '../includes/head.html';
?>

<body class="kold-mode">

    <?php 
        $nom_page = "restaurant";
        include '../includes/header.php';
    ?>

    <main class="restaurant-container">
        <h1>Gestion des Commandes</h1>
        
        <?php
        $statuts = ["en attente", "a livrée", "en livraison", "terminée", "abandonnée"];
        
        foreach ($statuts as $statut) {
            if (isset($grouped_commandes[$statut]) && !empty($grouped_commandes[$statut])) {
                echo "<section class='commandes-section'>";
                echo "<h2>Commandes " . htmlspecialchars($statut) . "</h2>";
                echo "<div class='commandes-list'>";
                
                foreach ($grouped_commandes[$statut] as $commande) {
                    echo "<div class='commande-item'>";
                    echo "<p><strong>ID Commande:</strong> " . htmlspecialchars($commande['id']) . "</p>";
                    echo "<p><strong>Date:</strong> " . htmlspecialchars($commande['date_heure']) . "</p>";
                    echo "<p><strong>Livraison:</strong> " . ($commande['quand'] === 'maintenant' ? 'Dès que possible' : 'Plus tard') . "</p>";
                    echo "<p><strong>Montant:</strong> " . htmlspecialchars($commande['montant_payé']) . " €</p>";
                    echo "<a href='detail_commande.php?id=" . $commande['id'] . "' class='btn-detail'>Voir le détail</a>";
                    echo "</div>";
                }
                
                echo "</div>";
                echo "</section>";
            }
        }
        ?>
    </main>

    <?php include '../includes/footer.html'; ?>

</body>
</html>