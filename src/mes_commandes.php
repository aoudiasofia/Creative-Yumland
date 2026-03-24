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
    $orders_json = preg_replace('/^<<<<<<< .*?^=======\s*|>>>>>>> .*/ms', '', $orders_json);
    $all_orders = json_decode($orders_json, true);
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
    <title>KØLD | Mes Commandes</title>
    <link rel="stylesheet" href="style.css">
    <link href="https:
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
                    <li><a href="mes_commandes.php">Mes Commandes</a></li>
                    <li class="user-status">ID: <?php echo strtoupper(htmlspecialchars($_SESSION['user'])); ?></li>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <li><a href="admin.php" style="color: #ff0055; font-weight: bold;">[PANNEAU_ADMIN]</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php" class="nav-login">Déconnexion</a></li>
                <?php else: ?>
                    <li class="user-status">STATUT: INVITÉ</li>
                    <li><a href="login.php" class="nav-login">Connexion</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main class="container" style="padding-top: 40px; padding-bottom: 40px;">
        <h1 class="main-title">Mes Commandes</h1>

        <?php if (empty($user_orders)): ?>
            <p>Vous n'avez encore passé aucune commande.</p>
            <p style="font-style: italic; color: #888;">(Note: This requires a 'user_id' in commandes.json for orders to be displayed.)</p>
        <?php else: ?>
            <table class="table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">ID Commande</th>
                        <th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Date</th>
                        <th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Total</th>
                        <th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Statut</th>
                        <th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Détails</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($user_orders as $order): ?>
                        <tr>
                            <td style="padding: 8px; border-bottom: 1px solid #ddd;"><?php echo htmlspecialchars($order['id']); ?></td>
                            <td style="padding: 8px; border-bottom: 1px solid #ddd;"><?php echo htmlspecialchars($order['date']); ?></td>
                            <td style="padding: 8px; border-bottom: 1px solid #ddd;"><?php echo htmlspecialchars(number_format($order['total'], 2)); ?> €</td>
                            <td style="padding: 8px; border-bottom: 1px solid #ddd;"><?php echo htmlspecialchars($order['status']); ?></td>
                            <td style="padding: 8px; border-bottom: 1px solid #ddd;">
                                <?php if (!empty($order['items'])): ?>
                                <ul>
                                    <?php foreach ($order['items'] as $item): ?>
                                        <li><?php echo htmlspecialchars($item['name']) . ' x ' . htmlspecialchars($item['quantity']); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>

    <footer class="kold-footer">
        <p> KØLD 
    </footer>

</body>
</html>