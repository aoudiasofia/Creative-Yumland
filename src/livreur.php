<?php
session_start();

// 1. SÉCURITÉ : Seul le livreur ou l'admin passe
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'livreur' && $_SESSION['role'] !== 'admin')) {
    header("Location: login.php");
    exit();
}

// 2. RÉCUPÉRATION DES COMMANDES
$file_path = '../data/commandes.json';
$orders = [];
if (file_exists($file_path)) {
    $orders = json_decode(file_get_contents($file_path), true) ?? [];
}

// 3. FILTRAGE : Uniquement les commandes prêtes ou en cours
$deliveries = array_filter($orders, function($o) {
    return isset($o['status']) && in_array(strtolower($o['status']), ['en préparation', 'en cours']);
});
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KØLD | DELIVERY_UNIT</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
</head>

<body class="kold-mode">

    <header class="main-header">
        <div class="logo">
            <a href="accueil.php" style="text-decoration: none; color: inherit;">KØLD</a>
        </div>
        <nav>
            <ul>
                <li><a href="presentation.php">La Carte</a></li>
                <li><a href="profil.php" class="nav-login"><?php echo strtoupper($_SESSION['user']); ?></a></li>
            </ul>
        </nav>
    </header>

    <main class="admin-container">
        <h1 class="main-title">MISSIONS_LIVRAISON</h1>

        <?php if (empty($deliveries)): ?>
            <div class="profil-box" style="text-align: center; padding: 50px;">
                <p class="label-tech" style="color: #666;">ZÉRO_MISSION_EN_ATTENTE</p>
            </div>
        <?php else: ?>
            <div class="profil-grid"> 
                <?php foreach ($deliveries as $o): ?>
                    <div class="profil-box" style="border-color: var(--accent);">
                        
                        <div class="box-header" style="background: var(--text); color: var(--bg);">
                            MISSION #<?php echo htmlspecialchars($o['id']); ?>
                        </div>

                        <div class="info-item" style="margin-top: 15px;">
                            <span>CLIENT : <b style="color: white;"><?php echo strtoupper(htmlspecialchars($o['user_id'])); ?></b></span>
                        </div>

                        <div class="info-item">
                            <span>STATUT : <b style="color: var(--accent);"><?php echo strtoupper(htmlspecialchars($o['status'])); ?></b></span>
                        </div>

                        <div style="margin: 15px 0; padding: 10px; border: 2px solid #333; background: #000; font-size: 0.8rem;">
                            <p style="margin: 0 0 10px 0; color: #666; font-weight: bold;">[ BORDEREAU_CONTENU ]</p>
                            <?php if (!empty($o['items'])): ?>
                                <ul style="list-style: none; padding: 0; margin: 0;">
                                    <?php foreach ($o['items'] as $item): ?>
                                        <li>- <?php echo strtoupper(htmlspecialchars($item['name'])); ?> [x<?php echo $item['quantity']; ?>]</li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p style="margin:0;">CONTENU_MASQUÉ</p>
                            <?php endif; ?>
                        </div>

                        <form action="traitement_commande.php" method="POST" style="margin-top: 20px; display: flex; gap: 10px;">
                            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($o['id']); ?>">

                            <button type="submit" name="new_status" value="livré" class="btn-brutal" style="background: #00ff00; color: black; flex: 1; padding: 12px; font-size: 0.9rem;">
                                VALIDER
                            </button>

                            <button type="submit" name="new_status" value="abandonné" class="btn-brutal" style="background: #ff0000; color: white; flex: 1; padding: 12px; font-size: 0.9rem;">
                                ÉCHEC
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <footer class="kold-footer">
        <p> KØLD // LOGISTICS_UNIT - 2025-2026</p>
    </footer>

</body>
</html>