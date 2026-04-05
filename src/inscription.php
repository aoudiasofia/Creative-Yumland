<?php
session_start();
include '../includes/fonctions.php';
?>
<!DOCTYPE html>
<html lang="fr">

<<?php 
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

            <form action="traitement_inscription.php" method="post">

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
                    <div class="input-col">
                        <input type="password" id="password" name="password" placeholder="*******" required>
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
                        <input type="text" id="infos" name="infos" placeholder="INTERPHONE, ÉTAGE...">
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