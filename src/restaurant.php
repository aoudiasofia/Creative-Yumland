<?php
session_start();

// 1. SÉCURITÉ : Vérification du rôle
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'restaurateur' && $_SESSION['role'] !== 'admin')) {
    header("Location: login.php");
    exit();
}

// 2. RÉCUPÉRATION DES COMMANDES
$orders_file = '../data/commandes.json';
$orders = [];
if (file_exists($orders_file)) {
    $orders = json_decode(file_get_contents($orders_file), true) ?? [];
}

// Filtrer uniquement les commandes en attente
$pending_orders = array_filter($orders, function ($order) {
    return isset($order['status']) && $order['status'] === 'en attente';
});

// 3. RÉCUPÉRATION DES LIVREURS (pour le menu déroulant)
$users_file = '../data/user.json';
$livreurs = [];
if (file_exists($users_file)) {
    $all_users = json_decode(file_get_contents($users_file), true) ?? [];
    foreach ($all_users as $u) {
        if (isset($u['role']) && $u['role'] === 'livreur') {
            $livreurs[] = $u;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<?php 
    $titre_page = "KØLD | restaurant";
    include '../includes/head.php';
?>


<body class="kold-mode">

    <?php 
        $nom_page = "restaurant";
        include '../includes/header.php';
    ?>

    <main class="admin-container">
        <h1 class="main-title">GESTION_RESTO // COMMANDES_ENTRANTES</h1>

        <?php if (empty($pending_orders)): ?>
            <div class="profil-box" style="text-align: center; padding: 50px;">
                <p class="label-tech">ZÉRO_COMMANDE_EN_ATTENTE</p>
            </div>
        <?php else: ?>
            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>CLIENT</th>
                            <th>DÉTAILS</th>
                            <th>TOTAL</th>
                            <th>WORKFLOW_ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pending_orders as $order): ?>
                            <tr>
                                <td>#<?php echo htmlspecialchars($order['id']); ?></td>
                                <td><?php echo strtoupper(htmlspecialchars($order['user_id'])); ?></td>
                                <td>
                                    <ul style="list-style: none; padding: 0; font-size: 0.8rem;">
                                        <?php foreach ($order['items'] as $item): ?>
                                            <li><?php echo strtoupper($item['name']) . ' x' . $item['quantity']; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </td>
                                <td style="font-family: 'Archivo Black';"><?php echo number_format($order['total'], 2); ?>€</td>
                                <td>
                                    <form action="traitement_commande.php" method="POST" style="display: flex; flex-direction: column; gap: 10px;">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        
                                        <button type="submit" name="new_status" value="en préparation" class="btn-brutal btn-small" style="background: #00ff00; color: black;">
                                            LANCER_CUISINE
                                        </button>

                                        <div style="display: flex; gap: 5px;">
                                            <select name="livreur_id" class="admin-select" required>
                                                <option value="">CHOISIR_LIVREUR</option>
                                                <?php foreach ($livreurs as $l): ?>
                                                    <option value="<?php echo $l['login']; ?>">
                                                        <?php echo strtoupper($l['login']); ?> (DISPO)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button type="submit" name="new_status" value="en cours" class="btn-brutal btn-small" style="background: var(--accent);">
                                                OK
                                            </button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </main>

    <?php include '../includes/footer.php'; ?>

</body>
</html>