<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$current_user_id = $_SESSION['id'];
$orders_file = '../data/commandes.json';
$all_orders = [];

if (file_exists($orders_file)) {
    $orders_json = file_get_contents($orders_file);
    // Nettoyage des conflits Git si présents
    $orders_json = preg_replace('/^<<<<<<< .*?^=======\s*|>>>>>>> .*/ms', '', $orders_json);
    $all_orders = json_decode($orders_json, true) ?? [];
}

$user_orders = [];
if (is_array($all_orders)) {
    $user_orders = array_filter($all_orders, function ($order) use ($current_user_id) {
        return isset($order['user_id']) && $order['user_id'] == $current_user_id;
    });
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KØLD | ARCHIVES_COMMANDES</title>
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
                <?php if (isset($_SESSION['user'])): ?>
                    <li><a href="mes_commandes.php" class="nav-active">Mes Commandes</a></li>
                    <li class="user-status" style="color: var(--accent);">ID: <?php echo strtoupper(htmlspecialchars($_SESSION['user'])); ?></li>
                    <li><a href="logout.php" class="nav-login">DÉCONNEXION</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main class="admin-container"> <h1 class="main-title">HISTORIQUE_FLUX</h1>

        <div class="admin-table-wrapper">
            <?php if (empty($user_orders)): ?>
                <div class="profil-box" style="text-align: center; padding: 40px;">
                    <p class="label-tech">ZÉRO_TRANSACTION_TROUVÉE</p>
                    <a href="presentation.php" class="btn-brutal" style="display: inline-block; margin-top: 20px; text-decoration: none;">PASSER_COMMANDE</a>
                </div>
            <?php else: ?>
                <table class="admin-table"> <thead>
                        <tr>
                            <th>ID_COMMANDE</th>
                            <th>DATE</th>
                            <th>STATUT</th>
                            <th>DÉTAILS_CONTENU</th>
                            <th>TOTAL</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($user_orders as $order): ?>
                            <tr>
                                <td style="font-weight: bold;">#<?php echo htmlspecialchars($order['id']); ?></td>
                                <td><?php echo htmlspecialchars($order['date'] ?? '---'); ?></td>
                                <td>
                                    <?php 
                                        $status = strtolower($order['status'] ?? 'en attente');
                                        $color = ($status === 'livré') ? '#00ff00' : (($status === 'abandonné') ? '#ff0000' : 'var(--accent)');
                                    ?>
                                    <span style="color: <?php echo $color; ?>; font-weight: bold; text-transform: uppercase; font-size: 0.8rem;">
                                        ● <?php echo htmlspecialchars($status); ?>
                                    </span>
                                </td>
                                <td>
                                    <ul style="list-style: none; padding: 0; font-size: 0.75rem;">
                                        <?php foreach ($order['items'] as $item): ?>
                                            <li>- <?php echo strtoupper(htmlspecialchars($item['name'])); ?> [x<?php echo $item['quantity']; ?>]</li>
                                        <?php endforeach; ?>
                                    </ul>
                                </td>
                                <td style="font-family: 'Archivo Black';">
                                    <?php echo number_format($order['total'], 2); ?>€
                                </td>
                                <td>
                                    <?php if ($status === 'livré'): ?>
                                        <a href="notation.php?order=<?php echo $order['id']; ?>" class="btn-brutal btn-small" style="background: var(--accent); color: white; text-decoration: none;">NOTER</a>
                                    <?php else: ?>
                                        <span style="opacity: 0.3;">---</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>

    <footer class="kold-footer">
        <p> KØLD // USER_ARCHIVE_UNIT - 2025-2026</p>
    </footer>

</body>
</html>