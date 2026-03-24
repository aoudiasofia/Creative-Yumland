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
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KØLD | ADMIN</title>
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
                <li><a href="mes_commandes.php">Mes Commandes</a></li>
                
                <?php if (isset($_SESSION['user'])): ?>
                    <li class="user-status">ID: <?php echo htmlspecialchars($_SESSION['user']); ?></li>
                    
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <li><a href="admin.php" style="color: #ff0055;">[PANNEAU_ADMIN]</a></li>
                    <?php endif; ?>
                    
                    <li><a href="logout.php" class="nav-login">Déconnexion</a></li>
                    
                <?php else: ?>
                    <li><a href="login.php" class="nav-login">Connexion</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

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

    <footer class="kold-footer">
        <p> KØLD // PROJET PREING2 - 2025-2026</p>
    </footer>

</body>
</html>