<?php
session_start();
include '../includes/fonctions.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: accueil.php");
    exit();
}

$utilisateurs = getTousLesUtilisateurs();
$user_id_detail = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;
$user_detail = null;

if ($user_id_detail) {
    foreach ($utilisateurs as $user) {
        if ($user['id'] == $user_id_detail) {
            $user_detail = $user;
            break;
        }
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

        <!-- AFFICHAGE DETAIL D'UN UTILISATEUR -->
        <?php if ($user_detail): ?>
            <div class="user-detail-section">
                <a href="admin.php" class="btn btn-secondary">← Retour à la liste</a>
                
                <div class="user-profile-card">
                    <h2><?php echo htmlspecialchars($user_detail['prenom'] . ' ' . $user_detail['nom']); ?></h2>
                    
                    <div class="user-info">
                        <p><strong>ID:</strong> <?php echo $user_detail['id']; ?></p>
                        <p><strong>Prénom:</strong> <?php echo htmlspecialchars($user_detail['prenom']); ?></p>
                        <p><strong>Nom:</strong> <?php echo htmlspecialchars($user_detail['nom']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($user_detail['email']); ?></p>
                        <p><strong>Téléphone:</strong> <?php echo htmlspecialchars($user_detail['tel']); ?></p>
                        <p><strong>Rôle:</strong> <?php echo htmlspecialchars($user_detail['role']); ?></p>
                        <p><strong>Adresse:</strong> <?php echo htmlspecialchars($user_detail['adresse']); ?></p>
                        <p><strong>Informations supplémentaires:</strong> <?php echo htmlspecialchars($user_detail['infosup'] ?? ''); ?></p>
                        <p><strong>Statut:</strong> <?php echo htmlspecialchars($user_detail['statut']); ?></p>
                        <p><strong>Remise:</strong> <?php echo htmlspecialchars($user_detail['remise']); ?>%</p>
                        <p><strong>Bloqué:</strong> <?php echo $user_detail['bloqué'] ? 'OUI' : 'NON'; ?></p>
                    </div>

                    <div class="user-actions">
                        <h3>Actions</h3>
                        
                        <div class="action-group">
                            <label>Rôle:</label>
                            <form method="POST" style="display: inline;">
                                <select name="new_role" required>
                                    <option value="">-- Sélectionner un rôle --</option>
                                    <option value="client" <?php echo $user_detail['role'] === 'client' ? 'selected' : ''; ?>>Client</option>
                                    <option value="livreur" <?php echo $user_detail['role'] === 'livreur' ? 'selected' : ''; ?>>Livreur</option>
                                    <option value="admin" <?php echo $user_detail['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                    <option value="restaurateur" <?php echo $user_detail['role'] === 'restaurateur' ? 'selected' : ''; ?>>Restaurateur</option>
                                </select>
                                <button type="submit" class="btn btn-primary">Modifier le rôle</button>
                            </form>
                        </div>

                        <div class="action-group">
                            <label>Statut:</label>
                            <form method="POST" action="" style="display: inline;">
                                <select name="new_statut" required>
                                    <option value="">-- Sélectionner un statut --</option>
                                    <option value="Moldu" <?php echo $user_detail['statut'] === 'Moldu' ? 'selected' : ''; ?>>Moldu</option>
                                    <option value="Premium" <?php echo $user_detail['statut'] === 'Premium' ? 'selected' : ''; ?>>Premium</option>
                                    <option value="VIP" <?php echo $user_detail['statut'] === 'VIP' ? 'selected' : ''; ?>>VIP</option>
                                </select>
                                <button type="submit" class="btn btn-primary">Modifier le statut</button>
                            </form>
                        </div>

                        <div class="action-group">
                            <label>Remise (%):</label>
                            <form method="POST" action="" style="display: inline;">
                                <input type="number" name="new_remise" min="0" max="100" value="<?php echo htmlspecialchars($user_detail['remise']); ?>" required>
                                <button type="submit" class="btn btn-primary">Modifier la remise</button>
                            </form>
                        </div>

                        <div class="action-group">
                            <label>Compte:</label>
                            <?php if ($user_detail['bloqué']): ?>
                                <button class="btn btn-success">✓ Débloquer le compte</button>
                            <?php else: ?>
                                <button class="btn btn-danger">✗ Bloquer le compte</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        <!-- AFFICHAGE DE LA LISTE -->
        <?php else: ?>
            <div class="users-list-section">
                <h2>Gestion des utilisateurs (<?php echo count($utilisateurs); ?>)</h2>
                
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Prénom</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                            <th>Remise</th>
                            <th>Bloqué</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($utilisateurs as $user): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo htmlspecialchars($user['prenom']); ?></td>
                                <td><?php echo htmlspecialchars($user['nom']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['role']); ?></td>
                                <td><?php echo htmlspecialchars($user['statut']); ?></td>
                                <td><?php echo htmlspecialchars($user['remise']); ?>%</td>
                                <td><?php echo $user['bloqué'] ? '✗ OUI' : '✓ NON'; ?></td>
                                <td>
                                    <a href="admin.php?user_id=<?php echo $user['id']; ?>" class="btn btn-sm btn-info">Voir profil</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    </main>

    <?php include '../includes/footer.html'; ?>

</body>
</html>