<?php
session_start();
require_once '../includes/fonctions.php';
verifierUtilisateurBloque();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

$id_commande = isset($_GET['id_commande']) ? intval($_GET['id_commande']) : 0;

// Traitement AJAX sécurisé
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'spin') {
    header('Content-Type: application/json');
    $id_user = $_SESSION['user'];
    
    $remise_gagnee = verifierEtJouerRoulette($id_user, $id_commande);
    
    if ($remise_gagnee === false) {
        echo json_encode(['success' => false, 'message' => 'Erreur : Roulette déjà jouée ou commande introuvable.']);
    } else {
        echo json_encode(['success' => true, 'remise' => $remise_gagnee]);
    }
    exit();
}

// Vérification autonome de l'existence et propriété de la commande
$fichier_commandes = '../data/commandes.json';
$commande_valide = false;
if (file_exists($fichier_commandes)) {
    $commandes = json_decode(file_get_contents($fichier_commandes), true);
    foreach ($commandes as $c) {
        if ($c['id'] == $id_commande && $c['user_id'] == $_SESSION['user']) {
            $commande_valide = true;
            break;
        }
    }
}

if (!$commande_valide) {
    header("Location: historique_commandes_client.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<?php 
    $titre_page = "KØLD | LE REPOUPON GLACÉ";
    include '../includes/head.php';
?>

<body class="kold-mode">

    <?php 
        $nom_page = "roulette";
        include '../includes/header.php';
    ?>

    <main class="roulette-container">
        <div class="roulette-card">
            <h1 class="main-title" style="font-size: 2.5rem; margin-bottom: 10px;">ROULETTE KØLD</h1>
            <p style="font-family: 'Space Mono'; font-weight: bold;">COMMANDE #<?php echo $id_commande; ?> VALIDÉE !</p>
            <p style="font-size: 0.85rem; margin-top: 5px; color: #555;">Tentez votre chance pour réduire le coût de votre repas live !</p>

            <div class="wheel-wrapper">
                <div class="wheel-pointer"></div>
                <div class="brutal-wheel" id="wheel">
                    <div class="wheel-label label-perdu">PERDU</div>
                    <div class="wheel-label label-cinq">-5%</div>
                    <div class="wheel-label label-dix">-10%</div>
                </div>
                <div class="wheel-center-btn">KØLD</div>
            </div>

            <button id="spin-button" class="btn-brutal" style="margin-top: 10px; width: 100%;">TOURNER LA ROULETTE</button>

            <div class="roulette-result-box" id="result-box">
                <h2 id="result-title" style="font-family: 'Archivo Black'; font-size: 1.5rem; margin-bottom: 10px;"></h2>
                <p id="result-text" style="font-family: 'Space Mono'; font-size: 1rem; margin-bottom: 20px;"></p>
                <a href="historique_commandes_client.php" class="btn-brutal" style="margin: 0; padding: 12px 25px; font-size: 0.9rem; background: #000; color: #fff;">VOIR MES COMMANDES</a>
            </div>
        </div>
    </main>

    <?php include '../includes/footer.html'; ?>

    <script>
    document.getElementById('spin-button').addEventListener('click', function() {
        const button = this;
        button.disabled = true;
        button.style.opacity = '0.5';

        // Demande asynchrone du tirage sécurisé au serveur PHP
        const formData = new FormData();
        formData.append('action', 'spin');

        fetch('roulette.php?id_commande=<?php echo $id_commande; ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert(data.message);
                window.location.href = 'historique_commandes_client.php';
                return;
            }

            const wheel = document.getElementById('wheel');
            let targetAngle = 0;

            // Définition de l'angle d'arrêt ciblé selon le lot (Aiguille positionnée en haut à 0° / 360°)
            // La roue tourne dans le sens horaire, on calcule la position du segment par rapport à l'index d'arrêt.
            if (data.remise === 0) {
                // Perdu se trouve entre 0° et 180°. On cible le milieu du segment à 90°.
                // On applique un décalage inverse pour amener les 90° sous l'aiguille (360 - 90 = 270)
                targetAngle = 270; 
            } else if (data.remise === 5) {
                // -5% se trouve entre 180° et 324°. Milieu de zone à 252°. (360 - 252 = 108)
                targetAngle = 108;
            } else if (data.remise === 10) {
                // -10% se trouve entre 324° et 360°. Milieu de zone à 342°. (360 - 342 = 18)
                targetAngle = 18;
            }

            // Ajout de 5 tours complets (5 * 360 = 1800) pour l'effet visuel de vitesse
            const finalRotation = 1800 + targetAngle;
            wheel.style.transform = `rotate(${finalRotation}deg)`;

            // Attente de la fin de l'animation CSS (4 secondes) pour afficher le résultat tactique
            setTimeout(() => {
                const resultBox = document.getElementById('result-box');
                const resultTitle = document.getElementById('result-title');
                const resultText = document.getElementById('result-text');

                if (data.remise > 0) {
                    resultBox.style.borderColor = '#00ff66';
                    resultTitle.innerText = `❄️ GAGNÉ !`;
                    resultTitle.style.color = '#00cc55';
                    resultText.innerText = `Félicitations ! Une remise immédiate de ${data.remise}% a été injectée sur votre commande et déduite de votre facture.`;
                } else {
                    resultBox.style.borderColor = '#ff4444';
                    resultTitle.innerText = `❌ PERDU !`;
                    resultTitle.style.color = '#ff4444';
                    resultText.innerText = `Pas de chance pour cette fois. Votre repas reste tout de même préservé au grand froid !`;
                }

                resultBox.style.display = 'block';
                resultBox.scrollIntoView({ behavior: 'smooth' });
            }, 4000);
        })
        .catch(error => {
            console.error('Erreur technique:', error);
            button.disabled = false;
            button.style.opacity = '1';
        });
    });
    </script>
</body>
</html>