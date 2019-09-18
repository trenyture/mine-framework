Pour prendre en main le framework il n’y a rien de mieux qu’un exemple pratique sous forme de tutoriel. J’essaierai d’être le plus concis mais de bien vous expliquer ce qu’il se passe à chaque fois. Pour l’exemple je pars du principe que vous avez votre environnement installé, et que le framework a été configuré avec votre base de donnée existante. Nous allons donc réaliser ensemble le **CRUD** (*Create, Read, Update, Delete*) d’une table ***Produits***

# 1 - Le Back-End

## 1.1 - La base de donnée

Nous allons donc créer dans la base de donnée une table ***Produits*** et y insérer des données de tests de la manière suivante :

| id | titre | description | prix | date_creation |
| --- | --- | --- | --- | --- |
| 1 | Doliprane | Très efficace contre les maux de têtes c’est l’allié contre vos migraines | 3,00 | --- |
| 2 | Ibuprophène | Moins cher que son concurrent il n’est pas pour autant inefficace | 2,69 | --- |

Voilà donc la requête SQL afin de créer ces informations (ainsi que la table) :
```sql
CREATE TABLE `produits` (
  `id_produit` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `titre_produit` varchar(90) COLLATE 'utf8_bin' NOT NULL,
  `description_produit` longtext COLLATE 'utf8_bin' NULL,
  `prix_produit` decimal(6,2) NOT NULL,
  `date_creation_produit` timestamp NOT NULL
) ENGINE='InnoDB' COLLATE 'utf8_bin';
INSERT INTO `produits` (`titre_produit`, `description_produit`, `prix_produit`, `date_creation_produit`) 
VALUES ('Doliprane', 'Très efficace contre les maux de têtes c’est l’allié contre vos migraines', '3', now()),
        ('Ibuprophène', 'Moins cher que son concurrent il n’est pas pour autant inefficace', '2.69', now());
```
Je ne m’attarderai pas sur la base de données, essayons juste de mettre l’encodage en **utf-8** le plus possible s’il vous plaît, ainsi que de respecter la syntaxe en **Snake** et de suffixer chaque attribut par le nom de sa table.

## 1.2 - Les routes

Nous allons maintenant modifier notre fichier de routes **./sources/routes.php** pour y ajouter toutes les routes dont nous avons besoin pour faire le CRUD. Nous avons donc 4 actions à maîtriser :

* L’affichage d’un ou de plusieurs produits
* La création d’un nouveau produit
* L’édition d’un produit existant
* La suppression d’un produit

Nous allons regrouper toutes ces routes dans un ensemble pointant sur **/produits** et nous allons donc rajouter tout cela à notre fichier de routes :
```php
Router::group(['prefix' => '/produits'], function() {
	Router::get('/{id?}', 'ProduitsController@view')->where(['id' => '[0-9]+']);
	Router::match(['get', 'post'], '/create', 'ProduitsController@create');
	Router::match(['get', 'patch'], '/edit/{id?}', 'ProduitsController@edit')->where(['id' => '[0-9]+']);
	Router::delete('/delete/{id}', 'ProduitsController@delete')->where(['id' => '[0-9]+']);
});
```
Vous remarquerez que j’envoie le paramètre ID (comme un integer) pour l’affichage, la création et la suppression. Cependant pour l’affichage j’ai rajouter un ? Pour faire comprendre au router qu’il est possible qu’il n’y ait pas ce paramètre dans l’URL, c’est le cas où j’affiche tous mes produits.

J’utilise la méthode POST pour la création et la méthode PATCH pour l’édition, mais c’est comme vous souhaitez, j’essaie de garder une certaine logique Rest.

## 1.3 - Le Modèle

