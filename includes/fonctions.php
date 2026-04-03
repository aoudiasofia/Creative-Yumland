<?php

function getToutesLesProduits() {
    $fichier_chemin = __DIR__ . '/../data/produits.json';
    
    if (file_exists($fichier_chemin)) {
        $contenu_json = file_get_contents($fichier_chemin);
        
        return json_decode($contenu_json, true);
    } else {
        return ['plats' => [], 'menus' => []];
    }
}

function getTousLesPlats() {
    $donnees = getToutesLesProduits();
    return $donnees['plats'];
}

function getTousLesMenus() {
    $donnees = getToutesLesProduits();
    return $donnees['menus'];
}

function getPlatsParCategorie() {
    $tous_les_plats = getTousLesPlats();
    $plats_par_categorie = [];

    foreach ($tous_les_plats as $plat) {
        $categorie = $plat['categorie'];
        if (!isset($plats_par_categorie[$categorie])) {
            $plats_par_categorie[$categorie] = [];
        }
        $plats_par_categorie[$categorie][] = $plat;
    }
    return $plats_par_categorie;
}

function getPlatById($id_recherche) {
    $plats = getTousLesPlats();

    foreach ($plats as $plat) {
        if ($plat['id'] === $id_recherche) {
            return $plat;
        }
    }

    return null;
}

function getMenuById($id_recherche) {
    $menus = getTousLesMenus();

    foreach ($menus as $menu) {
        if ($menu['id'] === $id_recherche) {
            return $menu;
        }
    }

    return null;
}

function initialiserPanier() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }
}

function ajouterAuPanier($id_produit, $quantite = 1) {
    initialiserPanier();
    
    if (isset($_SESSION['panier'][$id_produit])) {
        $_SESSION['panier'][$id_produit] += $quantite;
    } else {
        $_SESSION['panier'][$id_produit] = $quantite;
    }
}

function calculerTotalPanier() {
    initialiserPanier();
    $total = 0.0;
    
    foreach ($_SESSION['panier'] as $id_produit => $quantite) {
        $plat = getPlatById($id_produit);
        if ($plat) {
            $total += $plat['prix'] * $quantite;
        }
    }
    
    return $total;
}

function viderPanier() {
    initialiserPanier();
    $_SESSION['panier'] = [];
}

function getToutesLesCommandes() {
    $fichier_chemin = __DIR__ . '/../data/commandes.json';
    
    if (file_exists($fichier_chemin)) {
        $contenu_json = file_get_contents($fichier_chemin);
        
        return json_decode($contenu_json, true);
    } else {
        return [];
    }
}

function getTousLesUtilisateurs() {
    $fichier_chemin = __DIR__ . '/../data/users.json';
    
    if (file_exists($fichier_chemin)) {
        $contenu_json = file_get_contents($fichier_chemin);
        $users = json_decode($contenu_json, true);
        
        if (!is_array($users)) {
            return [];
        }
        
        return $users;
    } else {
        return [];
    }
}

function getTousLesLivreurs() {
    $fichier_chemin = __DIR__ . '/../data/users.json';
    
    if (file_exists($fichier_chemin)) {
        $contenu_json = file_get_contents($fichier_chemin);
        $users = json_decode($contenu_json, true);
        
        $livreurs = array_filter($users, function($user) {
            return isset($user['role']) && $user['role'] === 'livreur';
        });
        
        return array_values($livreurs);
    } else {
        return [];
    }
}

function getCommandesByUserId($user_id) {
    $toutes_commandes = getToutesLesCommandes();
    $commandes_utilisateur = array_filter($toutes_commandes, function($commande) use ($user_id) {
        return isset($commande['user_id']) && $commande['user_id'] == $user_id;
    });
    return array_values($commandes_utilisateur);
}

