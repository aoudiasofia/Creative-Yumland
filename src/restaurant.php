<?php
session_start();

$orders_file = '../data/commandes.json';
$orders = [];
if (file_exists($orders_file)) {
    $orders_json = file_get_contents($orders_file);
    $orders = json_decode($orders_json, true);
}

$pending_orders = array_filter($orders, function ($order) {
    return isset($order['status']) && $order['status'] === 'en attente';
});

include '../views/header.php';
?>

<div class="container">
    <h1>Commandes en attente</h1>

    <?php if (empty($pending_orders)): ?>
        <p>Il n'y a aucune commande en attente.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID Commande</th>
                    <th>Client</th>
                    <th>Contenu</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pending_orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['id']); ?></td>
                        <td><?php echo htmlspecialchars($order['user_id']); ?></td>
                        <td>
                            <ul>
                                <?php foreach ($order['items'] as $item): ?>
                                    <li><?php echo htmlspecialchars($item['name']) . ' x ' . htmlspecialchars($item['quantity']); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                        <td><?php echo htmlspecialchars(number_format($order['total'], 2)); ?> €</td>
                        <td>
                            <form action="traitement_commande.php" method="POST">
                                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['id']); ?>">
                                <input type="hidden" name="new_status" value="en préparation">
                                <button type="submit" class="btn btn-primary">Passer en préparation</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include '../views/footer.php'; ?>
