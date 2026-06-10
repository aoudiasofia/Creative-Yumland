<?php
session_start(); //admin
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

<style>
    .admin-container {
        max-width: 1300px;
        margin: 0 auto;
        padding: 40px 20px;
        width: 100%;
    }

    /* Force le fond blanc à englober tout son contenu sans hauteur fixe */
    .user-profile-card, .users-list-section {
        background-color: #ffffff;
        height: auto !important; /* Supprime toute restriction de hauteur */
        min-height: min-content;
        padding: 35px;
        border: 4px solid #000000;
        box-shadow: 8px 8px 0px #000000;
        margin-bottom: 40px;
        overflow: visible; /* Évite de couper les menus déroulants */
    }

    /* Enveloppe pour le tableau pour éviter qu'il ne déchire le fond blanc */
    .table-responsive {
        width: 100%;
        overflow-x: auto; /* Active le scroll horizontal proprement si l'écran est trop étroit */
        margin-top: 20px;
        border: 2px solid #000000;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px; /* Force le tableau à garder une taille lisible */
    }

    .admin-table th, .admin-table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 2px solid #000000;
    }

    .admin-table th {
        background-color: #f0f0f0;
        font-family: 'Archivo Black', sans-serif;
    }
</style>

<body class="kold-mode">

    <?php 
        $nom_page = "admin";
        include '../includes/header.php';
    ?>

    <main class="admin-container">
        <h1 class="main-title">ADMIN PANEL</h1>

        <?php if ($user_detail): ?>
            <div class="user-detail-section">
                <a href="admin.php" class="btn btn-secondary" style="margin-bottom: 20px; display: inline-block;">← Retour à la liste</a>
                
                <div class="user-profile-card">
                    <h2><?php echo htmlspecialchars($user_detail['prenom'] . ' ' . $user_detail['nom']); ?></h2>
                    
                    <div class="user-info" style="margin-top: 20px; display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 15px;">
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

                    <div class="user-actions" style="margin-top: 30px; border-top: 2px dashed #000; padding-top: 20px;">
                        <h3>Actions</h3>
                        
                        <div class="action-group" style="margin: 15px 0;">
                            <label style="display:inline-block; width: 120px;">Rôle:</label>
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

                        <div class="action-group" style="margin: 15px 0;">
                            <label style="display:inline-block; width: 120px;">Statut:</label>
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

                        <div class="action-group" style="margin: 15px 0;">
                            <label style="display:inline-block; width: 120px;">Remise (%):</label>
                            <form method="POST" action="" style="display: inline;">
                                <input type="number" name="new_remise" min="0" max="100" value="<?php echo htmlspecialchars($user_detail['remise']); ?>" required>
                                <button type="submit" class="btn btn-primary">Modifier la remise</button>
                            </form>
                        </div>

                       <div class="action-group" style="margin: 15px 0;">
                            <label style="display:inline-block; width: 120px;">Compte:</label>
                            <button class="btn-toggle-block btn <?php echo $user_detail['bloqué'] ? 'btn-success' : 'btn-danger'; ?>" 
                                    data-id="<?php echo $user_detail['id']; ?>">
                                <?php echo $user_detail['bloqué'] ? '✓ Débloquer le compte' : '✗ Bloquer le compte'; ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <div class="users-list-section">
                <h2>Gestion des utilisateurs (<?php echo count($utilisateurs); ?>)</h2>
                
                <div class="table-responsive">
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
            </div>
        <?php endif; ?>

    </main>

    <?php include '../includes/footer.html'; ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const boutons = document.querySelectorAll('.btn-toggle-block');

        boutons.forEach(monBouton => {
            monBouton.addEventListener('click', function(e) {
                e.preventDefault(); 
                
                const idUser = this.getAttribute('data-id');
                const donnees = new FormData();
                donnees.append('action', 'toggle_block');
                donnees.append('id', idUser);

                fetch('ajax_handler.php', {
                    method: 'POST',
                    body: donnees
                })
                .then(reponse => reponse.json())
                .then(data => {
                    if (data.success) {
                        if (data.estBloque) {
                            this.innerText = '✓ Débloquer le compte';
                            this.classList.remove('btn-danger');
                            this.classList.add('btn-success');
                        } else {
                            this.innerText = '✗ Bloquer le compte';
                            this.classList.remove('btn-success');
                            this.classList.add('btn-danger');
                        }
                    } else {
                        alert("Erreur lors de la modification.");
                    }
                });
            });
        });
    });
    </script>

</body>
</html>