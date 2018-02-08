<?php

	spl_autoload_register(
		function ($class) {
			if (file_exists(__DIR__.'/Class/' . $class . '.php')) {
				include __DIR__.'/Class/' . $class . '.php';
			} elseif (file_exists(__DIR__.'/Controllers/' . $class . '.php')) {
				include __DIR__.'/Controllers/' . $class . '.php';
			} elseif (file_exists(__DIR__.'/Models/' . $class . '.php')) {
				include __DIR__.'/Models/' . $class . '.php';
			} elseif (file_exists(__DIR__.'/Managers/' . $class . '.php')) {
				include __DIR__.'/Managers/' . $class . '.php';
			}
		}
	);

	$klein = new \Klein\Klein();

	/* On charge les dépendances dans tout le router */
	$klein->respond(function ($req, $res, $serv, $app) {
		$app->register('twig', function () {
			/* IF DEVELOPMENT */
			$twig = new Twig_Environment(
						new Twig_Loader_Filesystem(__DIR__.'/Views'), 
						array(
							'debug' => true,
							'cache' => false
						)
					);
			$twig->addExtension(new Twig_Extension_Debug());
			return $twig;
			/* IF PRODUCTION */
			// return new Twig_Environment(new Twig_Loader_Filesystem(__DIR__.'/Views'));;
		});
	});

	/* On inclut le router */
	require_once __DIR__.'/routes.php';

	/* On gère les codes d'erreurs */
	$klein->onHttpError(function ($code, $router) {
		switch ($code) {
			case 404:
				echo "Oops, it looks like the page you're looking for doesn't exist..\n";
				break;
			case 405:
				$router->res()->body('You can\'t do that!');
				break;
			default:
				$router->res()->body('Oh no, a bad error happened that caused a '. $code);
				break;
		}
	});

	$klein->dispatch();