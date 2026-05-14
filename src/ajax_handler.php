<?php
session_start();
require_once '../includes/fonctions.php';
verifierUtilisateurBloque();

$cheminFichier = '../data/users.json';
$action = $_POST['action'] ?? '';
$idSaisi = isset($_POST['id']) ? (int)$_POST['id'] : 0;

// --- ACTION 1 : BLOQUER UN UTILISATEUR (RESERVÉ ADMIN) ---
if ($action === 'toggle_block') {
    // La sécurité admin est UNIQUEMENT ici
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
            break;
        }
    }

    if ($success) {
        file_put_contents($cheminFichier, json_encode($utilisateurs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo json_encode(['success' => true, 'estBloque' => $nouvelEtat]);
    }
    exit();
}

// --- ACTION 2 : MODIFIER SON PROFIL (CLIENT OU ADMIN) ---
if ($action === 'update_profile') {
    
    // SÉCURITÉ : L'utilisateur doit être connecté ET ne peut modifier que son PROPRE ID
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
// --- ACTION 3 : FILTRER LE MENU ---
if ($action === 'filter_menu') {
    require_once '../includes/fonctions.php';
    
    $catDemandee = $_POST['categorie'] ?? 'tous';
    $filtreDemande = $_POST['filtre'] ?? 'tous'; 
    
    $plats = getTousLesPlats();
    
    echo '<div class="product-grid">';
    
    foreach ($plats as $p) {
        // Condition 1 : La catégorie (bowls, wraps, etc.)
        $matchCategorie = ($catDemandee === 'tous') || ($p['categorie'] === $catDemandee);
        
        // Condition 2 : Le régime (seulement si "vegetarien" est demandé)
        $matchFiltre = ($filtreDemande === 'tous') || 
                       (isset($p['regime']) && $p['regime'] === 'vegetarien');

        if ($matchCategorie && $matchFiltre) {
            ?>
            <div class="product-card" data-price="<?= $p['prix'] ?>" data-orders="<?= $p['commandes'] ?? 0 ?>">
                <img src="<?= htmlspecialchars($p['image']) ?>" class="product-image">
                <h3 class="product-name"><?= htmlspecialchars($p['nom']) ?></h3>
                <div class="product-action">
                    <span class="product-price"><?= number_format($p['prix'], 2, '.', ' ') ?> €</span>
                    <button class="btn-brutal btn-small">AJOUTER</button>
                </div>
            </div>
            <?php
        }
    }
    echo '</div>';
    exit();
}
   

// Si aucune action ne correspond
echo json_encode(['success' => false, 'message' => 'Action inconnue']);
?>