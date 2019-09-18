SDK U'RSelf
Developpement Framework Documentation
Annuler
Sauvegarder

La première version de notre Framework pour la création de la Marketplace U’RSELF est enfin prête. Nous allons voir dans ce guide documentation comment mettre en place le framework et comment commencer à bien travailler et se repérer pour un travail rapide et efficace.

​

Bien évidemment le framework n’en est qu’à sa première version et il peut évoluer rapidement selon les goûts et les habitudes de tout un chacun.

​

Je vous recommande chaudement la lecture de ce guide afin de bien comprendre comment marche le framework et pourquoi pas de réaliser vous-même l’exemple pratique proposé pour mieux le prendre en main. Cependant si vous aimez l’aventure et que vous êtes dépourvus de temps, vous pouvez tout aussi bien lire le README du projet qui synthétise parfaitement ce guide.

​

Je vous souhaite bon courage et surtout bon développement.

​

# 1 - Installation

​

Dans cette première partie nous verrons comment installer tout notre environnement de travail. Que ce soit pour le Front-End comme pour le Back-End, je vous demanderai de bien tout installer pour le bon fonctionnement du développement.

​

## 1.1 - Pré-Requis

​

Assurez vous d’avoir toutes ces technologies installées sur votre ordinateur:

​

* PHP >= 7.0 

* MySQL >= 5.7

* Apache >= 2.4

​

Maintenant nous allons télécharger le projet en utilisant Git. Peu importe où vous souhaitez télécharger le projet, nous verrons plus tard comment installer notre Vhost.

​

## 1.2 - Installation du Back-End

​

