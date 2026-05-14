<?php
session_start();
include '../includes/fonctions.php';

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

// On appelle la nouvelle fonction intelligente
$user_data = getInfoUser($_SESSION['user']); 

if (!$user_data) {
    // Si ça affiche encore l'erreur, on va afficher ce que contient la session pour comprendre
    die("Erreur : Utilisateur introuvable. La session contient : " . $_SESSION['user']);
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

    <main class="profil-container">

        <h1 class="main-title">PROFIL // <?php echo strtoupper($user_data['prenom'] . " " . $user_data['nom']); ?></h1>

        <div class="profil-grid">

            <section class="profil-box">
                <div class="box-header">DONNÉES_CLIENT</div>

                <form id="form-profil">
                    <input type="hidden" id="user-id" value="<?php echo htmlspecialchars($user_data['id']); ?>">

                    <div class="info-item" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <label style="font-weight: bold; font-size: 0.9rem;">PRÉNOM :</label>
                        <input type="text" id="prenom" value="<?php echo htmlspecialchars($user_data['prenom']); ?>" style="padding: 5px; width: 65%; border: 1px solid var(--text);" required>
                    </div>

                    <div class="info-item" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <label style="font-weight: bold; font-size: 0.9rem;">NOM :</label>
                        <input type="text" id="nom" value="<?php echo htmlspecialchars($user_data['nom']); ?>" style="padding: 5px; width: 65%; border: 1px solid var(--text);" required>
                    </div>

                    <div class="info-item" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <label style="font-weight: bold; font-size: 0.9rem;">E-MAIL :</label>
                        <input type="email" id="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" style="padding: 5px; width: 65%; border: 1px solid var(--text);" required>
                    </div>

                    <div class="info-item" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <label style="font-weight: bold; font-size: 0.9rem;">TÉL :</label>
                        <input type="text" id="tel" value="<?php echo htmlspecialchars($user_data['tel'] ?? ''); ?>" style="padding: 5px; width: 65%; border: 1px solid var(--text);">
                    </div>

                    <button type="submit" class="btn-brutal" style="width: 100%; font-size: 0.8rem; padding: 10px; background: var(--text); color: white;">
                        SAUVEGARDER
                    </button>
                    
                    <div id="message-retour" style="margin-top: 10px; font-weight: bold; text-align: center; font-size: 0.9rem;"></div>
                </form>

                <div class="info-item" style="margin-top: 15px; border-top: 1px dashed #ccc; padding-top: 10px;">
                    <span>RÔLE : <b style="color:var(--accent)"><?php echo strtoupper($user_data['role']); ?></b></span>
                </div>
            </section>

            <section class="profil-box">
                <div class="box-header">STATUT_FIDÉLITÉ</div>
                <div class="points-display">
                    <span style="font-family: 'Archivo Black'; font-size: 3rem;"><?php echo (intval($user_data['id']) * 2); ?></span>
                    <span style="font-size: 1rem; color: var(--accent);">PTS</span>
                </div>
                <div class="fidelity-bar" style="height: 20px; background: #f0f0f0; border: 2px solid var(--text); margin-top: 10px;">
                    <div class="progress" style="width: 65%; height: 100%; background: var(--accent);"></div>
                </div>
                <p style="margin-top: 10px; font-size: 0.8rem; font-weight: bold;">GRADE : <?php echo strtoupper($user_data['statut'] ?? 'MOLDU'); ?></p>
                
                <?php if (!empty($user_data['remise']) && $user_data['remise'] != "0"): ?>
                <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd;">
                    <p style="font-size: 0.8rem;"><strong>REMISE ACTIVE :</strong> <span style="color: var(--accent);"><?php echo htmlspecialchars($user_data['remise']); ?>%</span></p>
                </div>
                <?php endif; ?>
            </section>

            <section class="profil-box">
                <div class="box-header">DERNIÈRES_COMMANDES</div>
                <table style="width: 100%; font-size: 0.8rem; border-collapse: collapse;">
                    <?php 
                    $commandes = getCommandesByUserId($_SESSION['user']);
                    $commandes_recentes = array_slice(array_reverse($commandes), 0, 5);
                    
                    if (!empty($commandes_recentes)):
                        foreach ($commandes_recentes as $cmd): ?>
                        <tr style="border-bottom: 1px solid #ddd;">
                            <td style="padding: 10px 0;">#<?php echo $cmd['id']; ?></td>
                            <td><?php echo substr($cmd['date_heure'], 0, 10); ?></td>
                            <td style="text-align: right;">
                                <?php if ($cmd['notation'] === null): ?>
                                    <a href="notation.php?id=<?php echo $cmd['id']; ?>" class="btn-brutal btn-small" style="background:var(--accent); color:white; text-decoration:none; padding: 5px 12px; font-size: 0.7rem;">NOTER</a>
                                <?php else: ?>
                                    <span style="color: var(--accent); font-weight: bold;">✓ NOTÉ</span>
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

        <div style="margin-top: 30px; text-align: center; display: flex; gap: 15px; justify-content: center;">
            <a href="historique_commandes_client.php" class="btn-brutal" style="background: var(--accent); color: white; text-decoration: none; display: inline-block; margin: 0;">VOIR L'HISTORIQUE COMPLET</a>
            <a href="logout.php" class="btn-brutal" style="background: #ff0055; color: white; text-decoration: none; display: inline-block; margin: 0;">DÉCONNEXION</a>
        </div>

    </main>

    <?php include '../includes/footer.html'; ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const formProfil = document.getElementById('form-profil');

        if(formProfil) {
            formProfil.addEventListener('submit', function(e) {
                e.preventDefault(); // On bloque le rechargement de la page !

                const messageDiv = document.getElementById('message-retour');
                messageDiv.innerText = "Sauvegarde en cours...";
                messageDiv.style.color = "orange";

                // On ramasse toutes les nouvelles valeurs écrites par le client
                const donnees = new FormData();
                donnees.append('action', 'update_profile');
                donnees.append('id', document.getElementById('user-id').value); 
                donnees.append('prenom', document.getElementById('prenom').value);
                donnees.append('nom', document.getElementById('nom').value);
                donnees.append('email', document.getElementById('email').value);
                donnees.append('tel', document.getElementById('tel').value);

                // Envoi via le Talkie-Walkie (Fetch)
                fetch('ajax_handler.php', {
                    method: 'POST',
                    body: donnees
                })
                .then(reponse => reponse.json())
                .then(data => {
                    if (data.success) {
                        messageDiv.innerText = "✓ Profil mis à jour !";
                        messageDiv.style.color = "green";
                        // On attend 1 seconde pour que l'utilisateur voit le message, puis on recharge
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        messageDiv.innerText = "✗ Erreur : " + data.message;
                    }
                })
                .catch(error => {
                    console.error("Erreur:", error);
                    messageDiv.innerText = "✗ Erreur technique.";
                    messageDiv.style.color = "red";
                });
            });
        }
    });
    </script>

</body>
</html>