<?php
session_start();
require_once '../includes/fonctions.php';
verifierUtilisateurBloque();

$cheminFichier = '../data/users.json';
$action = $_POST['action'] ?? '';
$idSaisi = isset($_POST['id']) ? (int)$_POST['id'] : 0;

// BLOQUER UN UTILISATEUR (RESERVÉ ADMIN) 
if ($action === 'toggle_block') {
    // La sécurité admin ici
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        echo json_encode(['success' => false, 'message' => 'Accès refusé : réservé à l\'admin']);
        exit();
    }

    $utilisateurs = json_decode(file_get_contents($cheminFichier), true);
    $success = false;

    foreach ($utilisateurs as &$user) {
        if ($user['id'] === $idSaisi) {
            $user['bloqué'] = !$user['bloqué'];
            $success = true;
            $nouvelEtat = $user['bloqué'];
            
            
            $terme_action = $nouvelEtat ? "BLOQUÉ" : "DÉBLOQUÉ";
            $admin_identite = isset($_SESSION['user']) ? "ID: " . $_SESSION['user'] : "ID inconnu";
            
            ajouterLog('ADMIN_ACTION', "L'administrateur (" . $admin_identite . ") a " . $terme_action . " l'utilisateur (ID : " . $idSaisi . " | Email : " . $user['email'] . ").");
            
            break;
        }
    }

    if ($success) {
        file_put_contents($cheminFichier, json_encode($utilisateurs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo json_encode(['success' => true, 'estBloque' => $nouvelEtat]);
    }
    exit();
}

// MODIFIER PROFIL
if ($action === 'update_profile') {
    
    // L'utilisateur doit être connecté ET ne peut modifier que son PROPRE ID
    if (!isset($_SESSION['user']) || (int)$_SESSION['user'] !== $idSaisi) {
        echo json_encode(['success' => false, 'message' => 'Accès refusé : vous ne pouvez pas modifier ce profil']);
        exit();
    }

    $utilisateurs = json_decode(file_get_contents($cheminFichier), true);
    $success = false;

    foreach ($utilisateurs as &$user) {
        if ((int)$user['id'] === $idSaisi) {
            $user['prenom'] = htmlspecialchars($_POST['prenom']);
            $user['nom'] = htmlspecialchars($_POST['nom']);
            $user['email'] = htmlspecialchars($_POST['email']);
            $user['tel'] = htmlspecialchars($_POST['tel']);
            
            // On met à jour la session pour que l'affichage change partout
            $_SESSION['prenom'] = $user['prenom'];
            $_SESSION['nom'] = $user['nom'];
            $_SESSION['email'] = $user['email'];
            
            $success = true;
            break;
        }
    }

    if ($success) {
        file_put_contents($cheminFichier, json_encode($utilisateurs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Utilisateur introuvable']);
    }
    exit();
}
// FILTRER LE MENU
if ($action === 'filter_menu') {
    require_once '../includes/fonctions.php';
    
    $catDemandee = $_POST['categorie'] ?? 'tous';
    $filtreDemande = $_POST['filtre'] ?? 'tous'; 
    
    $plats = getTousLesPlats();
    
    echo '<div class="product-grid">';
    
    foreach ($plats as $p) {
        //  La catégorie (bowls, wraps, etc.)
        $matchCategorie = ($catDemandee === 'tous') || ($p['categorie'] === $catDemandee);
        
        // Le régime (vege ou pas)
        $matchFiltre = ($filtreDemande === 'tous') || 
                       (isset($p['regime']) && $p['regime'] === 'vegetarien');

        if ($matchCategorie && $matchFiltre) {
            ?>
            <div class="product-card" data-price="<?= $p['prix'] ?>" data-orders="<?= $p['commandes'] ?? 0 ?>">
                <img src="<?= htmlspecialchars($p['image']) ?>" class="product-image">
                <h3 class="product-name"><?= htmlspecialchars($p['nom']) ?></h3>
                <div class="product-action">
                    <span class="product-price"><?= number_format($p['prix'], 2, '.', ' ') ?> €</span>
                    <form method="POST" action="traitement_ajouter_panier.php" style="margin:0;">
                        <input type="hidden" name="action" value="ajouter">
                        <input type="hidden" name="id_produit" value="<?= $p['id'] ?>">
                        <button type="submit" class="btn-brutal btn-small">AJOUTER</button>
                    </form>
                </div>
            </div>
            <?php
        }
    }
    echo '</div>';
    exit();
}

// MODIFIER LE STATUT D'UNE COMMANDE (RESTAURATEUR / LIVREUR) 
if ($action === 'update_order_status') {
    $id_commande = (int)$_POST['id_commande'];
    $nouveau_statut = $_POST['nouveau_statut'];

    // Sécurité : Réservé aux rôles concernés
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['restaurateur', 'admin', 'livreur'])) {
        echo json_encode(['success' => false, 'message' => 'Accès refusé']);
        exit();
    }

    if (mettreAJourStatutCommande($id_commande, $nouveau_statut)) {
        echo json_encode(['success' => true, 'nouveau_statut' => $nouveau_statut]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
    }
    exit();
}

// ASSIGNER UN LIVREUR 
if ($action === 'assign_livreur') {
    $id_commande = (int)$_POST['id_commande'];
    $id_livreur = (int)$_POST['id_livreur'];

    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['restaurateur', 'admin'])) {
        echo json_encode(['success' => false, 'message' => 'Accès refusé']);
        exit();
    }

    $livreur_final = ($id_livreur === 0) ? null : $id_livreur;
    if (attribuerLivreurCommande($id_commande, $livreur_final)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur d\'assignation']);
    }
    exit();
}

// MODIFIER LA QUANTITÉ D'UN ARTICLE DANS UNE COMMANDE 
if ($action === 'modify_order_quantity') {
    $id_commande = (int)$_POST['id_commande'];
    $id_produit = (int)$_POST['id_produit'];
    $changement = (int)$_POST['changement']; // +1 ou -1

    $commandes = getToutesLesCommandes();
    $success = false;
    $montant_supplementaire = 0;

    foreach ($commandes as &$c) {
        // La commande doit être au client et ne pas être commencée
        if ($c['id'] === $id_commande && $c['user_id'] == $_SESSION['user'] && $c['statut_commande'] === 'en attente') {
            foreach ($c['articles'] as $k => &$art) {
                if ($art['id_produit'] == $id_produit) {
                    $art['quantite'] += $changement;
                    if ($art['quantite'] <= 0) {
                        unset($c['articles'][$k]); // Retire l'article s'il tombe à 0
                    }
                    
                    // Recalcul du montant
                    $ancien_prix = floatval($c['montant_payé']);
                    $details = calculerDetailCommande($c); // Fonction existante dans fonctions.php
                    $nouveau_prix = $details['prix_apres_remise'];
                    
                    if ($nouveau_prix > $ancien_prix) {
                        $montant_supplementaire = $nouveau_prix - $ancien_prix;
                    }
                    
                    $c['montant_payé'] = $nouveau_prix;
                    $c['articles'] = array_values($c['articles']); // Réindexer le tableau JSON
                    $success = true;
                    break;
                }
            }
            break;
        }
    }

    if ($success) {
        enregistrerToutesLesCommandes($commandes);
        echo json_encode(['success' => true, 'montant_supplementaire' => $montant_supplementaire]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Modification impossible à ce stade.']);
    }
    exit();
}

// Si aucune action ne correspond
echo json_encode(['success' => false, 'message' => 'Action inconnue']);
?>