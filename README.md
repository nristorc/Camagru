# CAMAGRU - ECOLE 42

**Projet réalisé dans le cadre de notre formation à l'école 42 - premier projet de la branche Web**\
**Objectifs: Gestion utilisateurs - Gestion permissions - Mailing - Sécurité / Validation de données**\
**Compétences: Security - Web - DB & Data**

# Technologies utilisées

* Serveur: **PHP**
* Client: **Javascript**
* Base de données: **MySQL**

## Table des matières
- [Technologies utilisées](#technologies-utilisées)
- [Objectifs](#objectifs)
- [Preview](#preview)
- [Consignes générales](#consignes-générales)
- [Partie Commune](#partie-commune)
- [Partie utilisateur](#partie-utilisateur)
- [Partie Galerie](#partie-galerie)
- [Partie Montage](#partie-montage)
- [Partie bonus](#partie-bonus)

# Objectifs

Camagru vous propose de créer une petite application web permettant de réaliser des montages basiques à l’aide de votre webcam et d’images prédéfinies. Evidemment, ces images auront un canal alpha, sinon votre
superposition n’aurait pas la prestance escomptée !

:information_source:Un utilisateur de votre site devra donc être capable de sélectionner une image dans une liste d’images superposables, prendre une photo depuis sa webcam et admirer le résultat d’un montage digne
de James Cameron.

Toutes les images prises devront être publiques, like-ables et commentables.

# Preview

Login Page
![login](https://drive.google.com/uc?id=1yCKN876hVLD2qBGLLiSCYCevPaNF7laC)

Modification données utilisateur
![user profil](https://drive.google.com/uc?id=1dM3L3gMAl5BvsjpC_DundiSveP71z91J)

Page Montage
![camera](https://drive.google.com/uc?id=1ceEGottzQ4EWG-oss6AddAc54CizF2pZ)

Bibliotheque photos
![bibliotheque](https://drive.google.com/uc?id=1lb25ZPXz6ofFnlB7znO3_cO2aMT0DQFa)

Commentaires, Likes
![comments and likes](https://drive.google.com/uc?id=12c7LTRkxaotovvCm7lGI2SiaTL_b8kgK)

Partage de mes montages
![share](https://drive.google.com/uc?id=1UkTF7v3xkymrdxKHOZnRubV0F9y0aBq_)

# Consignes générales

- :warning:Vous devrez seulement utiliser le **langage PHP** pour concevoir votre application coté serveur, avec seulement la **librairie standard**

- Coté client, vos pages devront utiliser **HTML, CSS et JavaScript**

:warning:**Aucun Framework, Micro-Framework ou librairie qui n’est pas de votre conception
n’est autorisée**. Vous pourrez utiliser Javascript avec les API natives des navigateurs uniquement. Les Frameworks CSS seront tolérés, tant qu’ils n’ajoutent pas du JavaScript interdit.

- Vous utiliserez interface d’**abstraction PDO de PHP** pour communiquer avec votre Base de Données, qui devra être requêtable en SQL. Le mode d’erreur de ce driver sera obligatoirement défini sur PDO::ERRMODE_EXCEPTION

# Partie Commune

- Votre site devra avoir une mise en page décente (c’est à dire au moins un header, une
section principale et un footer)

:iphone:  Votre site devra être présentable sur mobile, et garder une mise en page acceptable
sur de petites résolutions.

# Partie Utilisateur

- L’application doit permettre à un utilisateur de s’inscrire avec:
	- **adresse email**
	- **login**
	- **photo de profil**
	- **nom**
    - **prénom** 
    - **mot de passe**

- :warning: Une fois l’inscription validée, un e-mail de confirmation comportant un
lien unique sera envoyé sur l’adresse e-mail renseignée.
	
- L’utilisateur doit être capable de se connecter avec:
    - **login**
    - **mot de passe** 
- Il doit également pouvoir recevoir un **mail de réinitialisation**
    de son mot de passe en cas d’oubli.
- L’utilisateur doit pouvoir se **déconnecter en un seul clic** depuis n’importe quelle
    page du site.

- L’utilisateur doit pouvoir **Modifier** son **adresse email**, sa **photo de profil**, son **mot de passe** et ses **informations**

# Partie Galerie

- La **galerie devra être publique**, donc accessible sans authentification. Elle doit afficher l’intégralité des images prises par les membres du site, **triées par date de création**. Elle doit pouvoir permettre à l’utilisateur de les **commenter et de les **liker si celui-ci est authentifié**.

- Lorsque une image reçoit un nouveau commentaire, l’**auteur de cette image doit en être informé par mail**. 

:warning:Cette préférence est activée par défaut, mais peut être désactivée dans les préférences de l’utilisateur.

- La liste des images doit être paginée, avec au moins 5 éléments par page.

# Partie Montage

:warning:Cette partie ne doit être accessible qu’aux utilisateurs connectés, et rejeter poliment
l’internaute dans le cas contraire.

Cette page devra être composée de deux sections :
   - Une section principale, contenant **l’apercu de votre webcam**, la **liste des images
superposables disponibles** et un **bouton permettant de prendre la photo**.
   - Une section latérale, affichant les **miniatures de toutes les photos prises précedemment**.

- Les images superposables doivent être sélectionnables, et le bouton permettant de prendre la photo ne doit **pas être cliquable** tant qu’aucune image n’est sélectionnée.

- :information_source:Le traitement de l’image finale (donc entre autres la superposition des deux images) doit être fait coté serveur, en PHP.

- Parce que tout le monde n’a pas de webcam, vous devez laisser la possibilité d’uploader une image au lieu de la prendre depuis la caméra.

- L’utilisateur doit pouvoir supprimer ses montages, et :warning:uniquement les siens.

# Partie bonus

- “AJAXifier” les échanges avec le serveur **done**\
- Faire un aperçu du rendu final en live, directement sur l’aperçu de la caméra\
- Faire une pagination infinie sur la partie galerie\
- Pouvoir partager ses images sur les réseaux sociaux. **done: Facebook et Twitter**
- Pouvoir faire un rendu d’un GIF animé

Bonus supplémentaires effectués
- Mise en place du Re-Captcha
- Effectuer uniquement des requêtes SQL préparées
- Suppression du compte et des commentaires par l'utilisateur
