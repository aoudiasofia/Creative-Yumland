<?php
session_start();
include '../includes/fonctions.php';
?>

<!DOCTYPE html>
<html lang="fr">

<?php 
    $titre_page = "KØLD | CONNEXION";
    include '../includes/head.php';
?>

<body class="kold-mode">

    <?php 
        $nom_page = "connexion";
        include '../includes/header.php';
    ?>

    <main class="login-container">
        <h1 class="main-title" style="font-size: 3rem; margin-bottom: 20px;">CONNEXION</h1>

        <form class="kold-form" action="traitement_connexion.php" method="post">

            <div class="email-row input-group">
                <label class="email-col label-tech" for="email">E-MAIL</label>
                <div class="input-col">
                    <input type="text" id="email" name="email" placeholder="E-MAIL" required>
                </div>
            </div>

            <div class="password-row input-group">
                <label class="password-col label-tech" for="password">MOT DE PASSE</label>
                <div class="input-col" style="display: flex; flex-direction: column; gap: 5px;">
                    <div style="display: flex; gap: 5px;">
                        <input type="password" id="password" name="password" placeholder="••••••••" style="flex-grow: 1;" maxlength="20" oninput="mettreAJourCompteur('password', 'compteur-mdp-conn', 20)" required>
                        <button type="button" onclick="basculerMotDePasse('password')" style="cursor: pointer; background: var(--white); border: 3px solid var(--text); padding: 0 15px; font-size: 1.2rem;" title="Afficher/Masquer">👁️</button>
                    </div>
                    <div id="compteur-mdp-conn" style="font-size: 0.8rem; text-align: right; font-family: 'Space Mono'; opacity: 0.8;">
                        0 / 20
                    </div>
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

    <?php include '../includes/footer.html'; ?>

</body>
</html>