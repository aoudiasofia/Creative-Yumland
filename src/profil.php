<?php
session_start();
require_once '../includes/fonctions.php';
//s'assurer que l'utilisateur est connecté et non bloqué
verifierUtilisateurBloque();

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

$user_data = getInfoUser($_SESSION['user']); 

if (!$user_data) {
    die("Erreur : Utilisateur introuvable. La session contient : " . htmlspecialchars($_SESSION['user']));
}
?>

<!DOCTYPE html>
<html lang="fr">
<?php 
    $titre_page = "KØLD | PROFIL";
    include '../includes/head.php';
?>

<body class="kold-mode">

    <?php 
        $nom_page = "profil";
        include '../includes/header.php';
    ?>

    <main class="profil-container" style="padding: 20px; max-width: 1200px; margin: 0 auto;">

        <h1 class="main-title" style="text-align: center; margin-bottom: 30px;">
            PROFIL // <span id="titre-nom-dynamique"><?php echo strtoupper(htmlspecialchars($user_data['prenom'] . " " . $user_data['nom'])); ?></span>
        </h1>

        <div class="profil-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">

            <section class="profil-box" style="border: 3px solid #000; padding: 20px; background: #fff; box-shadow: 5px 5px 0px #000;">
                <div class="box-header" style="font-weight: 900; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 15px;">DONNÉES_CLIENT</div>

                <form id="form-profil">
                    <input type="hidden" id="user-id" value="<?php echo htmlspecialchars($user_data['id']); ?>">

                    <div class="info-item" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <label style="font-weight: bold; font-size: 0.9rem;">PRÉNOM :</label>
                        <input type="text" id="prenom" value="<?php echo htmlspecialchars($user_data['prenom']); ?>" style="padding: 5px; width: 65%; border: 2px solid #000; font-weight: bold;" required>
                    </div>

                    <div class="info-item" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <label style="font-weight: bold; font-size: 0.9rem;">NOM :</label>
                        <input type="text" id="nom" value="<?php echo htmlspecialchars($user_data['nom']); ?>" style="padding: 5px; width: 65%; border: 2px solid #000; font-weight: bold;" required>
                    </div>

                    <div class="info-item" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <label style="font-weight: bold; font-size: 0.9rem;">E-MAIL :</label>
                        <input type="email" id="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" style="padding: 5px; width: 65%; border: 2px solid #000; font-weight: bold;" required>
                    </div>

                    <div class="info-item" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <label style="font-weight: bold; font-size: 0.9rem;">TÉL :</label>
                        <input type="text" id="tel" value="<?php echo htmlspecialchars($user_data['tel'] ?? ''); ?>" style="padding: 5px; width: 65%; border: 2px solid #000; font-weight: bold;">
                    </div>

                    <button type="submit" class="btn-brutal" style="width: 100%; font-size: 0.8rem; padding: 10px; background: #000; color: white; font-weight: 900; border: 2px solid #000; cursor: pointer;">
                        SAUVEGARDER
                    </button>
                    
                    <div id="message-retour" style="margin-top: 10px; font-weight: bold; text-align: center; font-size: 0.9rem; min-height: 20px;"></div>
                </form>

                <div class="info-item" style="margin-top: 15px; border-top: 2px dashed #000; padding-top: 10px;">
                    <span>RÔLE : <b style="color: #ff0055;"><?php echo strtoupper(htmlspecialchars($user_data['role'])); ?></b></span>
                </div>
            </section>

            <section class="profil-box" style="border: 3px solid #000; padding: 20px; background: #fff; box-shadow: 5px 5px 0px #000;">
                <div class="box-header" style="font-weight: 900; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 15px;">STATUT_FIDÉLITÉ</div>
                <div class="points-display" style="display: flex; align-items: baseline; gap: 5px;">
                    <span style="font-size: 3rem; font-weight: 900;"><?php echo (intval($user_data['id']) * 2); ?></span>
                    <span style="font-size: 1rem; color: #ff0055; font-weight: 900;">PTS</span>
                </div>
                <div class="fidelity-bar" style="height: 20px; background: #f0f0f0; border: 2px solid #000; margin-top: 10px;">
                    <div class="progress" style="width: 65%; height: 100%; background: #000;"></div>
                </div>
                <p style="margin-top: 10px; font-size: 0.8rem; font-weight: bold;">GRADE : <?php echo strtoupper(htmlspecialchars($user_data['statut'] ?? 'MOLDU')); ?></p>
                
                <?php if (!empty($user_data['remise']) && $user_data['remise'] != "0"): ?>
                <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd;">
                    <p style="font-size: 0.8rem;"><strong>REMISE ACTIVE :</strong> <span style="color: #ff0055; font-weight: bold;"><?php echo htmlspecialchars($user_data['remise']); ?>%</span></p>
                </div>
                <?php endif; ?>
            </section>

            <section class="profil-box" style="border: 3px solid #000; padding: 20px; background: #fff; box-shadow: 5px 5px 0px #000;">
                <div class="box-header" style="font-weight: 900; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 15px;">DERNIÈRES_COMMANDES</div>
                <table style="width: 100%; font-size: 0.8rem; border-collapse: collapse;">
                    <?php 
                    $commandes = getCommandesByUserId($_SESSION['user']);
                    $commandes_recentes = array_slice(array_reverse($commandes), 0, 5);
                    
                    if (!empty($commandes_recentes)):
                        foreach ($commandes_recentes as $cmd): ?>
                        <tr style="border-bottom: 1px solid #ddd;">
                            <td style="padding: 10px 0; font-weight: bold;">#<?php echo htmlspecialchars($cmd['id']); ?></td>
                            <td><?php echo htmlspecialchars(substr($cmd['date_heure'], 0, 10)); ?></td>
                            <td style="text-align: right; padding: 5px 0;">
                                <?php if ($cmd['notation'] === null): ?>
                                    <a href="notation.php?id=<?php echo htmlspecialchars($cmd['id']); ?>" class="btn-brutal" style="background: #000; color: white; text-decoration: none; padding: 3px 8px; font-size: 0.65rem; display: inline-block; border: 1px solid #000;">NOTER</a>
                                <?php else: ?>
                                    <span style="color: green; font-weight: bold;">✓ NOTÉ</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach;
                    else: ?>
                        <tr>
                            <td style="padding: 10px 0; text-align: center; color: #999;">Aucune commande</td>
                        </tr>
                    <?php endif; ?>
                </table>
            </section>

        </div>

        <div style="margin-top: 30px; text-align: center; display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <a href="historique_commandes_client.php" class="btn-brutal" style="background: #000; color: white; text-decoration: none; display: inline-block; padding: 10px 20px; font-weight: 900; border: 2px solid #000; box-shadow: 3px 3px 0 #000;">VOIR L'HISTORIQUE COMPLET</a>
            <a href="traitement_deconnexion.php" class="btn-brutal" style="background: #ff0055; color: white; text-decoration: none; display: inline-block; padding: 10px 20px; font-weight: 900; border: 2px solid #000; box-shadow: 3px 3px 0 #000;">DÉCONNEXION</a>
        </div>

    </main>

    <?php include '../includes/footer.html'; ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const formProfil = document.getElementById('form-profil');

        if(formProfil) {
            formProfil.addEventListener('submit', function(e) {
                e.preventDefault(); // Bloque le rechargement synchrone de la page

                const messageDiv = document.getElementById('message-retour');
                messageDiv.innerText = "SAUVEGARDE EN COURS...";
                messageDiv.style.color = "#000";

                // Récupération des données du formulaire
                const prenomSaisi = document.getElementById('prenom').value;
                const nomSaisi = document.getElementById('nom').value;

                const donnees = new FormData();
                donnees.append('action', 'update_profile');
                donnees.append('id', document.getElementById('user-id').value); 
                donnees.append('prenom', prenomSaisi);
                donnees.append('nom', nomSaisi);
                donnees.append('email', document.getElementById('email').value);
                donnees.append('tel', document.getElementById('tel').value);

                // Envoi de la requête asynchrone (Fetch)
                fetch('ajax_handler.php', {
                    method: 'POST',
                    body: donnees
                })
                .then(reponse => reponse.json())
                .then(data => {
                    if (data.success) {
                        messageDiv.innerText = "✓ PROFIL MIS À JOUR AVEC SUCCÈS !";
                        messageDiv.style.color = "green";
                        
                        // EXPÉRIENCE ASYNCHRONE PURE : On met à jour l'interface à la volée sans recharger
                        // 1. Mise à jour du titre principal H1
                        document.getElementById('titre-nom-dynamique').innerText = (prenomSaisi + " " + nomSaisi).toUpperCase();
                        
                        // 2. Mise à jour de l'affichage de l'ID utilisateur dans le Header si présent
                        const headerUserStatus = document.querySelector('.user-status');
                        if (headerUserStatus && headerUserStatus.innerText.includes('ID:')) {
                            headerUserStatus.innerHTML = "ID: " + prenomSaisi.toUpperCase();
                        }
                    } else {
                        messageDiv.innerText = "✗ ERREUR : " + data.message;
                        messageDiv.style.color = "red";
                    }
                })
                .catch(error => {
                    console.error("Erreur technique:", error);
                    messageDiv.innerText = "✗ ERREUR TECHNIQUE LORS DE L'ENVOI.";
                    messageDiv.style.color = "red";
                });
            });
        }
    });
    </script>
</body>
</html>