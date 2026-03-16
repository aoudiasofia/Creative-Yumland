<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>KØLD | QUALITY_CONTROL</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Space+Mono:wght@400;700&display=swap"
        rel="stylesheet">
</head>

<body class="kold-mode">

    <header class="main-header">
         <div class="logo">
            <a href="accueil.html" style="text-decoration: none; color: inherit;">KØLD</a>
        </div>
        <nav>
            <ul>
                <li><a href="profil.html" class="nav-login">SOFIA_01</a></li>
            </ul>
        </nav>
    </header>

    <main class="notation-container">
        <h1 class="main-title">AVIS</h1>

            <form class="kold-form" action="#" method="post">
            <div class="rating-section">
                <label class="label-tech">ÉVALUATION_LOGISTIQUE (LIVRAISON)</label>
                <div class="rating-grid">
                    <label class="rate-box"><input type="radio" name="delivery" value="1"><span>01</span></label>
                    <label class="rate-box"><input type="radio" name="delivery" value="2"><span>02</span></label>
                    <label class="rate-box"><input type="radio" name="delivery" value="3"><span>03</span></label>
                    <label class="rate-box"><input type="radio" name="delivery" value="4"><span>04</span></label>
                    <label class="rate-box"><input type="radio" name="delivery" value="5"
                            checked><span>05</span></label>
                </div>
            </div>

            <div class="rating-section" style="margin-top: 30px;">
                <label class="label-tech">QUALITÉ_DES_PRODUITS</label>
                <div class="rating-grid">
                    <label class="rate-box"><input type="radio" name="product" value="1"><span>01</span></label>
                    <label class="rate-box"><input type="radio" name="product" value="2"><span>02</span></label>
                    <label class="rate-box"><input type="radio" name="product" value="3"><span>03</span></label>
                    <label class="rate-box"><input type="radio" name="product" value="4"><span>04</span></label>
                    <label class="rate-box"><input type="radio" name="product" value="5" checked><span>05</span></label>
                </div>
            </div>

            <div class="rating-section" style="margin-top: 30px;">
                <label class="label-tech">COMMENTAIRES_ADDITIONNELS</label>
                <textarea class="kold-textarea" name="commentaires" placeholder="RAS / TRANSMISSION EN COURS..."></textarea>
            </div>

            <button type="submit" class="btn-brutal btn-full" style="margin-top: 30px;">TRANSMETTRE </button>
        </form>
    </main>

    <footer class="kold-footer">
        <p> KØLD // PROJET PREING2 - 2025-2026</p>
    </footer>

</body>

</html>