Maintenant il nous faut créer le modèle de donnée, c’est à dire l’objet de type Produit. Dans **./sources/Models/** nous allons donc créer la classe *Produit.php* Attention : **remarquez bien comme je la met au singulier**, c’est pour conserver une certaine logique : Lorsque je récupère un objet de ce modèle j’aime bien avoir un objet de type **Produit** et non de type ~~Produits~~ et si j’en récupère plusieurs alors j’aurais plusieurs objets de type **Produit**.

Pour créer le modèle c’est très simple, dans un premier temps il ne faut pas oublier d’étendre la classe Model. Ensuite chaque variable **privée** représente un attribut de la table, puis il y a une fonction *Getter* et *Setter* pour chacune des variables. Nous avons donc le code suivant : 
```php
class Produit extends Model {
	private $idProduit;
	private $titreProduit;
	private $descriptionProduit;
	private $prixProduit;
	private $dateCreationProduit;
	
	public function getIdProduit()
	{
		return $this->idProduit;
	}
	
	public function getTitreProduit()
	{
		return $this->titreProduit;
	}
	
	public function getDescriptionProduit()
	{
		return $this->descriptionProduit;
	}
	
	public function getPrixProduit()
	{
		return $this->prixProduit;
	}
	
	public function getDateCreationProduit()
	{
		return $this->dateCreationProduit;
	}
	
	public function setIdProduit($idProduit)
	{
		$this->idProduit = $idProduit;
	}
	
	public function setTitreProduit($titreProduit)
	{
		$this->titreProduit = $titreProduit;
	}
	
	public function setDescriptionProduit($descriptionProduit)
	{
		$this->descriptionProduit = $descriptionProduit;
	}
	
	public function setPrixProduit($prixProduit)
	{
		$this->prixProduit = $prixProduit;
	}
	
	public function setDateCreationProduit($dateCreationProduit)
	{
		$this->dateCreationProduit = $dateCreationProduit;
	}
}
```
Maintenant lorsque nous ferons appel à ce modèle par le manageur ou autre, nous aurons un objet de type Produit. Attention de respecter le **camelCase** pour différencier le php de la base de donnée.

## 1.4 - Le Manageur

Très bien, maintenant le modèle en place nous allons créer notre manageur que l’on mettra dans **./sources/Models/Managers/** et que l’on appellera donc *ProduitsManager.php* 

Pour faire un manageur c’est très simple, il faut étendre la classe Manager, et puis il faut créer une fonction pour chaque action que l’on veut exécuter sur la base de donnée. Dans notre cas, nous sommes en train de réaliser un CRUD et donc nous avons 4 fonctions : l’affichage, la création, l’édition et la suppression.

**Remarque** : Certains développeurs aiment avoir deux fonctions différentes pour l’affichage, la fonction Get et GetAll, personnellement je préfère faire un switch dans une seule et même fonction. Chacun ses goûts.

Je vais également mettre tout le code SQL dans les fonctions mais je ne vais pas trop m’attarder dessus, sachez juste que le code de chaque fonction permet de réaliser l’action souhaitée sur la base de donnée.
```php
class ProduitsManager extends Manager{

	public function add(Produit $produit){
		$sql = "
			INSERT INTO
				produits(
					titre_produit,
					description_produit,
					prix_produit,
					date_creation_produit
				)
			VALUES
				(
					:titreProduit,
					:descriptionProduit,
					:prixProduit,
					:dateCreationProduit
				)
		";
		$q = $this->db->prepare($sql);
		$q->bindValue(':titreProduit', $produit->getTitreProduit());
		$q->bindValue(':descriptionProduit', $produit->getDescriptionProduit());
		$q->bindValue(':prixProduit', $produit->getPrixProduit());
		$q->bindValue(':dateCreationProduit', $produit->getDateCreationProduit());
		return $q->execute();
	}
	
	public function get($idProduit = null){
		if (is_null($idProduit)){
			$sql = '
				SELECT 
					id_produit,
					titre_produit,
					description_produit,
					prix_produit,
					date_creation_produit
				FROM
					produits
			';
			$produits = [];
			$q = $this->db->query($sql);
			
			while ($donnees = $q->fetch(PDO::FETCH_ASSOC)){
				$produits[] = new Produit($donnees);
			}
			return $produits;
		}else{
			$idProduit = (int) $idProduit;
			$sql = '
				SELECT
					id_produit,
					titre_produit,
					description_produit,
					prix_produit,
					date_creation_produit
				FROM
					produits
				WHERE
					id_produit = '.$idProduit;
			$q = $this->db->query($sql);
			$donnees = $q->fetch(PDO::FETCH_ASSOC);
			return new Produit($donnees);
		}
	}
	
	public function update(Produit $produit){
		$sql = '
			UPDATE
				produits
			SET
				titre_produit = :titreProduit,
				description_produit = :descriptionProduit,
				prix_produit = :prixProduit,
				date_creation_produit = :dateCreationProduit
			WHERE
				id_produit = :idProduit
		';
		$q = $this->db->prepare($sql);
		$q->bindValue(':titreProduit', $produit->getTitreProduit());
		$q->bindValue(':descriptionProduit', $produit->getDescriptionProduit());
		$q->bindValue(':prixProduit', $produit->getPrixProduit());
		$q->bindValue(':dateCreationProduit', $produit->getDateCreationProduit());
		$q->bindValue(':idProduit', $produit->getIdProduit());
		return $q->execute();
	}

	public function delete(Produit $produit){
		$sql = '
			DELETE FROM 
				produits
			WHERE
				id_produit = :idProduit
		';
		$q = $this->db->prepare($sql);
		$q->bindValue(':idProduit', $produit->getIdProduit());
		return $q->execute();
	}
}
```
C’est beaucoup de code mais en soit ce n’est pas très compliqué. Le plus difficile c’est de ne pas se tromper entre le **camelCase** (PHP) et le **Snake** (SQL). Et c’est tout ce que fait le manager. Vous remarquerez qu’il nous arrive de passer des objets de type **Produit** c’est parce que nous avons créer le modèle produit que nous en sommes capable.

## 1.5 - Le contrôleur

Voilà la dernière partie du Back-End et pas la plus simple. Maintenant que nous avons nos routes, nous allons définir chacune de nos fonctions associées à ces routes là. Nous allons donc créer le fichier **ProduitsController.php** dans **./sources/Controllers/**

Nous devons faire une fonction statique pour chaque fonction que nous avons déclaré par route un peu plus tôt. Dans notre cas nous pouvons voir que nous avons relier 4 fonctions différentes : `view`, `create`, `edit`, `delete`, ce qui nous donne donc le contrôleur suivant : 
```php
class ProduitsController
{

	public function view($id = null){
		$ProduitsManager = new ProduitsManager();
		
		/*S'il n'y a pas d'ID passé en paramètres : on affiche tous les produits*/
		if(is_null($id)){
			$produits = $ProduitsManager->get();
			$params = [
				'datas'=> [
					'pageTitle' => 'Tous les Prodtuis',
					'produits'=> $produits
				],
			];
			echo $this->render('Produits/index.html', $params);
		}else{
			/*Sinon il y a un ID passé en paramètre : on affiche uniquement le produit choisi*/
			$produit = $ProduitsManager->get($id);
			$params = [
				'datas'=> [
					'pageTitle' => 'Fiche Produit : '.$produit->getTitreProduit(),
					'produit'=> $produit
				],
			];
			echo $this->render('Produits/produit.html', $params);
		}
	}

	public function create(){
		/*On regarde quelle méthode est utilisée pour appeler la page*/
		switch (strtoupper($this->request->getMethod())) {
			/*Si c'est du GET on affiche le formulaire*/
			case 'GET':
				$params = [
					'datas'=> [
						'pageTitle' => 'Créer un nouveau Produit',
						'formId' => 'produitsCreateForm'
					],
				];
				echo $this->render('Produits/edition.html', $params);
				break;
			/*Si c'est du POST on fait le traitement des données*/
			case 'POST':
				//on sauvegarde
				$produit = new Produit();
				$ProduitsManager = new ProduitsManager();
				$produit->setTitreProduit(htmlspecialchars($_POST['titreProduit']));
				$produit->setDescriptionProduit(htmlspecialchars($_POST['descriptionProduit']));
				$produit->setPrixProduit(htmlspecialchars($_POST['prixProduit']));
				// Echo TRUE ou FALSE en fonction de la requête SQL
				echo json_encode($ProduitsManager->add($produit));
				break;
		}
	}

	public function edit($id){
		/*Comme le produit est déjà créé, on va prendre ses données*/
		$ProduitsManager = new ProduitsManager();
		$produit = $ProduitsManager->get($id);
		
		/*De la même manière on regarde la méthode utilisée*/
		switch (strtoupper($this->request->getMethod())) {
			case 'GET':
				$params = [
					'datas'=> [
						'pageTitle' => 'Editer le produit '.$produit->getTitreProduit(),
						'formId' => 'produitsEditForm',
						'produit' => $produit
					],
				];
				echo $this->render('Produits/edition.html', $params);
				break;
			case 'PATCH':
				/*Comme Php ne dispose pas d'un $_PATCH on va le créer nous même*/
				parse_str(file_get_contents('php://input'), $_PATCH);
				$produit->setTitreProduit(htmlspecialchars($_PATCH['titreProduit']));
				$produit->setDescriptionProduit(htmlspecialchars($_PATCH['descriptionProduit']));
				$produit->setPrixProduit(htmlspecialchars($_PATCH['prixProduit']));
				echo json_encode($ProduitsManager->update($produit));
				break;
		}
	}

	public function delete($id){
		/*Pas besoin de regarder la méthode vu que le router ne laissera passer que la methode DELETE*/
		$ProduitsManager = new ProduitsManager();
		$produit = $ProduitsManager->get($id);
		echo json_encode($ProduitsManager->delete($produit));
	}
}
```
Voilà, notre contrôleur est terminé. Vous remarquerez que certaines fonctions renvoient soit une vue en HTML soit du JSON, ce-dernier sera exploité par le JavaScript plus tard.

J’ai essayé de mettre beaucoup de commentaires pour que vous compreniez bien, ce controleur est très rudimentaire et il ne propose aucune vérification des données qu’il reçoit, mais c’est pour que vous compreniez bien à quoi cela sert et comment l’utiliser rapidement.

# 2 - Le Front-End

Maintenant nous allons passer aux vues. Comme vous l’avez sûrement compris en voyant le contrôleur nous aurons besoin de 3 vues différentes que nous allons mettre dans un sous-dossier **/Produits/** et le tout sera placé dans **./sources/Views**

## 2.1 - Affichage de tous les produits

Nous allons créer le fichier *index.html* qui affichera tous les produits. Vous devez vous souvenir qu’à chaque fois que nous faisions un rendu Twig nous passions le chemin de la vue ainsi qu’un tableau de données. Il va falloir que nous le prenions en compte pour compléter notre vue.

```twig
{% extends "_wireframe.html" %}
{% block content %}
	<main class="row">
		<section class='col-xs-6 col-sm-8'>
			<h1>{{ datas.pageTitle }}</h1>
			<a href="/produits/create">Créer un nouveau Produit</a>
			<div class="row">
				{% for produit in datas.produits %}
					<article class="col-xs-6 col-sm-4">
						<a href="/produits/{{ produit.getIdProduit() }}">
							<h2>{{ produit.getTitreProduit() }}</h2>
							<small>{{ produit.getPrixProduit }} €</small>
						</a>
					</article>
				{% else %}
					<p class="col-xs-12"><strong>Il n'y a aucun Produit pour le moment. Veuillez en créer un</strong></p>
				{% endfor %}
			</div>
			</ul>
		</section>
	</main>
{% endblock %}
```

## 2.2 - Affichage d'un seul produit

Bon continuons sur notre lancée pour créer la vue qui affichera un seul produit. Nous l’appellerons sobrement *produit.html*. Cette fois elle affichera en détail les données du produit.

```twig
{% extends "_wireframe.html" %}
{% block content %}
	<main class="row">
		<section class='col-xs-6 col-sm-8'>
			<h1>
				{{ datas.pageTitle }}
				<small>
					<a href="/produits/edit/{{ datas.produit.getIdProduit() }}">Editer</a>
				</small>
			</h1>
			<small>Le {{ datas.produit.getDateCreationProduit()|date("d F Y") }}</small>
			<div class="produit_content">
				{{ datas.produit.getDescriptionProduit() }}
			</div>
			<strong>{{ datas.produit.getPrixProduit() }} €</strong>
		</section>
	</main>
{% endblock %}
```

## 2.3 - Création / édition d'un produit

Enfin il ne nous reste plus que la page d’édition du produit. Si vous le souhaitez vous pouvez scinder cette page en deux pages différentes, une page *creation.html* et une page *edition.html* personnellement j’ai décidé de ne faire qu’une seule page, en revanche je passe un paramètre supplémentaire dans toutes les données que j’envoie à la vue qui est formId et qui me permettra de différencier la création de l’édition.

```twig
{% extends "_wireframe.html" %}
{% block content %}
	<main class="row">
		<section class='col-xs-6 col-sm-8'>
			<h1>{{ datas.pageTitle }}</h1>
			<form id="{{ datas.formId }}">
				<div class="form-group">
					<label for="titreProduit">Titre</label>
					<input type="text" class="form-control" name="titreProduit" id="titreProduit" placeholder="Entrer un titre" value="{{ datas.produit.getTitreProduit() }}">
				</div>
				<div class="form-group">
					<label for="descriptionProduit">Description du Produit</label>
					<textarea class="form-control" name="descriptionProduit" id="descriptionProduit" placeholder="Entrer votre contenu">{{datas.produit.getDescriptionProduit() }}</textarea>
				</div>
				<div class="form-group">
					<label for="prixProduit">Prix du Produit</label>
					<input type="text" class="form-control" name="prixProduit" id="prixProduit" placeholder="Entrer le prix du produit" value="{{datas.produit.getPrixProduit() }}">
				</div>
				<button type="submit" class="btn btn-primary">Submit</button>
				<a href='#' id="removeProduit" data-produitid="{{ datas.produit.getIdProduit() }}" class="btn btn-warning">Supprimer</a>
			</form>
		</section>
	</main>
{% endblock %}
{% block footer %}
	{{ parent() }}
	<script type="text/javascript" src="/assets/scripts/produits.js"></script>
{% endblock %}
```

## 2.4 - Développement du Script

Comme vous l’avez remarqué sur la dernière vue que nous avons fait, nous passons un fichier Javascript que nous rajoutons au block *footer*. Ce fichier nous servira à définir les différentes actions lors de l’envoi du formulaire. Ne vous en faites pas il va y avoir de l’ajax.

Je vous laisse styliser les pages comme bon vous semble, en revanche pour ce qui est du script laissez moi vous montrer la base de la base.

Commençons par créer le fichier dans **./resources/js/** et nommons le *produits.js*

Il va falloir gérer 3 cas particuliers, la création, l’édition ainsi que la suppression d’un produit. Nous allons récupérer les données du formulaire et les envoyer au contrôleur grâce à de l’AJAX. Nous passerons pour des soucis de rapidité sur la validation côté client, comme nous l’avons passé côté serveur, mais n’oubliez pas que c’est **IMPORTANT**.

Voilà donc le fichier que vous devriez obtenir :

```javascript
jQuery(
	function($) {

		var produitId = $('a#removeProduit').data('produitid') || -1;

		/*Suppression*/
		$('a#removeProduit').click(function(event) {
			$.ajax({
				url: '/produits/delete/' + produitId,
				type: 'DELETE',
				dataType: 'JSON',
				success: function(result){
					if(result === true){
						alert('Le produit a bien été supprimé');
						window.location.href = '/produits';
					}else{
						console.log('Problème de suppression');
					}
				}
			});

			event.preventDefault();
			return false;
		});

		/*Création Produit*/
		$('form#produitsCreateForm').submit(function(event) {
			var datas = formToJSON(this);
			$.ajax({
				url: window.location.href,
				type: 'POST',
				data: datas,
				dataType: 'JSON',
				success: function(result) {
					if (result === true) {
						alert('Le produit a bien été sauvegardé');
						window.location.href = '/produits';
					} else {
						console.log('Problème de sauvegarde');
					}
				}
			});
			event.preventDefault();
			return false;
		});

		/*Edition Produit*/
		$('form#produitsEditForm').submit(function(event){
			var datas = formToJSON(this);
			$.ajax({
				url: window.location.href,
				type: 'PATCH',
				data: datas,
				dataType: 'JSON',
				success: function(result) {
					if (result === true) {
						alert('Le produit a bien été édité');
						window.location.href = '/produits/' + produitId;
					} else {
						console.log("Problème d'édition");
					}
				}
			});
			event.preventDefault();
			return false;
		});
	}
);
```

Vous remarquerez que j’utilise une fonction **formToJSON** qui est une fonction que j’ai créée pour récupérer toutes les valeurs d’un formulaire et les trasformées en JSON, pour faciliter l’échange de données. Je n’ai pas utilisé dans l’exemple la librairie de *SweetAlert* et c’est un peu rudimentaire, mais au moins vous avez l’essentiel pour que cela fonctionne.

Maintenant il ne vous reste plus qu’à lancer dans le terminal **Gulp**, croiser les doigts, prier un bon coup et admirer le résultat.