function enregistrerToutesLesCommandes($commandes) {
    $fichier_chemin = __DIR__ . '/../data/commandes.json';
    $json = json_encode($commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    if (file_put_contents($fichier_chemin, $json) !== false) {
        return true;
    }

    return false;
}

function mettreAJourStatutCommande($id_commande, $nouveau_statut) {
    $commandes = getToutesLesCommandes();
    $trouve = false;

    foreach ($commandes as &$commande) {
        if ($commande['id'] === $id_commande) {
            $commande['statut_commande'] = $nouveau_statut;
            $trouve = true;
            break;
        }
    }

    if ($trouve) {
        return enregistrerToutesLesCommandes($commandes);
    }

    return false;
}

function attribuerLivreurCommande($id_commande, $id_livreur) {
    $commandes = getToutesLesCommandes();
    $trouve = false;

    foreach ($commandes as &$commande) {
        if ($commande['id'] === $id_commande) {
            $commande['livreur'] = $id_livreur;
            $trouve = true;
            break;
        }
    }

    if ($trouve) {
        return enregistrerToutesLesCommandes($commandes);
    }

    return false;
}

function getDernierId($fichier_json) {
    if (!file_exists($fichier_json)) {
        return false; // Si le fichier n'existe pas
    }
    
    $contenu_json = file_get_contents($fichier_json);
    $donnees = json_decode($contenu_json, true);
    
    if (!is_array($donnees)) {
        return 0; // Si le JSON n'est pas valide ou vide
    }
    
    $dernier_id = 0;
    foreach ($donnees as $element) {
        if (isset($element['id']) && $element['id'] > $dernier_id) {
            $dernier_id = $element['id'];
        }
    }
    
    return $dernier_id;
}

function verifierConnexion($email, $password) {
    $fichier_users = __DIR__ . '/../data/users.json';
    
    if (!file_exists($fichier_users)) {
        return false;
    }
    
    $contenu_json = file_get_contents($fichier_users);
    $users = json_decode($contenu_json, true);
    
    if (!is_array($users)) {
        return false;
    }
    
    foreach ($users as $user) {
        if (isset($user['email']) && isset($user['password'])) {
            if ($user['email'] === $email && $user['password'] === $password) {
                return $user['id'];
            }
        }
    }
    
    return false;
}

function getInfoUser($user_id) {
    $fichier_users = __DIR__ . '/../data/users.json';
    
    if (!file_exists($fichier_users)) {
        return null; // Fichier inexistant
    }
    
    $contenu_json = file_get_contents($fichier_users);
    $users = json_decode($contenu_json, true);
    
    if (!is_array($users)) {
        return null; // JSON invalide
    }
    
    foreach ($users as $user) {
        if (isset($user['id']) && $user['id'] == $user_id) {
            // Retourner toutes les infos sauf le mot de passe pour des raisons de sécurité
            unset($user['password']);
            return $user;
        }
    }
    
    return null; // Utilisateur non trouvé
}

function creerNouvelUtilisateur($nom, $prenom, $mdp, $email, $tel, $adresse, $infos) {
    $fichier_users = __DIR__ . '/../data/users.json';
    
    $nouvel_user = [
        "id" => getDernierId($fichier_users) + 1,
        "nom" => strtoupper($nom),
        "prenom" => $prenom,
        "email" => $email,
        "password" => $mdp,
        "tel" => $tel,
        "role" => "client",
        "adresse" => $adresse,
        "infosup" => $infos,
        "statut" => "moldu",
        "remise" => "0",
        "bloqué" => false
    ];
    
    $users = [];
    if (file_exists($fichier_users)) {
        $contenu_json = file_get_contents($fichier_users);
        $users = json_decode($contenu_json, true);
        if (!is_array($users)) {
            $users = [];
        }
    }
    
    $users[] = $nouvel_user;
    
    if (file_put_contents($fichier_users, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        return true; // Succès
    } else {
        return false; // Échec
    }
}

function existeDeja($email) {
    $fichier_users = __DIR__ . '/../data/users.json';
    
    if (!file_exists($fichier_users)) {
        return false; // Si le fichier n'existe pas, l'email n'existe pas
    }
    
    $contenu_json = file_get_contents($fichier_users);
    $users = json_decode($contenu_json, true);
    
    if (!is_array($users)) {
        return false; // JSON invalide
    }
    
    foreach ($users as $user) {
        if (isset($user['email']) && strtolower($user['email']) === strtolower($email)) {
            return true; // Email trouvé
        }
    }
    
    return false; // Email non trouvé
}

function getCommandeById($id_recherche) {
    $commandes = getToutesLesCommandes();
    
    foreach ($commandes as $commande) {
        if ($commande['id'] === $id_recherche) {
            return $commande;
        }
    }
    
    return null;
}

function calculerDetailCommande($commande) {
    $articles_detail = [];
    $prix_total_avant_remise = 0;
    
    foreach ($commande['articles'] as $article) {
        $type = isset($article['type']) ? $article['type'] : 'plats';
        $id_produit = $article['id_produit'];
        $quantite = $article['quantite'];
        
        if ($type === 'plats') {
            $plat = getPlatById($id_produit);
            if ($plat) {
                $prix_unitaire = $plat['prix'];
                $prix_total_article = $prix_unitaire * $quantite;
                $prix_total_avant_remise += $prix_total_article;
                
                $articles_detail[] = [
                    'type' => 'plat',
                    'nom' => $plat['nom'],
                    'description' => $plat['description'],
                    'quantite' => $quantite,
                    'prix_unitaire' => $prix_unitaire,
                    'prix_total' => $prix_total_article
                ];
            }
        } elseif ($type === 'menu') {
            $menu = getMenuById($id_produit);
            if ($menu) {
                $prix_unitaire = $menu['prix'];
                $prix_total_article = $prix_unitaire * $quantite;
                $prix_total_avant_remise += $prix_total_article;
                
                // Récupérer les plats du menu
                $plats_du_menu = [];
                foreach ($menu['plats_inclus'] as $id_plat) {
                    $plat = getPlatById($id_plat);
                    if ($plat) {
                        $plats_du_menu[] = [
                            'nom' => $plat['nom'],
                            'description' => $plat['description'],
                            'prix' => $plat['prix']
                        ];
                    }
                }
                
                $articles_detail[] = [
                    'type' => 'menu',
                    'nom' => $menu['nom'],
                    'description' => $menu['description'],
                    'quantite' => $quantite,
                    'prix_unitaire' => $prix_unitaire,
                    'prix_total' => $prix_total_article,
                    'plats_inclus' => $plats_du_menu
                ];
            }
        }
    }
    
    $remise = isset($commande['remise']) ? $commande['remise'] : 0;
    $prix_apres_remise = $prix_total_avant_remise - $remise;
    
    return [
        'articles' => $articles_detail,
        'prix_total_avant_remise' => $prix_total_avant_remise,
        'remise' => $remise,
        'prix_apres_remise' => $prix_apres_remise
    ];
}

function ajouterNotationCommande($id_commande, $notation, $commentaire = '') {
    $commandes = getToutesLesCommandes();
    $trouve = false;

    foreach ($commandes as &$commande) {
        if ($commande['id'] === $id_commande) {
            $commande['notation'] = intval($notation);
            $commande['commentaire'] = $commentaire;
            $trouve = true;
            break;
        }
    }

    if ($trouve) {
        return enregistrerToutesLesCommandes($commandes);
    }

    return false;
}

?>