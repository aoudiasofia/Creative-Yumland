<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KØLD | PROFIL_UNIT</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Space+Mono:wght@400;700&display=swap"
        rel="stylesheet">
</head>

<body class="kold-mode">

    <header class="main-header">
         <div class="logo">
            <a href="accueil.php" style="text-decoration: none; color: inherit;">KØLD</a>
        </div>
        <nav>
            <ul>
                <li><a href="presentation.php">La Carte</a></li>
                <li><a href="profil.php" class="nav-login">SOFIA_01</a></li>
            </ul>
        </nav>
    </header>

    <main class="profil-container">
        <h1 class="main-title">PROFIL</h1>

        <div class="profil-grid">

            <section class="profil-box">
                <div class="box-header">DONNÉES_CLIENT</div>

                <div class="info-item">
                    <span>NOM : SOFIA</span>
                    <button class="edit-btn">✏️</button>
                </div>

                <div class="info-item">
                    <span>ADRESSE : 12 RUE DU FROID, CERGY</span>
                    <button class="edit-btn">✏️</button>
                </div>

                <div class="info-item">
                    <span>INTERPHONE : B-240</span>
                    <button class="edit-btn">✏️</button>
                </div>

                <div class="info-item">
                    <span>E-MAIL : SOFIA@CYTECH.FR</span>
                    <button class="edit-btn">✏️</button>
                </div>
            </section>

            <section class="profil-box">
                <div class="box-header">STATUT_FIDÉLITÉ</div>
                <div class="points-display">
                    <span style="font-family: 'Archivo Black'; font-size: 3rem;">1250</span>
                    <span style="font-size: 1rem; color: var(--accent);">PTS</span>
                </div>
                <div class="fidelity-bar"
                    style="height: 20px; background: var(--bg); border: 2px solid var(--text); margin-top: 10px;">
                    <div class="progress" style="width: 70%; height: 100%; background: var(--accent);"></div>
                </div>
                <p style="margin-top: 10px; font-size: 0.8rem; font-weight: bold;">GRADE : ARTIC_MASTER</p>
            </section>

            <section class="profil-box">
                <div class="box-header">DERNIÈRES_COMMANDES</div>
                <table style="width: 100%; font-size: 0.8rem; border-collapse: collapse;">
                    <tr style="border-bottom: 1px solid var(--bg);">
                        <td style="padding: 10px 0;">#KB-892</td>
                        <td>12/02/26</td>
                        <td style="text-align: right; color: var(--accent);">LIVRÉ</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--bg);">
                        <td style="padding: 10px 0;">#KB-741</td>
                        <td>05/02/26</td>
                        <td style="text-align: right; color: var(--accent);">LIVRÉ</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0;">#KB-630</td>
                        <td>28/01/26</td>
                        <td style="text-align: right; color: var(--accent);">LIVRÉ</td>
                    </tr>
                </table>
            </section>

        </div>
    </main>

    <footer class="kold-footer">
        <p> KØLD // PROJET PREING2 - 2025-2026</p>
    </footer>

</body>

</html>