# MINE FRAMEWORK
Well first version I asume...

## Technologies
- PHP 7.x
- MySQL 5.7.x
- TWIG
- HTML 5
- SASS / CSS3
- JS / jQuery

## Bon on installe quoi?
- GIT (mais ça on le savait tous non?) [https://git-scm.com/](https://git-scm.com/)
- NodeJS [https://nodejs.org/](https://nodejs.org/)
- GULP [http://gulpjs.com/](https://github.com/gulpjs/gulp/blob/master/docs/getting-started.md)
- COMPOSER [https://getcomposer.org/](https://getcomposer.org/)

## Initialisation du projet
Très bien maintenant que vous avez installé le moteur, il faut connecter notre moteur aux roues ... Bon on passe la métaphore foireuse, on va d'abord télécharger le projet en faisant un petit ``` git clone [url_du_projet] ``` **Bon là dessus on y reviendra plus tard**
On va maintenant connecter notre base de donnée et pour cela il vous faut changer le fichier ``` /sources/configDB.json ``` et mettre les valeurs de votre base de donnée de la sorte : 

	{
		"host": "localhost",	// Nom du serveur
		"bdd": "marketplace",	// Nom de la base de donnée
		"user": "root",			// Nom d'utilisateur
		"pass": ""				// Mot de passe
	}

Ensuite il va nous falloir installer les dépendances du _Back-End_ avec composer, rien de plus simple : ``` composer install ```
Puis enfin on initialise le _Front-end_ en faisant ``` npm install ``` puis en lancant le server gulp : ``` gulp ```

## Explication
Notre projet est découpé en plusieurs parties, mais il faut savoir que le navigateur va toujours tapper sur ``` public/index.php ``` et suivant l'URL naviguée il va prendre différents éléments pour construire la page.
Nous utilisons une méthode MVC (Model View Controller) qui permet de séparer le traitement Back-End, du Front-End et de la gestion de base de donnée. Le router va donc selon la méthode et l'url utilisée renvoyer une vue, un controller et des données qu'il va mélanger pour fabriquer la page. Nous utilisons le moteur de Template **TWIG** [https://twig.symfony.com/](https://twig.symfony.com/) pour faliciter le développement front. Nous utiliserons du **SASS** pour faliciter le développement des styles. 
Pour le _FRONT-END_ les fichiers **SASS** et **JS** se trouvent dans le dossier ``` resources ``` et grâce à **GULP** ils sont automatiquement compilés et minimisés pour être écrit en envoyés dans le dossier ``` public/assets ``` où ils seront accessibles par le navigateur. Mais ce n'est pas tout, **GULP** nous recharge automatiquement notre page web lors d'un quelconque changement sur les styles, sur les scripts ou sur le HTML.

## D'accord mais ça ne marche toujours pas...
Et bien oui ... Ce projet est fait avec un VHOST, c'est à dire que je donne une url à mon navigateur et Apache me redirige automatiquement sur un dossier de mon ordinateur... Il va falloir créer le VHOST pointe sur le dossier ``` public ``` avec l'URL de votre choix. Ensuite, si j'ai choisi l'url **marketplace.dev**, il faut que je change mon fichier gulpfile.js (ligne 15) pour lui donner le nom de mon URL et ainsi mettre la ligne : ``` var proxyServer = 'marketplace.dev'; ``` maintenant lorsque je lancerai mon script GULP le navigateur m'ouvrira la page **marketplace.dev** qui me redirigera vers le dossier public sur mon ordi ``` /var/html/www/marketplace/public/ ```

## Le Back-End
- Les Routes -> ``` /sources/routes.php ```
- Les Controllers -> ``` /sources/Controllers ```
- Les Modèles -> ``` /sources/Models ```
- Les Vues -> ``` /sources/Views ```

Bon pour ce qui est des Vues, c'est la partie **Front-End** donc nous ne nous y intéresseront pas beaucoup dans ce chapitre.
Concernant les autres parties, nous allons voir cela dans un tableau :
partie | quézako?
Les Routes | Les Routes est l'ensemble des URL naviguées sur notre site web. Elles redirigent vers une fonction du controller et peuvent différencier le verb utiliser (GET, POST, PATCH, DELETE...)
 | Il est également possible de faire des groupes de routes. Par exemple, si j'ai les routes ``` /posts/view ``` , ``` /posts/edit ``` et ``` /posts/create ``` je peux regroupe les trois routes en un ensemble de routes ``` /posts/ ```
Controllers | Le Controller est en fait une classe PHP qui permet de gérer, tous les échanges entre les vues et la base de données. Les fonctions appelés par le router doivent être statiques et prennent les paramètres suivant : $req, $res, $serv, $app bon dans la pratique les plus utilisées seront la requête $req qui contient les paramètres de la route et également l'application $app qui contient la variable Twig qui permet le rendu de la vue
Modèles | Ce sont des éléments qui sont appelés par les controllers et qui permettent de gérer tout ce qui touche à la base de donnée, qu'il s'agisse de la sélection d'un ou plusieurs éléments, la suppression, l'insertion ou bien d'autres choses.

## Le Front-End
Comme dit plus tôt, les fichiers de styles sont créés en **SASS** dans ``` resources/sass ``` et transformés automatiquement en CSS minifié dans ``` public/assets/styles ``` de même que les fichiers **JS** depuis ``` resources/js ``` jusqu'à ``` public/assets/scripts ```
Pour ce qui est des images ou des typo ou autres éléments utilisés il faut directement les mettres dans les dossiers correspondants dans ``` public/assets/ ``` 
Toutes les vues (et donc les pages HTML) sont répertoriées dans ``` /sources/View ``` et sont faites avec **TWIG**
J'utilise ici l'application ICOMOON pour générer les icônes, je mets directement les fichiers sources dans ``` public/assets/fonts/ ``` et dans mes fichiers **SASS** je les appelle normalement. Pour le débugage c'est simple, en effet **GULP** nous renvoie des fichiers maps qui lors de l'utilisation de l'inspecteur de la console nous donne la ligne et le fichier sass équivalent pour être plus rapide dans nos recherches.
Pour ce qui est du **HTML** nous utilisons le moteur de template _TWIG_ ( [https://twig.symfony.com/](https://twig.symfony.com/) ) pour un meilleur développement FRONT. Les fichiers se trouvent dans ``` /sources/Views ``` et sont regroupés dans des dossiers pour mieux se repérer. L'utilisation de twig permet de récupérer des variables et faire des _scripts_ front afin de pouvoir générer automatiquement notre HTML. Ces variables sont données par le Controller.

##Passage en PROD
Lors du Passage en Prod il faut changer manuellement un fichier ... Ce n'est pas grave en soi, c'est uniquement pour enelver le débugage de Twig. Il faut pour cela modifier le fichier ``` /sources/autoload.php ``` et commenter l'injection de Twig dans Klein pour le developpement et également décommenter la ligne pour la prod.

## L'architecture (importante) est donc : 

	| - README.md
	| - .gitignore
	| - autoloader.php (appelé par /public/index.php il charge tous nos controllers et fonctions)
	| - composer.json (sert à charger les dépendences BACK)
	| - gulpfile.js (l'automatiseur de tâche FRONT)
	| - package.json (sert à charger les dépendances FRONT)
	| - package.json (sert à charger les dépendances FRONT)
	| - classes
		| - autoloader.js (charge toutes les classes)
		| - MaClasse
			| - MaClasse.php
	| - public
		| - index.php
		| - assets
			| - styles
			| - images
			| - scripts
			| - fonts
	| - resources (le développement des assets pour le FRONT END)
		| - sass
			| - vendors
			| - general (dossier contenant toutes nos variables SASS)
		| - js
			| - vendors
	| - sources
		| - autoload.php (charge tous nos composants Back)
		| - routes.php (charge toutes les routes du projet)
		| - Controllers
			| - PostsController.php
		| - Models
			| - PostsModel.php
		| - Views
			| - Posts
				| - index.html

## BANGAAA!
> Allez hop on y va, en route pour l'aventure !

## Copyright
**© Simon Trichereau - 2017**