Bon maintenant que vous avez installer toutes les technologies nécessaires pour le bon fonctionnement de la marketplace, nous allons installer les dépendances Back-End, ceci étant dit il nous faut installer Composer pour pouvoir automatiser le tout : [https://getcomposer.org/](https://getcomposer.org/)

​

Maintenant que c’est fait vous pouvez utiliser composer pour installer les dépendances du Back-End en tappant `composer install` dans votre terminal

​

Ensuite il nous faut mettre en place le Vhost de Apache. Il y a de très bons tutoriels sur internet pour mettre en place un Vhost et donc je ne vais pas m’attarder longtemps là dessus, il y a cependant une information importante, le dossier racine doit être **./public/**  et surtout, vous devez avoir un alias qui pointe sur tous les sous domaines possibles : 

​

​

```batchfile

<VirtualHost :*80>

  ServerName marketplace.dev

  ServerAlias *.marketplace.dev

</VirtualHost>

```

​

Je m'explique, vous pouvez appeller votre url locale comme vous souhaitez, dans mon exemple j'utilise **marketplace.dev**, mais peu importe ce que vous choisissez, le ServerAlias doit pointer sur tous les sous-domaines possibles de mon url, exemple : ***.marketplace.dev* **. Retenez bien l’adresse de votre Vhost, vous en aurez besoin pour l’installation du Front-End.

​

Bon là nous allons partir sur la partie la plus complexe... En effet, notre marketplace fonctionne en *contextes*, il va falloir que chaque contexte ait sa base de donnée et son fichier de configuration qui lui sont propres. Tous les fichiers de configuration sont définis dans le dossier **./configs/**

​

Nous allons dans un premier temps créer notre fichier de configuration général, le root de la base de donnée locale. Copiez coller le fichier *configDB_base.json* et renommer le en ***configDB.json*** et rentrez vos identifiants root de votre base de donnée. Le plus important dans ce fichier est l'identifiant utilisateur et le mot de passe car l'application ne devra normalement pas pointer sur ce fichier.

​

### En local

Très bien, maintenant nous allons créer un nouveau contexte, à la racine du projet vous verrez un dossier *./devtools* contenant les dumbs SQL et un script php **create_context.php**. Depuis un terminal, lancez cette commande `php create_context.php <nom du contexte> <type de contexte>` => exemple : si vous voulez créer le fournisseur urself, faites cette commande : `php create_context.php urself fournisseur` ou de même pour le reseau cultura :  `php create_context.php cultura reseau`

Maintenant veuillez modifier votre fichier `hosts` pour y ajouter la ligne de votre contexte : `127.0.0.1 urself.marketplace.dev` ou `127.0.0.1 cultura.marketplace.dev` (n'oubliez pas de remplacer par l'url locale que vous aurez choisie!)

​

### Sur le serveur

Sur le serveur c'est plus simple, rendez vous à la racine même du server, là ou sont hébergés les dossiers prod/ et test/ et vous verrez que vous avez un fichier **create_context.php** que vous pouvez lancer de la même manière que en local : `php create_context.php <nom du contexte> <type de contexte>`. Le script génèrera tout lui même (côté prod et côté dev) et vous pourrez ainsi accéder a votre url contexte.marketplace.urself.fr 

​

Ce script va donc créer les bases de données du contexte ainsi que les utilisateurs qui pourront échanger avec ces memes bases de données et également le fichier de config qui sera ajouter au bon endroit. Ensuite, lorsque vous navigeur vers votre URL `context.marketplace.dev` l'URL est parsée pour chargée toute la configuration du bon fichier de configuration!

​

## 1.3 - Installation du Front-End

​

Pour ce qui est du Front-End c’est un peu pareil que pour le back, nous aurons besoin de Node et plus particulièrement du gestionnaire de paquets npm : [https://nodejs.org/fr/](https://nodejs.org/fr/) ainsi que de l’automatiseur de tâches gulp : [https://gulpjs.com/](https://gulpjs.com/)

​

Une fois que vous avez installer ces deux programmes, il vous suffit d’installer tous les paquets dont à besoin gulp pour fonctionner, dans le terminal entrez la commande suivante : `npm install` 

​

Ensuite, il vous faut changer une ligne dans le fichier **gulpfile.js** pour que l’automate du serveur de gulp fonctionne correctement, à la ligne 16, rentrez l’adresse que vous avez définit dans votre Vhost plus tôt dans la variable ***proxyServer***

​

Maintenant, depuis votre terminal en tappant `gulp` vous pouvez lancer l’automate qui va compiler les styles et les scripts et lancer le serveur dans votre navigateur sur lequel vous aller développer. A chaque changement dans un fichier de script, de style ou de php, l’automate actualisera votre page.

​

**Attention** : Si vous créer un nouveau fichier il vous faudra relancer l’automate car il ne le prend en compte qu’au démarrage. De même lorsque vous avez une erreur PHP qui s’affiche, il vous faudra recharger la page manuellement car la passerelle entre Gulp et votre navigateur se cassera si le HTML ne se charge pas.

​

# 2 - Guide Pratique

​

Maintenant que votre environnement fonctionne je vais vous expliquer comment vous y retrouver dans tout ce bazar… Ne vous en faites pas, à première vue cela peut paraître désordonné, mais en réalité c’est de l’organisation et une fois que vous l’aurez acquise vous verrez que c’est un jeu d’enfant de développer comme cela.

​

## 2.1 - Explication Back-End

​

Je vais commencer par la partie la plus difficile et la plus complexe le Back-End. Pour synthétiser rapidement le fonctionnement du framework, le navigateur charge une route, la route appelle un contrôleur et une fonction spécifique, la fonction du contrôleur appelle un manager de classe et le modèle, en fonction du modèle, gère la connexion avec la base de données pour renvoyer les données sous forme d’objet modèle au contrôleur qui les relie à la vue.

​

Vous n’avez rien compris ? Je vous rassure, c’est plus compliqué qu’il n’y paraît. Petite astuce, tous les fichiers relatifs au Back-End sont compris dans le dossier **./sources/**

​

### 2.1.1 - Les Routes

​

Commençons par le plus simple, les routes. Ce sont en fait l’ensemble des URL’s navigables par notre application. C’est à dire que si le navigateur tombe sur une route qui n’est pas définit, il renverra sur une page d’erreur 404.  Cet ensemble de routes se trouve dans le fichier **./sources/routes.php**

​

Les URL’s se composent de trois parties, la méthode, la route et la fonction renvoyée : 

```php

Router::get('/posts/{id?}', 'PostsController@view');

```

Si votre URL peut être appelée par plusieurs méthodes différentes, il faut utiliser **match** : `Router::match(['get', 'post'], '/create', 'PostsController@create');`

Dans ce même fichier vous trouverez en commentaire toutes les combinaisons possible pour définir aux mieux toutes les routes possibles. Pour expliquer l’exemple, je demande simplement au routeur que lorsque le navigateur arrive sur la page */posts/* en **GET** il doit me charger la fonction *view()* du contrôleur *PostsController*. Vous remarquerez que les routes peuvent définir des paramètres comme pour l’exemple précédent avec le paramètre **ID**. Nous pouvons rajouter des paramètres à la route pour par exemple la nommer et sutout pour passer des validations sur nos paramètres : 

```php

Router::get('/posts/{id?}', 'PostsController@view')->where(['id' => '[0-9]+']);

```

Maintenant ma route ne sera accessible que si l'ID est numérique. 

​

Le router permet également de créer des groupes de routes. C’est à dire que vous pouvez faire des sous ensemble de routes en les groupant par un dénominateur commun… Exemple : 

```php

Router::group(['prefix' => '/posts'], function() {

  Router::get('/{id?}', 'PostsController@view')->where(['id' => '[0-9]+']);

  Router::match(['get', 'post'], '/create', 'PostsController@create');

  Router::match(['get', 'patch'], '/edit/{id?}', 'PostsController@edit')->where(['id' => '[0-9]+']);

  Router::delete('/delete/{id}', 'PostsController@delete')->where(['id' => '[0-9]+']);

});

```

Dans cet exemple là je suis en train de dire à mon routeur qu’il devra gérer plusieurs routes qui ont le dénominateur commun **/posts/**. Ainsi, si je tombe sur l’URL */posts/* ou */posts/edit* le routeur me dirigera vers une fonction différente (d’un même contrôleur ou pas obligatoirement). Vous pouvez ajouter des paramètres supplémentaires à votre groupe comme par exemple une fonction de ***middleware*** qui par exemple checkerai si l'utilisateur est connecté.

```php

Router::group(['middleware' => Namespace\To\My\Middleware::class, 'prefix' => '/posts'], function() {

  Router::get('/{id?}', 'PostsController@view')->where(['id' => '[0-9]+']);

});

```

Ainsi le router passera en premier par le middleware avant de continuer, si le middleware le mermet, de continuer sur la route.

​

Cette fonction de groupage n’est pas obligatoire, ceci dit elle est très pertinente dans l’organisation et permet de mieux nous repérer.

​

### 2.1.2 - Les Contrôleurs

​

Nous attaquons un gros morceau du sujet. Le contrôleur nous sert principalement à 2 choses, jouer avec la base de donnée et afficher la vue. Il s’agit en fait d’une classe suffixé par *Controller*, rangée dans le dossier **./source/Controllers/** qui étend la classe *Controller* de base (notamment pour avoir accès a la fonction de rendu) et composée de plusieurs fonctions **publiques**.

​

Ensuite il ne vous reste plus qu’à développer ce que bon vous semble à l’intérieur de ces fonctions. Vous pouvez appeler les manageurs ou les modèles pour jouer avec les données. Le rendu **Twig** se fait de la sorte : `$this->render('Posts/post.html', $params);` où *Posts/post.html* est la route du fichier de la vue et *$params* un tableau contenant toutes les données envoyé à la vue pour en assurer sa construction. Il y a des variables globales que nous verrons plus tard qu’il ne faut pas oublier dans cette fameuse variables $params.

​

### 2.1.3 - Les Modèles

​

Un modèle est en fait une classe qui fait référence à une table dans la base de donnée. Il sert uniquement à organiser nos données par rapport à la base de donnée, mais en aucun cas il ne communique avec elle, pour cela il a besoin d’un manageur. Ces classes n’ont pas de suffixes. 

​

Prenons l’exemple d’une table **utilisateurs**

​

<table>

  <thead>

    <tr>

      <th colspan="3">Utilisateurs</th>

    </tr>

  </thead>

  <tbody>

    <tr>

      <td>

        <em>id</em>

      </td>

      <td>

        <em>pseudo</em>

      </td>

      <td>

        <em>mot_de_passe</em>

      </td>

    </tr>

    <tr>

      <td>1</td>

      <td>Test</td>

      <td>123@ZeRTy</td>

    </tr>

  </tbody>

</table>

​

Le modèle relatif à cette table sera donc contenu dans **./sources/Models** et on l’appellera **Utilisateur.php**. Il ressemblera à ceci : 

```php

<?php

/**

* Utilisateur

*/

class Utilisateur extends Model

{

  private $id;

  private $pseudo;

  private $motDePasse;

  /*GETTERS*/

  public function getId()

  {

    return $this->id;

  }

  public function getPseudo()

  {

    return $this->pseudo;

  }

  public function getMotDePasse()

  {

    return $this->motDePasse;

  }

  /*SETTERS*/

  public function setId($id)

  {

    $this->id = $id;

  }

  public function setPseudo($pseudo)

  {

    $this->pseudo = $pseudo;

  }

  public function setMotDePasse($motDePasse)

  {

    $this->motDePasse = $motDePasse;

  }

}

```

Décortiquons ce modèle. Nous remarquons dans un premier temps que toutes les variables sont private (ou protected). En effet nous ne devons pas pouvoir accéder directement aux variables en dehors de la classe. C’est pour cela que nous avons les fonctions publiques *Getters* qui permettent de récupérer les variables ainsi que les *Setters* qui permettent d’instancier ces variables.

La fonction étend une classe Modèle dont le constructeur permet ainsi de créer un objet de type Utilisateur nous donnant accès à ces fonctions là. Le constructeur fait appel à la fonction d’hydratation de la classe étendue Modèle qui permet d’instancier directement, à partir d’un tableau donné, les variables du modèle. **Attention** pour que l’hydratation fonctionne il faut que le tableau soit constitué ainsi : 

```javascript

[

  'id' => 1,

  'pseudo' => 'Test',

  'motDePasse' => '123azerty'

]

```

(c’est à dire que les index soient le noms des variables du modèle, ne vous en faites pas le manageur fera tout pour nous!)

​

**REMARQUE IMPORTANTE** : Je tiens à préciser que la syntaxe en Base de Donnée est en **snake**, faite avec des underscores, tandis que dans le Php nous utilisons du **camelCase**. Ne vous en faites pas, le constructeur de la classe Model fait la transition pour vous !

​

### 2.1.4 - Les Manageurs

​

Le manageur est celui qui fait la transition entre les modèles et la base de données. Il se trouve dans le dossier **./sources/Models/Managers/** et est suffixé de *Manager*. De la même manière que le modèle, le manageur étend la classe *Manager.php* dont le constructeur instancie la base de donnée.

​

Finalement le manager est très facile à mettre en place, voici l’exemple d’un manageur en reprenant l’exemple du modèle Utilisateur :

```php

<?php

/**

* Utilisateurs Manager extends Manager

*/

class UtilisateursManager

{

  public function add(Post $post)

  {

    //Fonction qui ajoute une entrée dans la base de donnée

  }

  public function get($id = null)

  {

    if (is_null($id)) {

      //Fonction qui renvoie toutes les entrées de la base de données

    } else {

      //Fonction qui renvoie l'entrée de la base de donnée en fonction de l'ID

    }

  }

  public function update(Post $post)

  {

    //Fonction qui permet de mettre à jour une entrée dans la base de donnée

  }

  public function delete(Post $post)

  {

    //Fonction qui permet de supprimer une entrée dans la base de donnée

  }

}

```

​

Remarques : 

​

Certaines fonctions prennent en paramètres une variable $utilisateur de type Utilisateur, c’est parce que nous avons créer le modèle Utilisateur que cela est possible. 

​

La fonction du constructeur de la classe étendue instancie une classe Database, il s’agit de classes qui sont rangées dans les modèles mais qui ne sont pas associées à une table en base de donnée. Ces classes sont générales et « partagées » entre les autres classes. Nous y reviendront un peu plus tard. Ainsi depuis mon manageur j’ai accès à cette instanciation grâce à la variable privée $db.

​

Voilà, c’est tout ce que j’ai à dire sur le manageur, ce n’est pas très compliqué, c’est lui qui gérer l’interaction avec la base de donnée, en fonction d’un modèle donné.

​

### 2.1.5 - Les autres classes

​

Bon, comme nous en avons parlé un peu plus tôt, notre système a besoin d’autres classes pour fonctionner. Ces classes sont rangées dans **.sources/Class/** mais ne sont pas considérées comme des modèles vu qu’elles ne sont rattachées à aucune table en base de donnée. Si nous reprenons l’exemple ci-dessus, dans notre manageur nous faisons appel à la classe *Database*, cette classe permet d’instancier la connexion avec la base de donnée, et elle est partagée entre tous les manageurs. Ce sont donc des classes « outils » utilisables par toutes les autres classes (contrôleur ou manageur) et qui servent à faire des actions répétées au cours de notre application.

​

Nous pourrions facilement penser à au moins 4 de ces classes qui seraient utiles dans n’importe quel projet :

​

* **Database.php** : Pour l’instanciation de la connexion à la base de donnée

* **Validator.php** : Pour valider les données reçues lors d’un envoi par formulaire

* **Paginator.php** : pour transformer notre flux de données en pagination

* **Tools.php** : Une classe regroupant les fonctions les plus générales du site comme la suppression d'un fichier par exemple...

​

Bref, à vous de créer toutes les classes générales que vous jugez utiles pour le bon fonctionnement de l’application mais attention aux doublons tout de même!

​

## 2.2 - Explication Front-End

​

Ce n’est pas parce que j’ai dit que le Back-End était la partie la plus complexe qu’il faut négliger le Front-End. Je ne vais pas rentrer dans les principes du **HTML** et **CSS**, juste vous expliquer où sont les fichiers et comment les développer. Ensemble respectons le W3C et les grands principe du web. Je vais aussi faire un petit cours ultra rapide sur ***TWIG*** pour que vous puissiez comprendre de quoi il s’agit et comment le maîtriser.

​

### 2.2.1 - Les Vues

​

Souvenez vous des 2 principaux rôles d’un contrôleur, aller chercher les données et les compiler dans la vue. La vue c’est ce que le navigateur affichera, c’est à dire le code **HTML** structuré composé de toutes les données que le contrôleur aura fournies pour fabriquer la page demandée par la route. Ces fichiers se trouvent dans **./sources/Views/** et nous conseillons de regrouper les vues dans des dossiers, de même que nous avons regroupés les routes. Si nous reprenons l’exemple des routes, nous avions 4 sous routes : l’index, la création, l’édition et la suppression… Ceci étant dit, l’index est composé différemment s’il pointe sur tous les posts ou sur un post spécifique et c’est pourquoi je recommande de créer 2 vues séparées. Nous aurons donc dans un sous-dossier **Posts** :

​

* **./sources/Views/Posts/index.html** qui affiche tous les posts

* **./sources/Views/Posts/post.html** qui affiche un post spécifique

* **./sources/Views/Posts/create.html** qui affiche le formulaire de création d’un post

* **./sources/Views/Posts/edit.html** qui affiche le formulaire d’édition d’un post

​

Là vous devriez remarquez qu’il n’y a pas forcément une vue par route, en effet la fonction de suppression n’est associée à aucune vue. Si vous observez bien les routes vous verrez qu’elle n’est accessible qu’en DELETE et donc nous n’attendons pas forcément une vue. Il est également possible de regrouper si vous le souhaitez des vues, c’est à dire que finalement la création et l’édition sont les mêmes pages, ce sont les actions qui diffèrent, actions qui sont manipulées avec Javascript et donc nous y reviendrons plus tard. 

​

Très bien, j’imagine que vous avez du ouvrir un de ces fichiers et maintenant vous vous demandez sûrement qu’est-ce que c’est que ce bordel ? C’est du **HTML** ? Et bien oui et non, c’est du ***TWIG*** !

​

### 2.2.2 - Twig, quézako ?

​

Twig est un moteur de template, c’est à dire qu’il va générer du HTML à partir morceaux de pages le tout en implémentant des données qu’il aura reçu. C’est une grande aide pour le développement Front-End et pour la meilleure lisibilité du code. Il permet également de mettre des fonctions dans notre HTML, par exemple si nous avons telle donnée ou non ce qu’il doit afficher, ou même faire des boucles… Si vous ne connaissez pas, vous pouvez regarder la documentation ou sinon en cherchant sur internet, je ne doute pas de vos capacités d’apprentissage.

​

Ceci étant dit, j’aimerais que vous en compreniez le fonctionnement général et c’est pour ça que je vais vous expliquer le système mis en place.  Vous remarquerez que dans le dossier **./sources/Views/** nous avons un fichier *wireframe.html*, il s’agit du squelette général de toutes nos pages web, mais cette page n’est qu’un template, elle ne sera jamais invoquée directement, elle nous sert uniquement de modèle pour nos autre pages. Pour un soucis de compréhension nous avons choisi de préfixer ces pages spéciales, car on peut en faire plusieurs, par un underscore ( _ ).

​

Maintenant quand vous créer un page Twig, vous devez lui dire qu’il doit étendre le modèle du squelette, c’est à dire qu’il va en quelque sorte copier / coller votre squelette dans votre nouvelle vue avec les mêmes caractéristiques. Vous aurez ainsi accès aux systèmes de blocks définis dans la page squelette.

​

Nous arrivons maintenant aux blocs, véritable aide dans le développement front. Si vous découpez vos pages en blocs, dans vos fichiers enfants vous n’aurez plus qu’a réécrire les blocks que vous souhaitez changer. Par exemple, si je prends la vue **Posts/create.html** je n’ai besoin que de modifier le block du contenu de la page, je garderais donc le même header et le même footer. Ceci étant dit j’aimerais rajouter un script au footer qui n’est utilisé que par cette page-ci, je vais donc utiliser la fonction {{ parent() }} du système de bloc pour récupérer le contenu de la page squelette et le réinjecter dans mon nouvel élément. Voici donc ce que nous obtenons : 

```php

{% extends "_wireframe.html" %}

{% block content %}

  <!-- Nouveau Contenu -->

{% endblock %}

{% block footer %}

  {{ parent() }}

  <script type="text/javascript" src="/assets/scripts/posts.js"></script>

{% endblock %}

```

Dans un premier temps nous étendons (copie/colle) notre squelette de page, ensuite nous réécrivons le block du contenu puis enfin nous rajoutons une ligne dans notre block footer.

​

### 2.2.3 - Les assets

​

Les assets sont l’ensemble des éléments appelés par les fichiers FRONT qui permettent le meilleur rendu de la page, c’est à dire les styles, les scripts, les images et pourquoi pas les typographies. 

​

Le dossier d’assets se trouve dans **./public/assets** et vous verrez que certaines catégories d’assets peuvent avoir un fichier « vendors », il s’agit des bibliothèques externes, que l’on stockera tout le temps localement, qui nous permettent de développer plus facilement. Pour ajouter un vendor, il faut aller dans **./ressources/sass/vendors** ou **./ressources/js/vendors**  et ajouter les fichiers de notre librairie externes qui nous intéressent. Nous trouverons entre autre Bootstrap et jQuery par exemple, mais je vous invite à voir la liste des librairies associées un peu plus tard dans ce guide. Cependant il y a une petite nuance qu’il ne faut pas perdre de vue, vous vous souvenez de ***Gulp***, l’automatiseur de tâches, il ne sert pas uniquement au rafraîchissement de la page web, il permet également de vérifier, minimiser vos styles et scripts et regrouper tous vos vendors dans un seul fichier!

​

C’est à dire que tout ce qui n’est pas considéré comme vendor doit avoir été développé par vous-même ou un collègue., vérifié syntaxiquement et minimisé par Gulp. **Ceci ne concerne que les scripts et les styles pour l’instant.** Pour se faire il faut donc aller dans lo dossier **./resources/** dans lequel vous trouverez les fichiers sources en **SASS** ou en **JS** non vérifiés et non minimisés. A chaque changement dans l’un de ces fichiers, l’automate Gulp va alors faire son travail et les envoyer vers le dossier d’assets. Concernant les fichiers sass, vous verez qu’il y a un dossier **general**, c’est là où vous trouverez toutes les variables du sites, qu’il s’agisse des couleurs, des typographies, des dimensions…

​

**Petite remarque** sur le fonctionnement pratique, les routes sont appelés par des méthodes, GET, PATCH, POST, DELETE, pour jouer sur ces différentes méthodes nous devons utiliser un système comme **Ajax** pour envoyer les informations au contrôleur. Ainsi donc la partie des scripts est très importante dans le développement Front et il ne faut pas la négliger. Nous utiliserons donc la librairie jQuery pour simplifier le développement Javascript.

​

# 3 - Librairies utilisées et/ou préconisées

​

## 3.1 - PHP

​

* PDO – Extension native pour se connecter à la base de donnée

* Twig – Moteur de template [(https://twig.symfony.com/)](https://twig.symfony.com/)

* Composer – Gestionnaire des dépendances [(https://getcomposer.org/)](https://getcomposer.org/)

* Pecee Router – Simple PHP Router [(https://github.com/skipperbent/simple-php-router)](https://github.com/skipperbent/simple-php-router)

​

## 3.2 - CSS

​

* SASS – Préprocesseur CSS [(http://sass-lang.com/)](http://sass-lang.com/)

* Bootstrap – Framework  [(https://getbootstrap.com/)](https://getbootstrap.com/)

​

## 3.3 - JS

​

* jQuery – Librairie JS [(http://jquery.com/)](http://jquery.com/)

* Bootstrap – Framework Front [(https://getbootstrap.com/)](https://getbootstrap.com/)

* SweetAlert – Popup Boxes [(https://limonte.github.io/sweetalert2/)](https://limonte.github.io/sweetalert2/)

Propulsé par Wiki.js.

    Accueil
    Retour en haut
