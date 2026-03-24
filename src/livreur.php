<?php
session_start();

$orders_file = '../data/commandes.json';
$orders = [];
if (file_exists($orders_file)) {
    $orders_json = file_get_contents($orders_file);
    $orders_json = preg_replace('/^<<<<<<< .*?^=======\s*|>>>>>>> .*/ms', '', $orders_json);
    $orders = json_decode($orders_json, true);
}

$delivery_orders = array_filter($orders, function ($order) {
    return isset($order['status']) && $order['status'] === 'en préparation';
});

$users_file = '../data/user.json';
$users = [];
if (file_exists($users_file)) {
    $users_json = file_get_contents($users_file);
    $users = json_decode($users_json, true);
    $users_by_id = array_column($users, null, 'id');
}

include '../views/header.php'; 
?>

<link rel="stylesheet" href="style.css">
<body class="kold-mode">
    <div class="livraison-container">
        <h1 class="main-title" style="font-size: 3rem;">LIVRAISONS<br>À EFFECTUER</h1>

        <?php if (empty($delivery_orders)): ?>
            <div class="delivery-card">
                <p>Aucune livraison en attente pour le moment.</p>
            </div>
        <?php else: ?>
            <?php foreach ($delivery_orders as $order): ?>
                <div class="delivery-card">
                    <div class="delivery-section">
                        <div class="delivery-label">COMMANDE_ID</div>
                        <div class="delivery-data">#<?php echo htmlspecialchars($order['id']); ?></div>
                    </div>

                    <div class="delivery-section">
                        <div class="delivery-label">CLIENT</div>
                        <?php 
                            $client_name = "CLIENT_INCONNU";
                            if (isset($order['user_id']) && isset($users_by_id[$order['user_id']])) {
                                $client_name = strtoupper(htmlspecialchars($users_by_id[$order['user_id']]['login']));
                            }
                        ?>
                        <div class="delivery-data"><?php echo $client_name; ?></div>
                    </div>

                    <div class="delivery-section">
                        <div class="delivery-label">ADRESSE</div>
                        <?php 
                            $address = isset($order['adresse_livraison']) ? htmlspecialchars($order['adresse_livraison']) : "15 RUE DU CODE, 95000 CERGY";
                        ?>
                        <div class="delivery-data"><?php echo $address; ?></div>
                    </div>

                    <div class="delivery-actions">
                        <a href="https:
                        <form action="traitement_livraison.php" method="POST" style="margin:0;">
                            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['id']); ?>">
                            <input type="hidden" name="new_status" value="livrée">
                            <button type="submit" class="btn-brutal btn-full btn-confirm">LIVRAISON EFFECTUÉE</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>

<?php include '../views/footer.php'; 