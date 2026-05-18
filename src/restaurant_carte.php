<?php 
session_start(); 
include '../includes/fonctions.php';

// Sécurité : Restaurateur ou Admin uniquement
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'restaurateur' && $_SESSION['role'] !== 'admin')) {
    header("Location: connexion.php");
    exit();
}

// Récupération des plats actuels
$chemin = __DIR__ . '/../data/produits.json';
$data = json_decode(file_get_contents($chemin), true);
$plats = $data['plats'] ?? [];
?>
<!DOCTYPE html>
<html lang="fr">
<?php 
    $titre_page = "KØLD | GESTION DE LA CARTE";
    include '../includes/head.php';
?>

<body class="kold-mode">

    <?php 
        $nom_page = "restaurant_carte";
        include '../includes/header.php';
    ?>

    <main style="max-width: 1200px; margin: 0 auto; padding: 40px 20px;">
        
        <div style="margin-bottom: 20px;">
            <a href="restaurant.php" style="color: #000; font-weight: 900; text-transform: uppercase; text-decoration: underline;">
                ⬅️ Retour à la gestion des commandes
            </a>
        </div>

        <h1 style="font-family: 'Inter', sans-serif; font-weight: 900; font-size: 2.3rem; text-transform: uppercase; border-bottom: 5px solid #000; padding-bottom: 10px; margin-bottom: 30px;">
            ⚙️ CONFIGURATION DE LA CARTE
        </h1>

        <?php if (isset($_GET['statut']) && $_GET['statut'] === 'ajoute'): ?>
            <div style="background: #00ff66; border: 3px solid #000; padding: 15px; font-weight: 900; margin-bottom: 20px; box-shadow: 4px 4px 0px #000;">
                ✅ LE NOUVEAU PLAT A BIEN ÉTÉ INTÉGRÉ AU FICHIER JSON ET À LA CARTE !
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['statut']) && $_GET['statut'] === 'supprime'): ?>
            <div style="background: #ff3333; color: #fff; border: 3px solid #000; padding: 15px; font-weight: 900; margin-bottom: 20px; box-shadow: 4px 4px 0px #000;">
                🗑️ LE PLAT A ÉTÉ RETIRÉ DÉFINITIVEMENT DU MENU.
            </div>
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 40px; align-items: start;">
            
            <div style="border: 4px solid #000; background: #fff; padding: 25px; box-shadow: 8px 8px 0px #000;">
                <h2 style="font-weight: 900; text-transform: uppercase; font-size: 1.3rem; margin-top: 0; border-bottom: 2px solid #000; padding-bottom: 5px; margin-bottom: 20px;">
                    ➕ AJOUTER UN NOUVEAU PRODUIT
                </h2>
                
                <form action="traitement_restaurant.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="action" value="ajouter">
    
    <div style="margin-bottom: 15px;">
        <label style="display: block; font-weight: 900; margin-bottom: 5px; text-transform: uppercase;">Nom du plat :</label>
        <input type="text" name="nom" required style="width: 100%; border: 3px solid #000; padding: 8px; font-weight: bold; box-sizing: border-box;">
    </div>

    <div style="margin-bottom: 15px;">
        <label style="display: block; font-weight: 900; margin-bottom: 5px; text-transform: uppercase;">Description :</label>
        <textarea name="description" required rows="3" style="width: 100%; border: 3px solid #000; padding: 8px; font-weight: bold; font-family: sans-serif; box-sizing: border-box;"></textarea>
    </div>

    <div style="margin-bottom: 15px;">
        <label style="display: block; font-weight: 900; margin-bottom: 5px; text-transform: uppercase;">Prix public (€) :</label>
        <input type="number" step="0.01" name="prix" required style="width: 100%; border: 3px solid #000; padding: 8px; font-weight: bold; box-sizing: border-box;">
    </div>

    <div style="margin-bottom: 15px;">
        <label style="display: block; font-weight: 900; margin-bottom: 5px; text-transform: uppercase;">Illustration du plat (.png, .jpg) :</label>
        <input type="file" name="image_plat" accept="image/*" style="width: 100%; border: 3px solid #000; padding: 8px; font-weight: bold; background: #fff; box-sizing: border-box;">
    </div>

    <div style="margin-bottom: 15px;">
        <label style="display: block; font-weight: 900; margin-bottom: 5px; text-transform: uppercase;">Catégorie :</label>
        <select name="categorie" style="width: 100%; border: 3px solid #000; padding: 8px; font-weight: bold; background:#fff;">
            <option value="bowls">Bowls</option>
            <option value="wraps">Wraps</option>
            <option value="salades">Salades</option>
            <option value="desserts">Desserts</option>
            <option value="boissons">Boissons</option>
        </select>
    </div>

    <div style="margin-bottom: 20px;">
        <label style="display: block; font-weight: 900; margin-bottom: 5px; text-transform: uppercase;">Régime Alimentaire :</label>
        <select name="regime" style="width: 100%; border: 3px solid #000; padding: 8px; font-weight: bold; background:#fff;">
            <option value="classique">Classique</option>
            <option value="vegetarien">Végétarien 🍃</option>
        </select>
    </div>

    <button type="submit" class="btn-brutal" style="width: 100%; text-transform: uppercase; font-weight: 900; padding: 12px; cursor: pointer; background: #000; color: #fff; border: 3px solid #000;">
        INJECTER SUR LA CARTE
    </button>
</form>
            </div>

            <div style="border: 4px solid #000; background: #fff; padding: 25px; box-shadow: 8px 8px 0px #000;">
                <h2 style="font-weight: 900; text-transform: uppercase; font-size: 1.3rem; margin-top: 0; border-bottom: 2px solid #000; padding-bottom: 5px; margin-bottom: 20px;">
                    📋 CARTE ACTUELLE (<?= count($plats) ?> ARTICLES RÉPERTORIÉS)
                </h2>

                <div style="display: flex; flex-direction: column; gap: 15px; max-height: 600px; overflow-y: auto; padding-right: 5px;">
                    <?php foreach ($plats as $p): ?>
                        <div style="border: 3px solid #000; padding: 15px; display: flex; justify-content: space-between; align-items: center; background: #fafafa;">
                            <div>
                                <span style="font-size: 0.7rem; background: #000; color: #fff; padding: 2px 6px; font-weight: bold; text-transform: uppercase;">
                                    <?= htmlspecialchars($p['categorie']) ?> | <?= htmlspecialchars($p['regime']) ?>
                                </span>
                                <h3 style="margin: 6px 0 4px 0; font-weight: 900; text-transform: uppercase; font-size: 1.05rem;">
                                    <?= htmlspecialchars($p['nom']) ?>
                                </h3>
                                <p style="margin: 0; font-weight: bold; font-size: 0.95rem; color: #333;">
                                    <?= number_format($p['prix'], 2, ',', ' ') ?> € 
                                    <span style="font-weight: normal; font-size: 0.8rem; color: #666;"> (★ <?= $p['commandes'] ?> commandes)</span>
                                </p>
                            </div>
                            
                            <a href="traitement_restaurant.php?action=supprimer&id=<?= $p['id'] ?>" 
                               onclick="return confirm('Voulez-vous vraiment retirer ce plat du catalogue JSON ?');"
                               style="background: #ff3333; color: #fff; border: 2px solid #000; padding: 6px 10px; text-decoration: none; font-weight: 900; font-size: 0.8rem; box-shadow: 2px 2px 0px #000; text-transform: uppercase;">
                                Supprimer
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </main>

    <?php include '../includes/footer.html'; ?>
</body>
</html>