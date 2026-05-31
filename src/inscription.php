<?php
session_start();
include '../includes/fonctions.php';
?>
<!DOCTYPE html>
<html lang="fr">

<?php 
    $titre_page = "KØLD | INSCRIPTION";
    include '../includes/head.php';
?>


<body class="kold-mode">

    <?php 
        $nom_page = "inscription";
        include '../includes/header.php';
    ?>

    <main class="login-container">

        <div class="kold-form">
            <h2 class="section-title-simple">Inscription</h2>

            <form action="traitement_inscription.php" method="post" onsubmit="return validerInscription()">
                <div class="input-group">
                    <label class="label-tech" for="nom">Nom</label>
                    <div class="input-col">
                        <input type="text" id="nom" name="nom" placeholder="EX: PHILIPPOT" required>
                    </div>
                </div>

                <div class="input-group">
                    <label class="label-tech" for="prenom">Prénom</label>
                    <div class="input-col">
                        <input type="text" id="prenom" name="prenom" placeholder="EX: LUCIE" required>
                    </div>
                </div>

                <div class="input-group">
                    <label class="label-tech" for="email">Email</label>
                    <div class="input-col">
                        <input type="email" id="email" name="email" placeholder="KØLD@GMAIL.COM" required>
                    </div>
                </div>

                <div class="input-group">
                    <label class="label-tech" for="password">Mot de passe</label>
                    <div class="input-col" style="display: flex; flex-direction: column; gap: 5px;">
                        <div style="display: flex; gap: 5px;">
                            <input type="password" id="password" name="password" placeholder="*******" style="flex-grow: 1;" maxlength="20" oninput="mettreAJourCompteur('password', 'compteur-mdp-insc', 20)" required>
                            <button type="button" onclick="basculerMotDePasse('password')" style="cursor: pointer; background: var(--white); border: 3px solid var(--text); padding: 0 15px; font-size: 1.2rem;" title="Afficher/Masquer">👁️</button>
                        </div>
                        <div id="compteur-mdp-insc" style="font-size: 0.8rem; text-align: right; font-family: 'Space Mono'; opacity: 0.8;">
                            0 / 20
                        </div>
                    </div>
                </div>

                <div class="input-group">
                    <label class="label-tech" for="telephone">Téléphone</label>
                    <div class="input-col">
                        <input type="tel" id="telephone" name="telephone" placeholder="06 12 12 12 12"
                            pattern="[0-9]{10}" required>
                    </div>
                </div>

                <div class="input-group">
                    <label class="label-tech" for="adresse">Adresse complète</label>
                    <div class="input-col">
                        <input type="text" id="adresse" name="adresse" placeholder="N°, RUE, VILLE..." required>
                    </div>
                </div>

                <div class="input-group">
                    <label class="label-tech" for="infos">Informations supplémentaires</label>
                    <div class="input-col">
                          <input type="text" id="infos" name="infos" placeholder="INTERPHONE, ÉTAGE..." maxlength="150" oninput="mettreAJourCompteur('infos', 'compteur-infos', 150)">
                        
                        <div id="compteur-infos" style="font-size: 0.8rem; margin-top: 5px; text-align: right; font-family: 'Space Mono'; opacity: 0.8;">
                            0 / 150
                        </div>
                    </div>
                </div>

                <div class="button-row">
                    <button type="submit" class="btn-login">VALIDER L'INSCRIPTION</button>
                </div>

            </form>
        </div>
    </main>

    <?php include '../includes/footer.html'; ?>

</body>
</html>