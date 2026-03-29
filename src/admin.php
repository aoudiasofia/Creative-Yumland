<?php
session_start(); // UNE SEULE FOIS, TOUT EN HAUT

// 1. PROTECTION : Si pas admin, retour à l'accueil
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: accueil.php");
    exit();
}

// 2. RECUPERATION DES DONNÉES (Attention au nom du fichier : user.json)
$json_path = __DIR__ . '/../data/user.json'; 
$users = [];

if (file_exists($json_path)) {
    $json_data = file_get_contents($json_path);
    $users = json_decode($json_data, true);
    
    // Sécurité au cas où le JSON est vide
    if (!is_array($users)) {
        $users = [];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<?php 
    $titre_page = "KØLD | ADMIN";
    include '../includes/head.php';
?>


<body class="kold-mode">

    <?php 
        $nom_page = "admin";
        include '../includes/header.php';
    ?>

    <main class="admin-container">
        <h1 class="main-title">ADMIN PANEL</h1>

        <div class="admin-section">
            <h2>Gestion des Commandes</h2>
            <p>Gérer les commandes en cours, et assigner les livreurs.</p>
            <a href="restaurant.php" class="btn-brutal">Voir les commandes</a>
        </div>

        <div class="admin-section">
            <h2>Vue Livreur</h2>
            <p>Accéder à la vue des livreurs pour le suivi des livraisons.</p>
            <a href="livreur.php" class="btn-brutal">Voir les livraisons</a>
        </div>


        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>USER_ID</th>
                        <th>IDENTIFIANT</th>
                        <th>EMAIL</th>
                        <th>STATUT</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $u): ?>
                        <tr>
                            <td>#U-<?php echo $u['id']; ?></td>
                            <td><?php echo strtoupper(htmlspecialchars($u['login'])); ?></td>
                            <td><?php echo isset($u['email']) ? strtoupper(htmlspecialchars($u['email'])) : 'NON_RENSEIGNÉ'; ?></td>
                            <td>
                                <?php 
                                    $role = isset($u['role']) ? $u['role'] : 'client';
                                    $statusClass = "status-active";
                                    if ($role === 'livreur') $statusClass = "status-delivery";
                                    if ($role === 'restaurateur') $statusClass = "status-resto";
                                ?>
                                <span class="status-chip <?php echo $statusClass; ?>">
                                    <?php echo strtoupper($role); ?>
                                </span>
                            </td>
                            <td>
                                <a href="profil.php?id=<?php echo $u['id']; ?>" class="btn-brutal btn-small">VOIR</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5">AUCUN UTILISATEUR TROUVÉ DANS <?php echo realpath($json_path); ?></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <?php include '../includes/footer.php'; ?>

</body>
</html>