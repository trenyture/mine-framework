<?php
	use Pecee\SimpleRouter\SimpleRouter as Router;

	/* 	Documentation : https://github.com/skipperbent/simple-php-router

		En gros 2 cas de figures pour déclarer les routes:
		Router::$verb($route, $function)->$option
			$verb : est une fonction comme get, post, put, delete <=> exemple : Router::post()
		Router::match($verb, $route, $function)->$option
			verb : est un array de strings get, post, put, delete <=> exemple : Router::match(['get','post','delete'])
			----- 
			route : le chemin avec les possibles paramètres envoyés au controller
			function : La fonction du controlleur associé
			option : des paramètres supplémentaires notamment pour nommer les routes ou bien pour vérifier les paramètres avec du regex...

		Groupage de route :

		Router::group($array, function(){
	
			$array est un tableau associatif prenant plein de paramètres facultatifs, mais les plus importants sont :
				middleware : Permet de rattacher un middleware au groupe de routes (exemple si vous voulez que les routes soient accessible que si l'utilisateur est connecté, vous pouvez créer un middleware pour cela)
				prefix : le prefix est le chemin "parent" auquel se refèreront toutes les routes du groupe... exemple : si vous avez un préfixe "/users/" et une route '/create' alors l'url visitée sera /users/create
	*/

	/* Home Page */
	Router::get('/', 'HomeController@view');

	/* Posts */
	Router::group(['prefix' => '/posts'], function() {
		Router::get('/{id?}', 'PostsController@view')->where(['id' => '[0-9]+']);
		Router::match(['get', 'post'], '/create', 'PostsController@create');
		Router::match(['get', 'patch'], '/edit/{id?}', 'PostsController@edit')->where(['id' => '[0-9]+']);
		Router::delete('/delete/{id}', 'PostsController@delete')->where(['id' => '[0-9]+']);
	});

?>


