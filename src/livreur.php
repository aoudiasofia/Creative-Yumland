<?php 
session_start(); 
include '../includes/fonctions.php';

// Seul le livreur ou l'admin peut accéder à cette page
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'livreur' && $_SESSION['role'] !== 'admin')) {
    header("Location: connexion.php");
    exit();
}

//RÉCUPÉRATION DES COMMANDES
//  la même fonction que pour le restaurateur
$commandes = getToutesLesCommandes();

$commandes_a_livrer = [];
$mes_livraisons_en_cours = [];

foreach ($commandes as $commande) {
    // Commandes prêtes en attente d'un coursier
    if ($commande['statut_commande'] === 'a livrée') {
        $commandes_a_livrer[] = $commande;
    }
    // Commandes que CE livreur est en train de livrer
    // (Optionnel : si tu stockes l'id du livreur dans la commande, sinon on affiche toutes celles "en livraison")
    if ($commande['statut_commande'] === 'en livraison') {
        $mes_livraisons_en_cours[] = $commande;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<?php 
    $titre_page = "KØLD | ESPACE LIVREUR";
    include '../includes/head.php';
?>

<body class="kold-mode">

    <?php 
        $nom_page = "livreur";
        include '../includes/header.php';
    ?>

    <main style="max-width: 1200px; margin: 0 auto; padding: 40px 20px;">
        
        <h1 style="font-family: 'Inter', sans-serif; font-weight: 900; font-size: 2.5rem; text-transform: uppercase; border-bottom: 5px solid #000; padding-bottom: 10px; margin-bottom: 30px;">
            🚴 ESPACE LIVREUR (COURSES LIVE)
        </h1>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: start;">
            
            <div style="border: 4px solid #000; background: #fff; padding: 25px; box-shadow: 8px 8px 0px #000;">
                <h2 style="font-weight: 900; text-transform: uppercase; font-size: 1.3rem; margin-top: 0; border-bottom: 2px solid #000; padding-bottom: 5px; margin-bottom: 20px; background: #ffcc00; padding: 5px;">
                    📦 COMMANDES DISPONIBLES (<?= count($commandes_a_livrer) ?>)
                </h2>

                <?php if (empty($commandes_a_livrer)): ?>
                    <p style="font-style: italic; color: #666;">Aucune commande n'est prête pour le moment. Attends que le restaurant valide les plats !</p>
                <?php else: ?>
                    <div style="display: flex; flex-direction: column; gap: 15px;">
                        <?php foreach ($commandes_a_livrer as $cmd): ?>
                            <div style="border: 3px solid #000; padding: 15px; background: #fafafa;">
                                <p><strong>ID Commande :</strong> #<?= htmlspecialchars($cmd['id']) ?></p>
                                <p><strong>Heure :</strong> <?= htmlspecialchars($cmd['date_heure']) ?></p>
                                <p><strong>Montant :</strong> <?= htmlspecialchars($cmd['montant_payé']) ?> €</p>
                                
                                <a href="traitement_statut.php?id=<?= $cmd['id'] ?>&nouveau_statut=en livraison" 
                                   style="display: block; text-align: center; margin-top: 10px; text-decoration: none; border: 3px solid #000; padding: 8px; background: #00ff66; color: #000; font-weight: 900; box-shadow: 3px 3px 0px #000; text-transform: uppercase; font-size: 0.9rem;">
                                    🚴 Prendre cette course
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div style="border: 4px solid #000; background: #fff; padding: 25px; box-shadow: 8px 8px 0px #000;">
                <h2 style="font-weight: 900; text-transform: uppercase; font-size: 1.3rem; margin-top: 0; border-bottom: 2px solid #000; padding-bottom: 5px; margin-bottom: 20px; background: #0055ff; color: #fff; padding: 5px;">
                    ⚡ EN COURS DE LIVRAISON (<?= count($mes_livraisons_en_cours) ?>)
                </h2>

                <?php if (empty($mes_livraisons_en_cours)): ?>
                    <p style="font-style: italic; color: #666;">Tu n'as aucune livraison en cours de route.</p>
                <?php else: ?>
                    <div style="display: flex; flex-direction: column; gap: 15px;">
                        <?php foreach ($mes_livraisons_en_cours as $cmd): ?>
                            <div style="border: 3px solid #000; padding: 15px; background: #fafafa;">
                                <p><strong>ID Commande :</strong> #<?= htmlspecialchars($cmd['id']) ?></p>
                                <p><strong>Destination :</strong> Campus CY Tech (ou adresse du JSON)</p>
                                <p><strong>Montant à vérifier :</strong> <?= htmlspecialchars($cmd['montant_payé']) ?> €</p>
                                
                                <a href="traitement_statut.php?id=<?= $cmd['id'] ?>&nouveau_statut=terminée" 
                                   style="display: block; text-align: center; margin-top: 10px; text-decoration: none; border: 3px solid #000; padding: 8px; background: #fff; color: #000; font-weight: 900; box-shadow: 3px 3px 0px #000; text-transform: uppercase; font-size: 0.9rem;">
                                    🏁 Marquer comme LIVRÉE
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>

    </main>

    <?php include '../includes/footer.html'; ?>
    
</body>
</html>