<?php
session_start();
include '../includes/fonctions.php';

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['restaurateur', 'admin', 'livreur'])) {
    header("Location: connexion.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: restaurant.php");
    exit();
}

$id_commande = intval($_GET['id']);
$commande = getCommandeById($id_commande);

if (!$commande) {
    header("Location: restaurant.php");
    exit();
}

// Sécurité : si le rôle est livreur, il ne peut voir que sa propre commande en livraison
if ($_SESSION['role'] === 'livreur') {
    $livreur_id = isset($_SESSION['id']) ? intval($_SESSION['id']) : 0;
    if (!isset($commande['livreur']) || intval($commande['livreur']) !== $livreur_id) {
        header("Location: livraison.php");
        exit();
    }
}

$details = calculerDetailCommande($commande);
$livreurs = getTousLesLivreurs();

?>

<!DOCTYPE html>
<html lang="fr">
<?php 
    $titre_page = "KØLD | DETAIL COMMANDE";
    include '../includes/head.php';
?>

<body class="kold-mode">

    <?php 
        $nom_page = "detail_commande";
        include '../includes/header.php';
    ?>

    <?php $retour_page = $_SESSION['role'] === 'livreur' ? 'livraison.php' : 'restaurant.php'; ?>
    <main class="detail-commande-container">
        
        <div class="detail-header">
            <h1>Commande #<?php echo htmlspecialchars($commande['id']); ?></h1>
            <a href="<?php echo $retour_page; ?>" class="btn-retour">← Retour aux commandes</a>
        </div>

        <div class="detail-grid">
            
            <!-- COLONNE 1 : ARTICLES -->
            <section class="detail-section articles-section">
                <h2>Articles de la commande</h2>
                
                <div class="articles-list">
                    <?php foreach ($details['articles'] as $article): ?>
                        <div class="article-card">
                            <div class="article-header">
                                <h3><?php echo htmlspecialchars($article['nom']); ?></h3>
                                <span class="article-type"><?php echo $article['type'] === 'menu' ? 'MENU' : 'PLAT'; ?></span>
                            </div>
                            
                            <p class="article-desc"><?php echo htmlspecialchars($article['description']); ?></p>
                            
                            <div class="article-quantite">
                                <span class="label">Quantité</span>
                                <span class="valeur"><?php echo $article['quantite']; ?></span>
                            </div>
                            
                            <!-- Si c'est un menu, afficher les plats inclus -->
                            <?php if ($article['type'] === 'menu' && isset($article['plats_inclus'])): ?>
                                <div class="plats-inclus">
                                    <h4>Plats inclus dans ce menu:</h4>
                                    <ul>
                                        <?php foreach ($article['plats_inclus'] as $plat): ?>
                                            <li>
                                                <strong><?php echo htmlspecialchars($plat['nom']); ?></strong>
                                                <span class="prix-petit"><?php echo number_format($plat['prix'], 2, ',', ' '); ?> €</span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            
                            <div class="article-prix">
                                <div class="prix-ligne">
                                    <span>Prix unitaire</span>
                                    <span><?php echo number_format($article['prix_unitaire'], 2, ',', ' '); ?> €</span>
                                </div>
                                <div class="prix-ligne prix-total-article">
                                    <span>Total article</span>
                                    <span><?php echo number_format($article['prix_total'], 2, ',', ' '); ?> €</span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- RÉSUMÉ PRIX -->
                <div class="prix-summary">
                    <div class="prix-ligne">
                        <span class="label">Sous-total</span>
                        <span class="valeur"><?php echo number_format($details['prix_total_avant_remise'], 2, ',', ' '); ?> €</span>
                    </div>
                    <?php if ($details['remise'] > 0): ?>
                        <div class="prix-ligne remise">
                            <span class="label">Remise</span>
                            <span class="valeur">-<?php echo number_format($details['remise'], 2, ',', ' '); ?> €</span>
                        </div>
                    <?php endif; ?>
                    <div class="prix-ligne prix-final">
                        <span class="label">Prix final</span>
                        <span class="valeur"><?php echo number_format($details['prix_apres_remise'], 2, ',', ' '); ?> €</span>
                    </div>
                </div>
            </section>

            <!-- COLONNE 2 : STATUT ET LIVREUR -->
            <section class="detail-section actions-section">
                <h2>Gestion de la commande</h2>
                
                <!-- ADRESSE DE LIVRAISON -->
                <div class="info-box">
                    <h4>Adresse de livraison</h4>
                    <p><?php echo htmlspecialchars($commande['adresse_livraison']) ?: 'Non spécifiée'; ?></p>
                </div>

                <!-- QUAND -->
                <div class="info-box">
                    <h4>Livraison</h4>
                    <p><?php echo $commande['quand'] === 'maintenant' ? 'Dès que possible' : 'Plus tard'; ?></p>
                </div>

                <!-- STATUT DE PAIEMENT -->
                <div class="info-box">
                    <h4>Statut du paiement</h4>
                    <p class="statut-badge"><?php echo htmlspecialchars($commande['statut_paiement']); ?></p>
                </div>

                <?php if ($_SESSION['role'] === 'livreur'): ?>
                    <div class="form-box">
                        <h4>Statut de la livraison</h4>
                        <form method="post" id="form-statut-ajax">
                            <input type="hidden" name="id_commande" value="<?php echo htmlspecialchars($commande['id']); ?>" />
                            <select name="nouveau_statut" class="statusselect">
                                <option value="terminée" <?php echo $commande['statut_commande'] === 'terminée' ? 'selected' : ''; ?>>Terminée</option>
                                <option value="abandonnée" <?php echo $commande['statut_commande'] === 'abandonnée' ? 'selected' : ''; ?>>Abandonnée</option>
                            </select>
                            <button type="submit" class="btn-brutal btn-status">Enregistrer</button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="form-box">
                        <h4>Statut de la commande</h4>
                        <form method="post" id="form-statut-ajax">
                            <input type="hidden" name="id_commande" value="<?php echo htmlspecialchars($commande['id']); ?>" />
                            <select name="nouveau_statut" class="statusselect">
                                <option value="en attente" <?php echo $commande['statut_commande'] === 'en attente' ? 'selected' : ''; ?>>En attente</option>
                                <option value="a livrée" <?php echo $commande['statut_commande'] === 'a livrée' ? 'selected' : ''; ?>>À livrée</option>
                                <option value="en livraison" <?php echo $commande['statut_commande'] === 'en livraison' ? 'selected' : ''; ?>>En livraison</option>
                                <option value="terminée" <?php echo $commande['statut_commande'] === 'terminée' ? 'selected' : ''; ?>>Terminée</option>
                                <option value="abandonnée" <?php echo $commande['statut_commande'] === 'abandonnée' ? 'selected' : ''; ?>>Abandonnée</option>
                            </select>
                            <button type="submit" class="btn-brutal btn-status">Valider le nouveau statut</button>
                        </form>
                    </div>

                    <div class="form-box">
                        <h4>Assigner à un livreur</h4>
                        <form method="post" id="form-livreur-ajax">
                            <input type="hidden" name="id_commande" value="<?php echo htmlspecialchars($commande['id']); ?>" />
                            <?php if (!empty($livreurs)): ?>
                                <select name="id_livreur" class="livreur-select">
                                    <option value="">-- Sélectionner un livreur --</option>
                                    <?php foreach ($livreurs as $livreur_item): ?>
                                        <option value="<?php echo $livreur_item['id']; ?>" <?php echo isset($commande['livreur']) && $commande['livreur'] === $livreur_item['id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($livreur_item['prenom'] . ' ' . $livreur_item['nom']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn-brutal btn-livreur">Assigner le livreur</button>
                            <?php else: ?>
                                <p class="no-livreurs">Aucun livreur disponible</p>
                            <?php endif; ?>
                        </form>
                    </div>
                <?php endif; ?>

                <!-- INFOS CLIENT -->
                <?php if ($commande['user_id']): ?>
                    <?php $user = getInfoUser($commande['user_id']); ?>
                    <?php if ($user): ?>
                        <div class="info-box client-info">
                            <h4>Client</h4>
                            <p><strong><?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></strong></p>
                            <p><?php echo htmlspecialchars($user['email']); ?></p>
                            <p><?php echo htmlspecialchars($user['tel']); ?></p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="info-box">
                        <h4>Client</h4>
                        <p>Commande anonyme</p>
                    </div>
                <?php endif; ?>
            </section>
        </div>

    </main>

    <?php include '../includes/footer.html'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Changement de Statut
            const formStatut = document.getElementById('form-statut-ajax');
            if (formStatut) {
                formStatut.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const fd = new FormData(this);
                    fd.append('action', 'update_order_status');

                    fetch('ajax_handler.php', { method: 'POST', body: fd })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) {
                            // On modifie l'affichage en direct
                            document.querySelector('.statut-badge').innerText = data.nouveau_statut;
                            alert('Statut mis à jour avec succès : ' + data.nouveau_statut);
                        } else {
                            alert('Erreur: ' + data.message);
                        }
                    });
                });
            }

            // Assignation Livreur
            const formLivreur = document.getElementById('form-livreur-ajax');
            if (formLivreur) {
                formLivreur.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const fd = new FormData(this);
                    fd.append('action', 'assign_livreur');

                    fetch('ajax_handler.php', { method: 'POST', body: fd })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) {
                            alert('Livreur assigné avec succès !');
                        } else {
                            alert('Erreur: ' + data.message);
                        }
                    });
                });
            }
        });
    </script>

</body>
</html>
