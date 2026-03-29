<?php
session_start();

// 1. SECURITÉ : On vérifie que l'utilisateur est bien connecté
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// 2. RÉCUPÉRATION DU NUMÉRO DE COMMANDE (optionnel, via l'URL)
$id_commande = isset($_GET['order']) ? $_GET['order'] : 'NON_DÉFINIE';

// 3. SIMULATION D'ENVOI (Pour la Phase 2, on redirige juste avec un petit message)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ici on pourrait enregistrer dans un fichier avis.json plus tard
    header("Location: profil.php?feedback=success");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<?php 
    $titre_page = "KØLD | NOTATION";
    include '../includes/head.php';
?>


<body class="kold-mode">

    <?php 
        $nom_page = "notation";
        include '../includes/header.php';
    ?>

    <main class="notation-container">
        <h1 class="main-title">QUALITY_CONTROL // #<?php echo htmlspecialchars($id_commande); ?></h1>

            <form class="kold-form" action="notation.php" method="post">
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

            <button type="submit" class="btn-brutal btn-full" style="margin-top: 30px; cursor: pointer;">TRANSMETTRE_DONNÉES</button>
        </form>
    </main>

    <?php include '../includes/footer.php'; ?>

</body>
</html>