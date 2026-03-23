<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KØLD - Identification</title>
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
                <li><a href="inscription.php">Inscription</a></li>
            </ul>
        </nav>
    </header>

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

    <footer class="kold-footer">
        <p> KØLD // PROJET PREING2 - 2025-2026</p>
    </footer>

</body>
</html>