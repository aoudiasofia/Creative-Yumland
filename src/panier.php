<?php
session_start();
require_once '../includes/fonctions.php';
initialiserPanier();

$message_systeme = ""; 

// Action : vider le panier
if (isset($_GET['action']) && $_GET['action'] === 'vider') {
    viderPanier();
    header('Location: panier.php');
    exit;
}

// Action : payer la commande
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'payer') {
    $total_a_payer = calculerTotalPanier();
    
    if ($total_a_payer > 0) {
        $reference_commande = uniqid('KB-');
        
        $type_preparation = isset($_POST['preparation']) ? $_POST['preparation'] : 'immediat';

        $paiement_accepte = true; 

        if ($paiement_accepte) {
            $message_systeme = "TRANSACTION CYBANK VALIDÉE ! VOTRE COMMANDE A ÉTÉ ENVOYÉE EN CUISINE.";
            
            $nouvelle_commande = [
                'id_commande' => $reference_commande,
                'date' => date('Y-m-d H:i:s'),
                'preparation' => $type_preparation,
                'total' => $total_a_payer,
                'statut' => 'en_attente',
                'articles' => $_SESSION['panier']
            ];

            $chemin_commandes = '../data/commandes.json';
            
            if (file_exists($chemin_commandes)) {
                $commandes_existantes = json_decode(file_get_contents($chemin_commandes), true);
            } else {
                $commandes_existantes = [];
            }
            
            $commandes_existantes[] = $nouvelle_commande;
            
            file_put_contents($chemin_commandes, json_encode($commandes_existantes, JSON_PRETTY_PRINT));
            
            viderPanier(); 
        } else {
            $message_systeme = "ÉCHEC DE LA TRANSACTION CYBANK. VEUILLEZ RÉESSAYER.";
        }
    }
}

$total_panier = calculerTotalPanier();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KØLD | PANIER</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Space+Mono:wght@400;700&display=swap"
        rel="stylesheet">
</head>

<body class="kold-mode">

    <header class="main-header">
        <div class="logo">
            <a href="accueil.php" style="text-decoration: none; color: inherit;">KØLD</a>
        </div>
        <nav>
            <ul>
                <li><a href="presentation.php">La Carte</a></li>
                <li><a href="inscription.php">Inscription</a></li>
                <li><a href="login.php">Connexion</a></li>
                <li><a href="panier.php" class="active" style="color: var(--white); background: var(--text);">PANIER (<?= number_format($total_panier, 2, '.', ' ') ?> €)</a></li>
            </ul>
        </nav>
    </header>

    <main class="profil-container" style="max-width: 900px; margin: 0 auto;">
        <h1 class="main-title" style="font-size: 3rem; text-align: left;">RÉCAPITULATIF</h1>

        <?php if (!empty($message_systeme)): ?>
            <div style="background: #c8e6c9; color: #2e7d32; padding: 20px; font-family: 'Archivo Black', sans-serif; margin-bottom: 30px; border: var(--main-border); box-shadow: 8px 8px 0px var(--text);">
                <?= htmlspecialchars($message_systeme) ?>
            </div>
        <?php endif; ?>

        <?php if (empty($_SESSION['panier'])): ?>
            <div class="profil-box" style="text-align: center; padding: 60px;">
                <h2 style="font-family: 'Archivo Black', sans-serif; margin-bottom: 20px;">VOTRE PANIER EST FROID (ET VIDE)</h2>
                <a href="presentation.php" class="btn-brutal">RETOURNER À LA CARTE</a>
            </div>
        <?php else: ?>

            <div style="background: var(--white); border: var(--main-border); box-shadow: 15px 15px 0px var(--accent); overflow: hidden; margin-bottom: 40px;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead style="background: var(--bg); border-bottom: 2px solid var(--text);">
                        <tr>
                            <th style="padding: 15px; font-family: 'Archivo Black', sans-serif;">PRODUIT</th>
                            <th style="padding: 15px; text-align: center; font-family: 'Archivo Black', sans-serif;">PRIX UNITAIRE</th>
                            <th style="padding: 15px; text-align: center; font-family: 'Archivo Black', sans-serif;">QUANTITÉ</th>
                            <th style="padding: 15px; text-align: right; font-family: 'Archivo Black', sans-serif;">SOUS-TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach ($_SESSION['panier'] as $id_produit => $quantite): 
                            $plat = getPlatById($id_produit);
                            if ($plat): 
                                $sous_total = $plat['prix'] * $quantite;
                        ?>
                        <tr style="border-bottom: 1px solid var(--bg);">
                            <td style="padding: 15px; font-weight: bold;"><?= htmlspecialchars($plat['nom']) ?></td>
                            <td style="padding: 15px; text-align: center;"><?= number_format($plat['prix'], 2, '.', ' ') ?> €</td>
                            <td style="padding: 15px; text-align: center;"><?= $quantite ?></td>
                            <td style="padding: 15px; text-align: right; color: var(--accent); font-weight: bold;"><?= number_format($sous_total, 2, '.', ' ') ?> €</td>
                        </tr>
                        <?php 
                            endif; 
                        endforeach; 
                        ?>
                    </tbody>
                    <tfoot>
                        <tr style="border-top: var(--border-weight) solid var(--text); background: var(--bg);">
                            <td colspan="3" style="padding: 20px; text-align: right; font-family: 'Archivo Black', sans-serif; font-size: 1.2rem;">TOTAL À PAYER</td>
                            <td style="padding: 20px; text-align: right; font-family: 'Archivo Black', sans-serif; font-size: 1.5rem; color: var(--text);"><?= number_format($total_panier, 2, '.', ' ') ?> €</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <form method="POST" action="panier.php" style="margin: 0; display: flex; flex-direction: column; align-items: flex-end; gap: 20px;">
                <input type="hidden" name="action" value="payer">
                
                <div style="background: var(--white); padding: 20px; border: var(--main-border); width: 100%; max-width: 400px; cursor: url('../images/moufle.png'), auto !important;">
                    <label class="label-tech" style="margin-bottom: 20px; cursor: url('../images/moufle.png'), auto !important;">PRÉPARATION :</label>
                    <div style="display: flex; flex-direction: column; gap: 20px;">
                        <label style="display: flex; align-items: center; font-size: 1.1rem; font-weight: bold; cursor: url('../images/moufle.png'), auto !important;">
                            <input type="radio" name="preparation" value="immediat" style="transform: scale(1.8); margin-right: 15px; margin-left: 10px; cursor: url('../images/moufle.png'), auto !important;" checked> 
                            DÈS QUE POSSIBLE
                        </label>
                        <label style="display: flex; align-items: center; font-size: 1.1rem; font-weight: bold; cursor: url('../images/moufle.png'), auto !important;">
                            <input type="radio" name="preparation" value="plus_tard" style="transform: scale(1.8); margin-right: 15px; margin-left: 10px; cursor: url('../images/moufle.png'), auto !important;"> 
                            POUR PLUS TARD (PRÉCOMMANDE)
                        </label>
                    </div>
                </div>

                <div style="display: flex; gap: 20px;">
                    <a href="panier.php?action=vider" class="btn-brutal" style="background: var(--white); color: var(--text);">VIDER LE PANIER</a>
                    <button type="submit" class="btn-brutal">PAYER (API CYBANK)</button>
                </div>
            </form>

        <?php endif; ?>

    </main>

    <footer class="kold-footer" style="margin-top: 80px;">
        <p> KØLD // PROJET PREING2 - 2025-2026</p>
    </footer>

</body>

</html>