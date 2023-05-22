# feecare

Bonjour, 

Nous avons réussi à héberger le site sur : https://feecare.herokuapp.com/
Seulement lorsque nous essayons d'accéder a un element de la base de donnée , le site nous renvoie une erreur 500:
Oops! An Error Occurred
The server returned a "500 Internal Server Error".
Something is broken. Please let us know what you were doing when this error occurred. We will fix it as soon as possible. Sorry for any inconvenience caused.

Malheureusement, nous n'avons pas pu corriger l'erreur.

________________________________________________________________________________________________________________________________________________
**INSTALLATION WINDOWS:**

Pour voir le site en sa globalité il faut installer les élements suivant : 
    -Symfony

    -PostgreSQL

    -interface PgAdmin

Pré-requis modules : **npm, scoop, composer**

**Installer Symfony via Scoop avec Windows :**
https://www.devenir-webmaster.com/V2/TUTO/CHAPITRE/SYMFONY/01-symfony-cli/

**Installer Composer et PHP**: 
Installez PHP sur votre ordinateur. Nous vous recommandons d’utiliser XAMPP(https://www.apachefriends.org/fr/download.html) .

Une fois que **XAMPP **est installé, téléchargez la dernière version de Composer.
Lancez l’assistant d’installation de Composer. Lorsqu’il vous demande d’activer le mode développeur, ignorez-le et poursuivez le processus d’installation.
Une autre fenêtre s’ouvre et vous demande de localiser la ligne de commande PHP. Par défaut, elle se trouve dans C:/xampp/php/php.exe. Après avoir spécifié le chemin, cliquez sur Suivant.
Vous serez invité à entrer les paramètres du proxy. Laissez la case non cochée et sautez cette partie en cliquant sur Suivant. Ensuite, dans la dernière fenêtre, cliquez sur Installer.
Une fois l’installation terminée, ouvrez l’invite de commande. Appuyez sur CTRL +R, tapez « cmd » et cliquez sur OK.

**Installer PostgreSQL:**
https://www.enterprisedb.com/downloads/postgres-postgresql-downloads

**Installer pgAdmin 4, (interface d'administration de la base de données postgresql):**
https://www.postgresql.org/ftp/pgadmin/pgadmin4/v7.0/windows/

**Importer la base de données : **
https://www.windows8facile.fr/postgresql-importer-exporter-base-pgadmin/?amp

Depuis le répertoire ou se trouve le dossier comprenant le projet, écrire: 

1- npm install --force
2- composer install (pour mettre à jour les dépendances).

A lancer en parallèle sur des terminaux différents : 

1- npm run watch

2- symfony server:start
3- php bin/console run:websocket-server

Arreter l'execution: 

symfony server:stop

  
Lorsque vous avez téléchargé la branche main (le projet entier) il faut lancer les commandes dans Visual Studio Code:
    - composer install
    - npm install

Dans le .env il faut changer le !changeme! par votre mot de passe choisi dans PgAdmin.

Pour lancer le projet:
    - symfony server:start

    - npm run watch

    - php bin/console run:websocket-server


Sur la page d’accueil le User rentre son identifiant et on vérifie s’il existe.
S’il existe et qu’il a déjà complété ses informations, on le renvoie sur le formulaire d’inscription.
Sinon sur le formulaire de LogIn.
Et s’il n’existe pas, un message d’erreur est affiché.
Et la casse est importante pour l’identifiant.
Le ou les admins ça commence par « A » .
Les familles par « F ».
Et les éducateurs par « E ».
Les identifiants sont rajoutés en base de données directement par l’admin pour chaque nouvel User.


Identifiants en base de données : Ajunior, Fndelo , Echanez  dans la base de donnée que vous pouvez trouver dans le repertoire base de donnée.

page login:
tout mot de passe : root
un compte admininstrateur: junior@gmail.com
            famille : rick@gmail.com
            educateur : chanez@gmail.com

Une fois entrer dans le site vous pouvez naviguer entre les pages:
    Familles et Educateurs:
        -fil d'actualité : Accueil
        -Disponibilités
        -Rendez-vous
        -Messagerie
        -Nos centres

    Administrateur:
        -Ajouter une famille à un traitant
        -Retirer une famille
        -Creer un ID 
        -Editer les centres (Map)  

Nous avons essayer de suivre au maximum notre cahier des charges. 

Bonne visite du site ! :)

