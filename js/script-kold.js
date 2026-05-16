function changerTheme(nomDuTheme) {
    document.body.className = nomDuTheme;
    
    document.cookie = "kold_theme=" + nomDuTheme + "; max-age=2592000; path=/";
}

window.onload = function() {
    var tousLesCookies = document.cookie;
    
    var tableauCookies = tousLesCookies.split('; ');
    
    for (var i = 0; i < tableauCookies.length; i++) {
        var unCookie = tableauCookies[i];
        
        if (unCookie.indexOf('kold_theme=') === 0) {
            
            var themeSauvegarde = unCookie.split('=')[1];
            
            document.body.className = themeSauvegarde;
        }
    }
};

function basculerMotDePasse(idDuChamp) {
    var champMdp = document.getElementById(idDuChamp);
    
    if (champMdp.type === "password") {
        champMdp.type = "text";
    } 
    else {
        champMdp.type = "password";
    }
}

function mettreAJourCompteur(idChamp, idTexteCompteur, limite) {
    var champ = document.getElementById(idChamp);
    var affichage = document.getElementById(idTexteCompteur);
    
    var nombreDeCaracteres = champ.value.length;

    affichage.innerHTML = nombreDeCaracteres + " / " + limite;

    if (nombreDeCaracteres > limite) {
        affichage.style.color = "red";
    } else {
        affichage.style.color = "inherit";
    }
}

function validerInscription(event) {
    var email = document.getElementById('email').value;
    var telephone = document.getElementById('telephone').value;

    if (email.indexOf('@') === -1) {
        alert("🚨 Erreur : Votre adresse e-mail doit contenir un '@'.");
        return false;
    }

    if (telephone.length !== 10 || isNaN(telephone)) {
        alert("🚨 Erreur : Le numéro de téléphone doit contenir exactement 10 chiffres, sans espaces.");
        return false; 
    }

    return true;
}