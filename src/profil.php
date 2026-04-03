<?php
session_start();

// 1. SECURITÉ : Si pas connecté, retour au login
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

include '../includes/fonctions.php';

// 2. RÉCUPÉRATION DE L'UTILISATEUR
$user_data = getInfoUser($_SESSION['user']);

if (!$user_data) {
    die("Erreur : Utilisateur introuvable dans la base.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<?php 
    $titre_page = "KØLD | PROFIL";
    include '../includes/head.php';
?>


<body class="kold-mode">

    <?php 
        $nom_page = "profil";
        include '../includes/header.php';
    ?>

    <main class="profil-container">

        <h1 class="main-title">PROFIL // <?php echo strtoupper($user_data['prenom'] . " " . $user_data['nom']); ?></h1>

        <div class="profil-grid">

            <section class="profil-box">
                <div class="box-header">DONNÉES_CLIENT</div>

                <div class="info-item">
                    <span>NOM : <?php echo strtoupper($user_data['nom'] . " " . $user_data['prenom']); ?></span>
                    <button class="edit-btn" onclick="alert('Phase 3 : Édition du nom')">✏️</button>
                </div>

                <div class="info-item">
                    <span>E-MAIL : <?php echo htmlspecialchars($user_data['email']); ?></span>
                    <button class="edit-btn" onclick="alert('Phase 3 : Édition de l\'email')">✏️</button>
                </div>

                <div class="info-item">
                    <span>TÉL : <?php echo htmlspecialchars($user_data['tel'] ?? 'NON_RENSEIGNÉ'); ?></span>
                    <button class="edit-btn" onclick="alert('Phase 3 : Édition du tel')">✏️</button>
                </div>

                <div class="info-item">
                    <span>RÔLE : <b style="color:var(--accent)"><?php echo strtoupper($user_data['role']); ?></b></span>
                </div>
            </section>

            <section class="profil-box">
                <div class="box-header">ADRESSE_LIVRAISON</div>

                <div class="info-item" style="flex-direction: column; align-items: flex-start;">
                    <span style="margin-bottom: 10px; word-break: break-word;"><?php echo htmlspecialchars($user_data['adresse'] ?? 'NON_RENSEIGNÉE'); ?></span>
                    <button class="edit-btn" onclick="alert('Phase 3 : Édition de l\'adresse')">✏️</button>
                </div>

                <?php if (!empty($user_data['infosup'])): ?>
                <div class="info-item" style="flex-direction: column; align-items: flex-start; margin-top: 15px;">
                    <span style="font-size: 0.7rem; color: var(--accent); margin-bottom: 5px;">INFO SUPPLÉMENTAIRE</span>
                    <span style="word-break: break-word; font-size: 0.9rem;"><?php echo htmlspecialchars($user_data['infosup']); ?></span>
                </div>
                <?php endif; ?>
            </section>

            <section class="profil-box">
                <div class="box-header">STATUT_FIDÉLITÉ</div>
                <div class="points-display">
                    <span style="font-family: 'Archivo Black'; font-size: 3rem;"><?php echo (intval($user_data['id']) * 2); ?></span>
                    <span style="font-size: 1rem; color: var(--accent);">PTS</span>
                </div>
                <div class="fidelity-bar" style="height: 20px; background: #f0f0f0; border: 2px solid var(--text); margin-top: 10px;">
                    <div class="progress" style="width: 65%; height: 100%; background: var(--accent);"></div>
                </div>
                <p style="margin-top: 10px; font-size: 0.8rem; font-weight: bold;">GRADE : <?php echo strtoupper($user_data['statut'] ?? 'MOLDU'); ?></p>
                
                <?php if (!empty($user_data['remise']) && $user_data['remise'] != "0"): ?>
                <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd;">
                    <p style="font-size: 0.8rem;"><strong>REMISE ACTIVE :</strong> <span style="color: var(--accent);"><?php echo htmlspecialchars($user_data['remise']); ?>%</span></p>
                </div>
                <?php endif; ?>
            </section>

            <section class="profil-box">
                <div class="box-header">DERNIÈRES_COMMANDES</div>
                <table style="width: 100%; font-size: 0.8rem; border-collapse: collapse;">
                    <?php 
                    $commandes = getCommandesByUserId($_SESSION['user']);
                    $commandes_recentes = array_slice(array_reverse($commandes), 0, 5);
                    
                    if (!empty($commandes_recentes)):
                        foreach ($commandes_recentes as $cmd): ?>
                        <tr style="border-bottom: 1px solid #ddd;">
                            <td style="padding: 10px 0;">#<?php echo $cmd['id']; ?></td>
                            <td><?php echo substr($cmd['date_heure'], 0, 10); ?></td>
                            <td style="text-align: right;">
                                <?php if ($cmd['notation'] === null): ?>
                                    <a href="notation.php?id=<?php echo $cmd['id']; ?>" class="btn-brutal btn-small" style="background:var(--accent); color:white; text-decoration:none; padding: 5px 12px; font-size: 0.7rem;">NOTER</a>
                                <?php else: ?>
                                    <span style="color: var(--accent); font-weight: bold;">✓ NOTÉ</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach;
                    else: ?>
                        <tr>
                            <td style="padding: 10px 0; text-align: center; color: #999;">Aucune commande</td>
                        </tr>
                    <?php endif; ?>
                </table>
            </section>

        </div>

        <div style="margin-top: 30px; text-align: center; display: flex; gap: 15px; justify-content: center;">
            <a href="historique_commandes_client.php" class="btn-brutal" style="background: var(--accent); color: white; text-decoration: none; display: inline-block; margin: 0;">VOIR L'HISTORIQUE COMPLET</a>
            <a href="logout.php" class="btn-brutal" style="background: #ff0055; color: white; text-decoration: none; display: inline-block; margin: 0;">DÉCONNEXION</a>
        </div>

    </main>

    <?php include '../includes/footer.php'; ?>

</body>
</html>