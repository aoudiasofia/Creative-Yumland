<?php
session_start(); // UNE SEULE FOIS, TOUT EN HAUT

// 1. PROTECTION : Si pas admin, retour à l'accueil
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: accueil.php");
    exit();
}

// 2. INCLUSION DES FONCTIONS
include '../includes/fonctions.php';

// 3. RECUPERATION DES UTILISATEURS
$utilisateurs = getTousLesUtilisateurs();

// 4. TRAITEMENT DE L'AFFICHAGE (détail d'un utilisateur ou liste)
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
    include '../includes/head.html';
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

    <style>
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .main-title {
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em;
            color: #333;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .admin-table thead {
            background-color: #333;
            color: white;
        }

        .admin-table th,
        .admin-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .admin-table tbody tr:hover {
            background-color: #f5f5f5;
        }

        .user-detail-section {
            margin-top: 20px;
        }

        .user-profile-card {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-top: 20px;
        }

        .user-profile-card h2 {
            color: #333;
            margin-bottom: 20px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }

        .user-info {
            margin-bottom: 30px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .user-info p {
            margin: 0;
            line-height: 1.6;
        }

        .user-actions {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        .user-actions h3 {
            color: #333;
            margin-bottom: 20px;
        }

        .action-group {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }

        .action-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 10px;
            color: #333;
        }

        .action-group select,
        .action-group input[type="number"] {
            padding: 8px;
            margin-right: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
            margin-bottom: 20px;
        }

        .btn-secondary:hover {
            background-color: #545b62;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-info {
            background-color: #17a2b8;
            color: white;
        }

        .btn-info:hover {
            background-color: #138496;
        }

        .btn-sm {
            padding: 6px 10px;
            font-size: 0.85em;
        }

        @media (max-width: 768px) {
            .admin-table {
                font-size: 0.9em;
            }

            .admin-table th,
            .admin-table td {
                padding: 8px;
            }

            .user-info {
                grid-template-columns: 1fr;
            }
        }
    </style>

</body>
</html>