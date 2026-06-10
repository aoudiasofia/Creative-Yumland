# KØLD DELIVERY ❄️

**KØLD** est un site de commande et de livraison de repas à emporter. Codé en PHP avec un système de stockage basé sur des fichiers JSON, le projet se démarque par son style visuel très marqué. 

L'application gère tout le parcours d'une commande de A à Z : le client choisit ses plats, le restaurateur reçoit la commande et la prépare en cuisine, puis le livreur prend le relais pour l'apporter à destination.

---

## 🤝 Collaborateurs
- [**AOUDIA Sofia**](https://github.com/aoudiasofia)
- [**PHILIPPOT Lucie**](https://github.com/luciephilippot)
- [**DELECHENEAU Camille**](https://github.com/delecheneaucamille)

## 📄 Rapport de projet
Le rapport complet détaillant la conception du site est disponible juste ici :
- [📕 Rapport de projet (PDF)](rapport-de-projet.pdf)

---

## Ce que fait le projet (Fonctionnalités)

- **Changement de thème à la volée** : Le site propose trois visuels différents. Le mode **KØLD** (sombre et percutant), un mode **Clair** classique, et un mode **Accessibilité** qui bascule tout l'affichage avec de très gros caractères et des boutons XXL pour le confort visuel.
- **Panier et formules menus** : On peut composer son panier en y ajoutant des plats à la carte ou des formules menus complètes. Le site calcule les totaux en temps réel et intègre une option pour trier ses articles (par prix ou par ordre alphabétique).
- **Quatre profils d'utilisateurs (Rôles)** : 
  - Les **clients** commandent et voient leur historique.
  - Les **restaurateurs** modifient la carte et valident les plats prêts.
  - Les **livreurs** s'attribuent les livraisons et mettent à jour le statut en route/livré.
  - Les **administrateurs** gèrent les comptes et peuvent bloquer des utilisateurs.
- **La Roulette de la Fidélité 🎰** : Un mini-jeu se déclenche dès qu'une commande est validée. Le client tente sa chance et peut gagner immédiatement 5% ou 10% de réduction sur son ticket.
- **Sécurité et suivi** : Les mots de passe sont chiffrés en base de données. De plus, un système détecte et déconnecte automatiquement les utilisateurs bannis, tout en inscrivant les tentatives suspectes dans un fichier de logs.