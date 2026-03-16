<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KØLD | GESTION COMMANDES</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Space+Mono:wght@400;700&display=swap"
        rel="stylesheet">
</head>

<body class="kold-mode">

    <header class="main-header">
        <div class="logo">KØLD</div>
        <nav>
            <ul>
                <li><a href="#" class="nav-login">RESTO_KITCHEN</a></li>
            </ul>
        </nav>
    </header>

    <main class="commande-container">
        <h1 class="main-title" style="font-size: 4rem;">GESTION DES COMMANDES</h1>

        <div class="commande-grid">
            <section class="commande-column">
                <h2 class="column-title">EN ATTENTE</h2>

                <div class="order-card">
                    <div class="order-header">
                        <span class="order-id">#KB-8925</span>
                        <span class="order-time">14:32</span>
                    </div>
                    <ul class="order-items">
                        <li><span>1x</span> Le Poke Saumon</li>
                        <li><span>2x</span> Le Wrap Banquise</li>
                        <li><span>1x</span> Coca</li>
                    </ul>
                    <button class="btn-brutal btn-full">PASSER EN PRÉPARATION</button>
                </div>

                <div class="order-card">
                    <div class="order-header">
                        <span class="order-id">#KB-8924</span>
                        <span class="order-time">14:28</span>
                    </div>
                    <ul class="order-items">
                        <li><span>1x</span> La Salade Cristal</li>
                    </ul>
                    <button class="btn-brutal btn-full">PASSER EN PRÉPARATION</button>
                </div>

            </section>

            <section class="commande-column">
                <h2 class="column-title">EN PRÉPARATION</h2>

                <div class="order-card order-in-progress">
                    <div class="order-header">
                        <span class="order-id">#KB-8923</span>
                        <span class="order-time">14:25</span>
                    </div>
                    <ul class="order-items">
                        <li><span>1x</span> Le Veggie Ice</li>
                        <li><span>1x</span> Le Dôme de Neige</li>
                    </ul>
                    <button class="btn-brutal btn-full btn-ready">PRÊT POUR LIVRAISON</button>
                </div>

            </section>

            <section class="commande-column">
                <h2 class="column-title">EN LIVRAISON</h2>

                <div class="order-card order-in-delivery">
                    <div class="order-header">
                        <span class="order-id">#KB-8922</span>
                        <span class="order-time">14:20</span>
                    </div>
                    <ul class="order-items">
                        <li><span>3x</span> Le Sandwich Nordique</li>
                    </ul>
                    <div class="delivery-status">LIVREUR_03</div>
                </div>

            </section>
        </div>
    </main>

    <footer class="kold-footer">
        <p> KØLD // PROJET PREING2 - 2025-2026</p>
    </footer>

</body>

</html>