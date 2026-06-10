<?php // focntions

function ajouterAuPanier($id_produit, $quantite = 1) {
    initialiserPanier();
    
    if (isset($_SESSION['panier'][$id_produit])) {
        $_SESSION['panier'][$id_produit] += $quantite;
    } else {
        $_SESSION['panier'][$id_produit] = $quantite;
    }
}

function ajouterLog($type, $details) {
    $chemin_log = __DIR__ . '/../data/incidents.log';
    
    $date_heure = date('Y-m-d H:i:s');
    
    $ligne_log = "[" . $date_heure . "] [" . strtoupper($type) . "] " . $details . PHP_EOL;
    
    file_put_contents($chemin_log, $ligne_log, FILE_APPEND | LOCK_EX);
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


function calculerDetailCommande($commande) {
    $details = [
        'articles' => [],
        'prix_total_avant_remise' => 0,
        'remise' => 0,
        'prix_apres_remise' => 0
    ];

    // MODIFICATION ICI : On accepte 'items' OU 'articles'
    $liste_brute = null;
    if (!empty($commande)) {
        if (isset($commande['items'])) {
            $liste_brute = $commande['items'];
        } elseif (isset($commande['articles'])) {
            $liste_brute = $commande['articles'];
        }
    }

    if (empty($liste_brute) || !is_array($liste_brute)) {
        return $details;
    }

    // On récupère la base de données des produits (plats et menus)
    $produits_data = getToutesLesProduits(); 
    $tous_les_plats = $produits_data['plats'] ?? [];
    $tous_les_menus = $produits_data['menus'] ?? [];

    foreach ($liste_brute as $item) {
        // Sécurité supplémentaire : On gère si la clé s'appelle 'id_produit' ou juste 'id'
        $id_item = $item['id_produit'] ?? ($item['id'] ?? null);
        $type_item = $item['type'] ?? 'plat'; // 'plat' ou 'menu'
        $quantite = intval($item['quantite'] ?? 1);

        $nom = "Produit indisponible";
        $description = "";
        $prix_unitaire = 0;
        $plats_inclus_details = [];

        if ($type_item === 'menu') {
            // 1. CHERCHER DANS LES MENUS
            foreach ($tous_les_menus as $menu) {
                if ($menu['id'] == $id_item) {
                    $nom = $menu['nom'];
                    $description = $menu['description'] ?? '';
                    $prix_unitaire = floatval($menu['prix']);
                    
                    // Récupérer les détails des plats inclus dans ce menu
                    if (isset($menu['plats']) && is_array($menu['plats'])) {
                        foreach ($menu['plats'] as $id_plat_inclus) {
                            foreach ($tous_les_plats as $plat) {
                                if ($plat['id'] == $id_plat_inclus) {
                                    $plats_inclus_details[] = [
                                        'nom' => $plat['nom'],
                                        'prix' => floatval($plat['prix'])
                                    ];
                                }
                            }
                        }
                    }
                    break;
                }
            }
        } else {
            // 2. CHERCHER DANS LES PLATS SIMPLES
            foreach ($tous_les_plats as $plat) {
                if ($plat['id'] == $id_item) {
                    $nom = $plat['nom'];
                    $description = $plat['description'] ?? '';
                    $prix_unitaire = floatval($plat['prix']);
                    break;
                }
            }
        }

        $prix_total_article = $prix_unitaire * $quantite;

        $details['articles'][] = [
            'id_produit' => $id_item, // Ajouté pour le bouton de modification de quantité
            'nom' => $nom,
            'type' => $type_item,
            'description' => $description,
            'quantite' => $quantite,
            'prix_unitaire' => $prix_unitaire,
            'prix_total' => $prix_total_article,
            'plats_inclus' => $plats_inclus_details
        ];

        $details['prix_total_avant_remise'] += $prix_total_article;
    }

    // Gestion des remises et totaux finaux
    $details['remise'] = floatval($commande['remise'] ?? 0);
    
    // Si le montant payé est déjà écrit en dur dans la commande, on le garde en priorité
    if (isset($commande['montant_payé']) && floatval($commande['montant_payé']) > 0) {
        $details['prix_apres_remise'] = floatval($commande['montant_payé']);
    } else {
        $details['prix_apres_remise'] = $details['prix_total_avant_remise'] - $details['remise'];
    }

    // Backwards compatibility 
    $details['total'] = $details['prix_apres_remise'];
    $details['items'] = $details['articles'];

    return $details;
}



function creerNouvelUtilisateur($nom, $prenom, $mdp, $email, $tel, $adresse, $infos) {
    $fichier_users = __DIR__ . '/../data/users.json';
    
    $mdp_hache = password_hash($mdp, PASSWORD_DEFAULT);
    
    $nouvel_user = [
        "id" => getDernierId($fichier_users) + 1,
        "nom" => strtoupper($nom),
        "prenom" => $prenom,
        "email" => $email,
        "password" => $mdp_hache, // Stockage de la clé de hachage sécurisée
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
        return true; 
    } else {
        return false; 
    }
}

function creerNouvelleCommande($user_id, $articles, $quand, $adresse_livraison, $statut_paiement, $montant_paye, $remise = 0) {
    $commandes = getToutesLesCommandes();
    $nouveau_id = getDernierId(__DIR__ . '/../data/commandes.json') + 1;
    $date_heure = date('Y-m-d H:i');
    
    // Statut commande initial
    $statut_commande = ($statut_paiement === 'payé') ? 'en attente' : 'en attente'; // Peut ajuster selon logique
    
    $nouvelle_commande = [
        'id' => $nouveau_id,
        'user_id' => $user_id,
        'articles' => $articles,
        'quand' => $quand,
        'adresse_livraison' => $adresse_livraison,
        'statut_paiement' => $statut_paiement,
        'statut_commande' => $statut_commande,
        'date_heure' => $date_heure,
        'montant_payé' => $montant_paye,
        'remise' => $remise,
        'livreur' => null,
        'notation' => null,
        'commentaire' => null
    ];
    
    $commandes[] = $nouvelle_commande;
    
    return enregistrerToutesLesCommandes($commandes) ? $nouveau_id : false;
}

function enregistrerToutesLesCommandes($commandes) {
    $fichier_chemin = __DIR__ . '/../data/commandes.json';
    $json = json_encode($commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    if (file_put_contents($fichier_chemin, $json) !== false) {
        return true;
    }

    return false;
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

function getCommandesByUserId($user_id) {
    $toutes_commandes = getToutesLesCommandes();
    $commandes_utilisateur = array_filter($toutes_commandes, function($commande) use ($user_id) {
        return isset($commande['user_id']) && $commande['user_id'] == $user_id;
    });
    return array_values($commandes_utilisateur);
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

function getInfoUser($identifiant) {
    // 1. On vérifie le chemin
    $chemin = __DIR__ . '/../data/users.json';
    if (!file_exists($chemin)) {
        die("Erreur critique : Le fichier JSON est introuvable à cet endroit : " . $chemin);
    }

    $users = json_decode(file_get_contents($chemin), true);

    foreach ($users as $user) {
        // On teste l'ID (en forçant la comparaison en texte pour éviter les bugs)
        $matchId = (string)$user['id'] === (string)$identifiant;
        // On teste l'Email
        $matchEmail = strtolower($user['email']) === strtolower($identifiant);

        if ($matchId || $matchEmail) {
            unset($user['password']);
            return $user;
        }
    }
    return null;
}

function getMenuById($id_recherche) {
    $menus = getTousLesMenus();

    foreach ($menus as $menu) {
        if ($menu['id'] == $id_recherche) {
            return $menu;
        }
    }

    return null;
}

function getPanier(){
    // Assure que la session et le panier existent
    initialiserPanier();

    $articles_panier = [];
    foreach ($_SESSION['panier'] as $cle => $item) {
        // Supporte deux formes : $_SESSION['panier'][id] = quantite  OU  $_SESSION['panier'][] = ['id'=>..., 'quantite'=>...]
        if (is_array($item)) {
            $id_produit = isset($item['id']) ? (int)$item['id'] : (int)$cle;
            $quantite = isset($item['quantite']) ? (int)$item['quantite'] : (int)($item['quantity'] ?? 0);
        } else {
            $id_produit = (int)$cle;
            $quantite = (int)$item;
        }

        $plat = getPlatById($id_produit);
        if (!$plat) continue;

        // Garantit que le prix est un nombre (si 'prix' est malformé, on tombe à 0.0)
        $prix_unitaire = 0.0;
        if (isset($plat['prix'])) {
            if (is_array($plat['prix'])) {
                // si c'est un tableau, cherche une première valeur numérique
                foreach ($plat['prix'] as $v) {
                    if (is_numeric($v)) { $prix_unitaire = (float)$v; break; }
                }
            } elseif (is_numeric($plat['prix'])) {
                $prix_unitaire = (float)$plat['prix'];
            }
        }

        $articles_panier[] = [
            'plat' => $plat,
            'quantite' => $quantite,
            'prix_total' => $prix_unitaire * $quantite
        ];
    }
    return $articles_panier;
}

function getPlatById($id_recherche) {
    $plats = getTousLesPlats();

    foreach ($plats as $plat) {
        if ($plat['id'] == $id_recherche) {
            return $plat;
        }
    }

    return null;
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

function getToutesLesCommandes() {
    $fichier_chemin = __DIR__ . '/../data/commandes.json';
    
    if (file_exists($fichier_chemin)) {
        $contenu_json = file_get_contents($fichier_chemin);
        
        return json_decode($contenu_json, true);
    } else {
        return [];
    }
}

function getToutesLesProduits() {
    $fichier_chemin = __DIR__ . '/../data/produits.json';
    
    if (file_exists($fichier_chemin)) {
        $contenu_json = file_get_contents($fichier_chemin);
        $donnees = json_decode($contenu_json, true);
        
        // On vérifie que le JSON est bien un tableau valide
        if (is_array($donnees)) {
            return $donnees;
        }
    }
    // Si le fichier n'existe pas ou est corrompu, on renvoie une structure vide propre
    return ['plats' => [], 'menus' => []];
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

function getTousLesMenus() {
    $donnees = getToutesLesProduits();
    return $donnees['menus'] ?? [];
}

function getTousLesPlats() {
    $donnees = getToutesLesProduits();
    // Le ?? [] garantit de renvoyer un tableau vide si la clé "plats" n'existe pas
    return $donnees['plats'] ?? [];
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

function initialiserPanier() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }
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
            if (strtolower($user['email']) === strtolower($email)) {
                // TÂCHE 1 : Vérification du mot de passe haché
                if (password_verify($password, $user['password'])) {
                    return $user['id'];
                } else {
                    // TRIGER 1 : Le mot de passe associé à cet e-mail est incorrect
                    ajouterLog('CONNEXION_ECHEC', "Mot de passe incorrect pour la tentative sur le compte : " . $email);
                    return false;
                }
            }
        }
    }
    
    ajouterLog('CONNEXION_ECHEC', "Tentative de connexion avec un e-mail non enregistré : " . $email);
    return false;
}

function viderPanier() {
    initialiserPanier();
    $_SESSION['panier'] = [];
}

function verifierEtJouerRoulette($id_user, $id_commande) {
    $fichier_commandes = __DIR__ . '/../data/commandes.json';
    if (!file_exists($fichier_commandes)) return false;

    $commandes = json_decode(file_get_contents($fichier_commandes), true);
    $deja_joue = false;
    $commande_existe = false;
    $index_commande = -1;

    // 1. Utilisation de == (comparaison souple) pour éviter le conflit string/int de l'ID
    foreach ($commandes as $index => $commande) {
        if ($commande['id'] == $id_commande && $commande['user_id'] == $id_user) {
            $commande_existe = true;
            $index_commande = $index;
            if (isset($commande['roulette_jouee']) && $commande['roulette_jouee'] === true) {
                $deja_joue = true;
            }
            break;
        }
    }

    if (!$commande_existe || $deja_joue) {
        return false;
    }

    // 2. Tirage au sort
    $tirage = rand(1, 100);
    if ($tirage <= 50) {
        $remise_pourcent = 0; 
    } elseif ($tirage <= 90) {
        $remise_pourcent = 5; 
    } else {
        $remise_pourcent = 10; 
    }

    $fichier_users = __DIR__ . '/../data/users.json';
    $users = json_decode(file_get_contents($fichier_users), true);

    // 3. Enregistrement temporaire dans le profil utilisateur
    if ($remise_pourcent > 0) {
        foreach ($users as &$user) {
            if ($user['id'] == $id_user) {
                $user['remise'] = (string)$remise_pourcent;
                break;
            }
        }
        file_put_contents($fichier_users, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    // 4. Détection dynamique de la clé de prix utilisée dans votre JSON
    $cle_montant = 'montant_payé';
    if (isset($commandes[$index_commande]['total'])) {
        $cle_montant = 'total';
    } elseif (isset($commandes[$index_commande]['prix_total'])) {
        $cle_montant = 'prix_total';
    } elseif (isset($commandes[$index_commande]['prix'])) {
        $cle_montant = 'prix';
    }

    // 5. Application de la remise sur la commande
    $commandes[$index_commande]['roulette_jouee'] = true;
    if ($remise_pourcent > 0) {
        $montant_actuel = floatval($commandes[$index_commande][$cle_montant]);
        $montant_remise = round($montant_actuel * ($remise_pourcent / 100), 2);
        
        if (!isset($commandes[$index_commande]['remise'])) {
            $commandes[$index_commande]['remise'] = 0;
        }
        $commandes[$index_commande]['remise'] += $montant_remise;
        $commandes[$index_commande][$cle_montant] = max(0, $montant_actuel - $montant_remise);
    }
    file_put_contents($fichier_commandes, json_encode($commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    // 6. Nettoyage de la remise utilisateur immédiat
    if ($remise_pourcent > 0) {
        $users = json_decode(file_get_contents($fichier_users), true);
        foreach ($users as &$user) {
            if ($user['id'] == $id_user) {
                $user['remise'] = "0";
                break;
            }
        }
        file_put_contents($fichier_users, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    return $remise_pourcent;
}

function verifierUtilisateurBloque() {
    if (isset($_SESSION['user'])) {
        $idUser = (int)$_SESSION['user'];
        $utilisateurs = json_decode(file_get_contents(__DIR__ . '/../data/users.json'), true);

        foreach ($utilisateurs as $u) {
            if ($u['id'] === $idUser) {
                if (isset($u['bloqué']) && $u['bloqué'] === true) {
                    // TRIGGER 2 : Un utilisateur banni tente de forcer l'accès à l'environnement applicatif
                    ajouterLog('ACCES_FORCE', "L'utilisateur bloqué (ID : " . $idUser . " | Email : " . $u['email'] . ") a tenté de charger une page protégée.");

                    // L'utilisateur est bloqué dans le fichier -> On détruit sa session
                    session_unset();
                    session_destroy();
                    header('Location: connexion.php?erreur=compte_bloque');
                    exit();
                }
                break;
            }
        }
    }
}

function getPlatsPopulaires($limite = 3) {
    //  Chemin vers fichier JSON 
    $chemin = __DIR__ . '/../data/produits.json'; 
    if (!file_exists($chemin)) {
        return [];
    }

    // Décoder le fichier
    $data = json_decode(file_get_contents($chemin), true);
    
    // vérifie que la clé 'plats' existe bien
    if (!isset($data['plats']) || !is_array($data['plats'])) {
        return [];
    }

    $plats = $data['plats'];

    // Tri décroissant basé sur le champ 'commandes'
    usort($plats, function($a, $b) {
        $cmdA = $a['commandes'] ?? 0;
        $cmdB = $b['commandes'] ?? 0;
        return $cmdB <=> $cmdA; // Du plus commandé au moins commandé
    });

    // On extrait le TOP (le nombre de plats demandé)
    return array_slice($plats, 0, $limite);
}

function getProduitsParCategorie($categorie_recherchee) {
    // On récupère tous les plats du JSON
    $tous_les_plats = getTousLesPlats(); 
    $resultat = [];

    foreach ($tous_les_plats as $plat) {
        if (isset($plat['categorie']) && $plat['categorie'] === $categorie_recherchee) {
            // On ne garde que les infos essentielles pour le JavaScript [id, nom]
            $resultat[] = [
                'id' => $plat['id'],
                'nom' => $plat['nom']
            ];
        }
    }
    return $resultat;
}
function calculerTotalPanier() {
    if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
        return 0.0;
    }

    $total = 0.0;
    $liste_produits = getTousLesPlats(); 

    $prix_par_id = [];
    foreach ($liste_produits as $p) {
        $prix_par_id[$p['id']] = (float)$p['prix'];
    }

    // Parcours du panier 
    foreach ($_SESSION['panier'] as $cle => $item) {
        if (is_array($item)) {
            
            $id_produit = isset($item['id']) ? (int)$item['id'] : 0;
            $quantite = isset($item['quantite']) ? (int)$item['quantite'] : 0;
        } else {
            
            $id_produit = (int)$cle;
            $quantite = (int)$item;
        }

        $prix_article = isset($prix_par_id[$id_produit]) ? $prix_par_id[$id_produit] : 0.0;
        $total += $quantite * $prix_article;
    }

    return $total;
}

?>