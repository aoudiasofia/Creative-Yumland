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

// On regarde si on affiche notre propre profil ou celui d'un autre (si on est admin)
$id_a_chercher = isset($_GET['id']) ? $_GET['id'] : $_SESSION['id'];

if (file_exists($json_path)) {
    $users = json_decode(file_get_contents($json_path), true);
    foreach ($users as $u) {
        if ($u['id'] == $id_a_chercher) {
            $user_data = $u;
            break;
        }
    }
}

// Si l'utilisateur n'existe pas dans le JSON
if (!$user_data) {
    die("Utilisateur introuvable.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KØLD | PROFIL_UNIT</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
</head>

<body class="kold-mode">

    <header class="main-header">
         <div class="logo">
            <a href="accueil.php" style="text-decoration: none; color: inherit;">KØLD</a>
        </div>
        <nav>
            <ul>
                <li><a href="presentation.php">La Carte</a></li>
                <li><a href="profil.php" class="nav-login"><?php echo strtoupper($_SESSION['user']); ?></a></li>
            </ul>
        </nav>
    </header>

    <main class="profil-container">
        <h1 class="main-title">PROFIL // <?php echo strtoupper($user_data['login']); ?></h1>

        <div class="profil-grid">

            <section class="profil-box">
                <div class="box-header">DONNÉES_CLIENT</div>

                <div class="info-item">
                    <span>NOM : <?php echo strtoupper($user_data['nom'] . " " . $user_data['prenom']); ?></span>
                    <button class="edit-btn">✏️</button>
                </div>

                <div class="info-item">
                    <span>E-MAIL : <?php echo strtoupper($user_data['email']); ?></span>
                    <button class="edit-btn">✏️</button>
                </div>

                <div class="info-item">
                    <span>TÉL : <?php echo $user_data['tel'] ?? 'NON_RENSEIGNÉ'; ?></span>
                    <button class="edit-btn">✏️</button>
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
                    // Simulation de commandes pour l'exemple
                    $commandes = [
                        ['id' => 'KB-892', 'date' => '12/02/26', 'etat' => 'LIVRÉ'],
                        ['id' => 'KB-741', 'date' => '05/02/26', 'etat' => 'LIVRÉ']
                    ];
                    foreach ($commandes as $cmd): ?>
                    <tr style="border-bottom: 1px solid #333;">
                        <td style="padding: 10px 0;">#<?php echo $cmd['id']; ?></td>
                        <td><?php echo $cmd['date']; ?></td>
                        <td style="text-align: right; color: var(--accent);"><?php echo $cmd['etat']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </section>

        </div>

    
    </main>

    <footer class="kold-footer">
        <p> KØLD // PROJET PREING2 - 2025-2026</p>
    </footer>

</body>
</html>