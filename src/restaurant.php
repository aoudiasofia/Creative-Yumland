<?php
session_start();
include '../includes/fonctions.php';

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'restaurateur' && $_SESSION['role'] !== 'admin')) {
    header("Location: connexion.php");
    exit();
}

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
    $titre_page = "KØLD | RESTAURANT";
    include '../includes/head.php';
?>

<body class="kold-mode">

    <?php 
        $nom_page = "restaurant";
        include '../includes/header.php';
    ?>

    <main class="restaurant-container">
        <h1>Gestion des Commandes</h1>

        <div style="margin: 20px 0 40px 0;">
            <a href="restaurant_carte.php" class="btn-brutal" style="display: inline-block; background: #000; color: #fff; padding: 15px 25px; font-weight: 900; text-transform: uppercase; text-decoration: none; border: 3px solid #000; box-shadow: 5px 5px 0px #fff;">
                ⚙️ GÉRER LA CARTE ET LES PLATS 
            </a>
        </div>
        
        <?php
        $statuts = ["en attente", "a livrée", "en livraison", "terminée", "abandonnée"];
        
        foreach ($statuts as $statut) {
            if (isset($grouped_commandes[$statut]) && !empty($grouped_commandes[$statut])) {
                echo "<section class='commandes-section'>";
                echo "<h2>Commandes " . htmlspecialchars($statut) . "</h2>";
                echo "<div class='commandes-list'>";
                
                foreach ($grouped_commandes[$statut] as $commande) {
                    echo "<div class='commande-item' style='border: 3px solid #000; padding: 15px; background: #fff; box-shadow: 4px 4px 0px #000; margin-bottom: 15px;'>";
                    echo "<p><strong>ID Commande:</strong> " . htmlspecialchars($commande['id']) . "</p>";
                    echo "<p><strong>Date:</strong> " . htmlspecialchars($commande['date_heure']) . "</p>";
                    echo "<p><strong>Livraison:</strong> " . ($commande['quand'] === 'maintenant' ? 'Dès que possible' : 'Plus tard') . "</p>";
                    echo "<p><strong>Montant:</strong> " . htmlspecialchars($commande['montant_payé']) . " €</p>";
    
                    echo "<div style='display: flex; gap: 10px; margin-top: 10px;'>";
                    // Bouton pour voir les détails
                    echo "<a href='detail_commande.php?id=" . $commande['id'] . "' class='btn-detail' style='text-decoration: none; border: 2px solid #000; padding: 5px 10px; background: #fff; color: #000; font-weight: bold; font-size: 0.85rem;'>Détails</a>";

                    // BOUTONS SELON LE STATUT
                    if ($statut === 'en attente') {
                        echo "<a href='traitement_statut.php?id=" . $commande['id'] . "&nouveau_statut=a livrée' style='text-decoration: none; border: 2px solid #000; padding: 5px 10px; background: #00ff66; color: #000; font-weight: 900; font-size: 0.85rem; box-shadow: 2px 2px 0px #000;'>✔️ PRÊTE (À LIVRER)</a>";
                    } elseif ($statut === 'a livrée') {
                        echo "<span style='font-size: 0.85rem; color: #666; font-style: italic; align-self: center;'>⏳ En attente d'un livreur...</span>";
                    } elseif ($statut === 'en livraison') {
                        echo "<span style='font-size: 0.85rem; color: #0055ff; font-weight: bold; align-self: center;'>🚴 En cours de livraison</span>";
                    }
                    echo "</div>";
    
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