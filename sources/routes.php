<?php
	/* Documentation : https://github.com/klein/klein.php

		$klein->respond(verb, route, callback);
			verb 	 : peut être un string ou un array de GET, POST, PUT, DELETE
			route 	 : le chemin avec les possibles paramètres envoyés au controller (voir ci-après)
			callback : Une function d'un controller appélé dans un tableau : [new Controller, function du controller]
						Cette fonction du controlleur prendra les variables suivantes :
						-> $req	 : pour les paramètres de la requete
						-> $res : pour éditer la vue en fonction de la requete
						-> $serv  : si on souhaite connecter un serv a notre requete
						-> $app 	 : pour le contexte, cette variable contient toutes les dépendances comme par exemple Twig

			Paramètres :
				*                    // Match all req URIs
				[i]                  // Match an integer
				[i:id]               // Match an integer as 'id'
				[a:action]           // Match alphanumeric characters as 'action'
				[h:key]              // Match hexadecimal characters as 'key'
				[:action]            // Match anything up to the next / or end of the URI as 'action'
				[create|edit:action] // Match either 'create' or 'edit' as 'action'
				[*]                  // Catch all (lazy)
				[*:trailing]         // Catch all as 'trailing' (lazy)
				[**:trailing]        // Catch all (possessive - will match the rest of the URI)
				.[:format]?          // Match an optional parameter 'format' - a / or . before the block is also optional

		$klein->with(route, callback);
			route 	 : la route générale de toutes les sous route
			callback : la function executée qui définira les sous route. Attention de bien veiller à l'utiliser comme suit : function() use($klein){}

			Cette fonction nous permet de diviser en grandes familles notre routage. Par exemple, plutôt que de faire :
				$klein->respond('GET', '/posts/[i:id]?', function($req) {PostsController::view($req->id);});
				$klein->respond(['GET', 'POST'], '/posts/create', function() {PostsController::create();});
			On peut regrouper ces deux routes dans une route principale /posts

		Vous pouvez définir plusieurs route avec un meme nom de paramètre grâce a [method1|method2:method] mais si vous traiter différement ces routes suivant la méthode vous devrez réaliser un SWITCH dans la function de callback pour appeller plus tard
	*/

	/* HOME PAGE */
	$klein->respond('/', ['HomeController', 'view']);

	/* POSTS PAGES */
	$klein->with('/posts', function() use ($klein) {
		$klein->respond('GET', '/[i:id]?', ['PostsController', 'view']);
		$klein->respond(['GET', 'POST'], '/create', ['PostsController', 'create']);
		$klein->respond(['GET', 'PATCH'], '/edit/[i:id]?', ['PostsController', 'edit']);
		$klein->respond('DELETE', '/delete/[i:id]', ['PostsController', 'delete']);
	});

?>


