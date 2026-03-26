<?php
session_start();
// Sécurité : seul le livreur ou l'admin passe
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'livreur' && $_SESSION['role'] !== 'admin')) {
    header("Location: login.php"); exit();
}

$orders = json_decode(file_get_contents('../data/commandes.json'), true);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>KØLD | DELIVERY_UNIT</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="kold-mode">
    <header class="main-header">
        <div class="logo"><a href="accueil.php" style="text-decoration:none; color:inherit;">KØLD</a></div>
        <nav><ul><li><a href="profil.php" class="nav-login"><?php echo strtoupper($_SESSION['user']); ?></a></li></ul></nav>
    </header>

    <main class="admin-container">
        <h1 class="main-title">LIVRAISONS_À_EFFECTUER</h1>
        <?php foreach ($orders as $o): if ($o['status'] === 'en préparation' || $o['status'] === 'en cours'): ?>
        <div class="profil-box" style="margin-bottom:20px;">
            <div class="box-header">COMMANDE #<?php echo $o['id']; ?></div>
            <p>CLIENT : <?php echo $o['user_id']; ?></p>
            <p>STATUT : <span style="color:var(--accent)"><?php echo strtoupper($o['status']); ?></span></p>
            
            <form action="traitement_commande.php" method="POST" style="margin-top:15px; display:flex; gap:10px;">
                <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
                <button type="submit" name="new_status" value="livré" class="btn-brutal" style="background:#00ff00; flex:1;">LIVRÉ</button>
                <button type="submit" name="new_status" value="abandonné" class="btn-brutal" style="background:#ff0000; color:white; flex:1;">ABANDONNER</button>
            </form>
        </div>
        <?php endif; endforeach; ?>
    </main>
</body>
</html>