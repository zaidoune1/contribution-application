# Documentation d'installation du projet de contribution de fiabilisation d'une base de lieux

## Présentation du projet

Le projet Contributor consiste à développer une application web qui permet aux utilisateurs de contribuer à identifier les lieux où ils ont effectué des paiements par carte bancaire. L'application utilise les informations d'opération bancaire et de géoposition pour proposer une liste de lieux possibles à l'aide de l'API Google Places. L'utilisateur peut alors choisir le lieu correspondant à son paiement et ainsi enrichir la base de données des lieux.

## Prérequis techniques

Pour installer le projet Contributor, vous devez avoir :

- PHP 8.0 ou supérieur 
- Composer
- Symfony CLI
- MySQL 8.0 ou supérieur
- Apache 2.4 ou supérieur

## Récupération du code source

Le code source du projet est hébergé sur GitHub à l'adresse suivante : https://github.com/zaidoune1/contribution-application.git

Vous pouvez le cloner en utilisant la commande suivante :

```bash
git clone https://github.com/zaidoune1/contribution-application.git
```

Ou le télécharger en cliquant sur le bouton "Code" puis "Download ZIP" sur la page du dépôt.

## Installation des dépendances

Une fois le code source récupéré, vous devez installer les dépendances du projet en utilisant Composer. Pour cela, ouvrez un terminal et déplacez-vous dans le répertoire du projet :

```bash
cd contribution-application
```

Puis exécutez la commande suivante :

```bash
composer install
```

Cette commande va télécharger et installer les packages nécessaires au fonctionnement du projet dans le répertoire vendor/.

## Configuration du projet

Avant de lancer le projet, vous devez le configurer en créant un fichier .env.local à la racine du répertoire. Ce fichier va contenir les variables d'environnement spécifiques à votre installation locale.

Vous pouvez copier le contenu du fichier .env qui sert de modèle et le modifier selon vos besoins.

Voici les variables que vous devez définir :

- APP_ENV : le mode d'exécution de l'application, qui peut être dev pour le développement ou prod pour la production. Par défaut, il vaut dev.
- APP_SECRET : la clé secrète utilisée par Symfony pour générer des jetons CSRF, chiffrer les cookies, etc. Vous pouvez la générer aléatoirement en utilisant la commande suivante :

```bash
php bin/console secrets:generate-keys
```

- DATABASE_URL : l'URL de connexion à la base de données MySQL. Vous devez indiquer le nom d'utilisateur, le mot de passe, le nom de la base de données et éventuellement le port et le serveur. Par exemple :

```bash
DATABASE_URL="mysql://contribution@localhost:3306/contribution"
```

Une fois le fichier .env.local créé et renseigné, vous devez créer la base de données en utilisant la commande suivante :

```bash
php bin/console doctrine:database:create
```

Puis exécuter les migrations pour créer les tables correspondant aux entités du projet :

```bash
php bin/console doctrine:migrations:migrate
```


## Lancement du serveur web

Pour lancer le serveur web intégré de Symfony, vous pouvez utiliser la commande suivante :

```bash
symfony server:start
```

Cette commande va démarrer un serveur web local sur le port 8000. Vous pouvez alors accéder au projet dans votre navigateur à l'adresse http://localhost:8000

Si vous préférez utiliser un serveur web externe comme Apache, vous devez configurer un hôte virtuel qui pointe vers le répertoire public/ du projet. Vous pouvez consulter la documentation officielle de Symfony pour plus de détails.

## Test du projet

Une fois le serveur web lancé, vous pouvez tester les fonctionnalités du projet Contributor. Voici quelques scénarios possibles :
- Profil Utilisateur:
   - Créer un compte utilisateur en cliquant sur le bouton “S’inscrire”. Vous devez indiquer une adresse email, un nom d’utilisateur et un mot de passe.
   - Se connecter avec le compte créé en cliquant sur le bouton “Se connecter”. Vous devez indiquer l’adresse email et le mot de passe du compte.
   - Accéder à l’interface d’ajout des contributions, Vous devez saisir la date d’opération,le libellé de la carte bancaire, la position gps (latitude,longitude). Par la suite une interface de choix du lieu va apparaitre.Vous devez voir la liste des lieux possibles dans un rayon de 1km autour de votre position GPS au moment du paiement. Cette liste est obtenue à l’aide de l’API Google Places. Vous pouvez soit valider un lieu soit passer cette étape et passer directement à la page « mes contribution »
   - Accéder à l’interface de contribution en cliquant sur le bouton “mes contribution”. Vous devez voir la liste de vos opérations bancaires datant du moment où vous avez réalisé votre paiement.
   - Pour contribuer, cliquer sur le bouton “Choisir un lieu” d’une opération. Le même concept que la première interface de choix du lieu.
   - Choisir le lieu correspondant à votre paiement en cliquant sur son nom. En confirmant vous allez vous rediriger vers la page « mes contribution », l’opération doit être mise à jour pour afficher le nom du lieu choisi.
   - Accéder à la page “Mon compte” en cliquant sur le bouton “Mon compte”. Vous devez voir un état de vos contributions, avec le nombre d’opérations où vous avez validé un lieu et le nombre d’opérations où vous n’avez pas encore validé un lieu.
   - Se déconnecter du compte utilisateur en cliquant sur le bouton “Se déconnecter” en haut à droite de la page.

- Profil Administrateur:
    - Se connecter avec le compte admin en cliquant sur le bouton “Se connecter”. Vous devez indiquer l’adresse email et le mot de passe du compte.
    - Accéder à l’interface d’administrateur, en cliquant sur le boutton "Accées Administrateur".
    -Dans le dashboard: l'administrateur peut consulter tous les opérations à travers la page "operations" et tous les utilisateurs à travers la page "utilistaeur"
    
 
## Problèmes et solutions

Si vous rencontrez des problèmes ou des erreurs lors de l'installation ou du test du projet Contributor, voici quelques solutions possibles :

- Si vous avez une erreur liée aux permissions sur les répertoires var/cache/ et var/log/, vous devez les rendre accessibles en écriture par le serveur web. Vous pouvez consulter cette page pour savoir comment faire selon votre système d'exploitation.
- Si vous avez une erreur liée à la connexion à la base de données, vous devez vérifier que les informations dans le fichier .env.local sont correctes et que le serveur MySQL est bien démarré.

Si vous avez besoin d'aide supplémentaire, vous pouvez me contacter par email à l'adresse moezzaidoun21@gmail.com ou consulter la documentation officielle de Symfony .
