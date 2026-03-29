<?php
session_start();

// 1. SECURITÉ : Si pas connecté, retour au login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// 2. RÉCUPÉRATION DE L'UTILISATEUR
$json_path = __DIR__ . '/../data/user.json';
$user_data = null;

// Si un ID est dans l'URL (via admin.php), on l'utilise, sinon on prend le nôtre
$id_a_chercher = isset($_GET['id']) ? $_GET['id'] : $_SESSION['id'];

if (file_exists($json_path)) {
    $users = json_decode(file_get_contents($json_path), true);
    if (is_array($users)) {
        foreach ($users as $u) {
            if ($u['id'] == $id_a_chercher) {
                $user_data = $u;
                break;
            }
        }
    }
}

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
        <?php if (isset($_GET['id'])): ?>
            <a href="admin.php" style="color: var(--accent); text-decoration: none; font-size: 0.8rem;">← RETOUR À LA LISTE ADMIN</a>
        <?php endif; ?>

        <h1 class="main-title">PROFIL // <?php echo strtoupper($user_data['login']); ?></h1>

        <div class="profil-grid">

            <section class="profil-box">
                <div class="box-header">DONNÉES_CLIENT</div>

                <div class="info-item">
                    <span>NOM : <?php echo strtoupper($user_data['nom'] . " " . $user_data['prenom']); ?></span>
                    <button class="edit-btn" onclick="alert('Phase 3 : Édition du nom')">✏️</button>
                </div>

                <div class="info-item">
                    <span>E-MAIL : <?php echo strtoupper($user_data['email']); ?></span>
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
                <div class="box-header">STATUT_FIDÉLITÉ</div>
                <div class="points-display">
                    <span style="font-family: 'Archivo Black'; font-size: 3rem;"><?php echo (substr($user_data['id'], -3) * 2); ?></span>
                    <span style="font-size: 1rem; color: var(--accent);">PTS</span>
                </div>
                <div class="fidelity-bar" style="height: 20px; background: #333; border: 2px solid var(--text); margin-top: 10px;">
                    <div class="progress" style="width: 65%; height: 100%; background: var(--accent);"></div>
                </div>
                <p style="margin-top: 10px; font-size: 0.8rem; font-weight: bold;">GRADE : ARTIC_MASTER</p>
            </section>

            <section class="profil-box">
                <div class="box-header">DERNIÈRES_COMMANDES</div>
                <table style="width: 100%; font-size: 0.8rem; border-collapse: collapse;">
                    <?php 
                    // Simulation de commandes pour le workflow
                    $commandes = [
                        ['id' => 'KB-892', 'date' => '12/02/26', 'etat' => 'LIVRÉ'],
                        ['id' => 'KB-741', 'date' => '05/02/26', 'etat' => 'EN_COURS']
                    ];
                    foreach ($commandes as $cmd): ?>
                    <tr style="border-bottom: 1px solid #333;">
                        <td style="padding: 10px 0;">#<?php echo $cmd['id']; ?></td>
                        <td><?php echo $cmd['date']; ?></td>
                        <td style="text-align: right;">
                            <?php if ($cmd['etat'] === 'LIVRÉ'): ?>
                                <a href="notation.php?order=<?php echo $cmd['id']; ?>" class="btn-brutal btn-small" style="background:var(--accent); color:white; text-decoration:none; padding: 2px 8px;">NOTER</a>
                            <?php else: ?>
                                <span style="color: #888;"><?php echo $cmd['etat']; ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </section>

        </div>

        <?php if ($id_a_chercher == $_SESSION['id']): ?>
        <div style="margin-top: 30px; text-align: center;">
            <a href="logout.php" class="btn-brutal" style="background: #ff0055; color: white; text-decoration: none;">DÉCONNEXION</a>
        </div>
        <?php endif; ?>

    </main>

    <?php include '../includes/footer.php'; ?>

</body>
</html>