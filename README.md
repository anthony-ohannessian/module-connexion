
# Module connexion

créez votre base de données nommée
“moduleconnexion” à l’aide de phpmyadmin. Dans cette bdd, créez une
table “utilisateurs” qui contient les champs suivants :
- id, int, clé primaire et Auto Incrément
- login, varchar de taille 255
- prenom, varchar de taille 255
- nom, varchar de taille 255
- password, varchar de taille 255

créez dors et déjà un utilisateur qui aura la possibilité d’accéder à
l’ensemble des informations. Son login, prenom, nom et mot de passe sont
“admin”.

- Une page d’accueil qui présente votre site (index.php)
- Une page contenant un formulaire d’inscription (inscription.php)
Le formulaire doit contenir l’ensemble des champs présents dans la table “utilisateurs” (sauf “id”) + une confirmation de mot de passe.

- Une page contenant un formulaire de connexion (connexion.php) :
Le formulaire doit avoir deux inputs : “login” et “password”. Lorsque le formulaire est validé, s’il existe un utilisateur en bdd correspondant à ces informations, alors l’utilisateur est considéré comme connecté et une (ou plusieurs) variables de session sont créées.

- Une page permettant de modifier son profil (profil.php) :
Cette page possède un formulaire permettant à l’utilisateur de modifier ses informations. Ce formulaire est par défaut pré-rempli avec les informations qui sont actuellement stockées en base de données.

- Une page d’administration (admin.php) :
Cette page est accessible UNIQUEMENT pour l’utilisateur “admin”. Elle permet de lister l’ensemble des informations des utilisateurs présents dans la base de données.

