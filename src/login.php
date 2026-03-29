<!DOCTYPE html>
<html lang="fr">

<?php 
    $titre_page = "KØLD | LOGIN";
    include '../includes/head.php';
?>


<body class="kold-mode">

    <?php 
        $nom_page = "login";
        include '../includes/header.php';
    ?>

    <main class="login-container">
        <h1 class="main-title" style="font-size: 3rem; margin-bottom: 20px;">IDENTIFICATION</h1>

        <form class="kold-form" action="verif_login.php" method="post">

            <div class="email-row input-group">
                <label class="email-col label-tech" for="login">LOGIN</label>
                <div class="input-col">
                    <input type="text" id="login" name="login" placeholder="VOTRE_IDENTIFIANT" required>
                </div>
            </div>

            <div class="password-row input-group">
                <label class="password-col label-tech" for="password">MOT DE PASSE</label>
                <div class="input-col">
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div style="color: #ff4444; font-family: 'Space Mono'; margin-bottom: 20px; border: 1px solid #ff4444; padding: 10px; background: rgba(255,0,0,0.1);">
                    > ERREUR_SYSTEME : IDENTIFIANTS_INVALIDES
                </div>
            <?php endif; ?>

            <div class="button-row">
                <button type="submit" class="btn-login">INITIALISER LA SESSION</button>
            </div>
        </form>
    </main>

    <?php include '../includes/footer.php'; ?>

</body>
</html>