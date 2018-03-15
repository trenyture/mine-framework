<?php

	/* On affiche les erreurs PHP pour l'environnement de dev */
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	/* On initialise les variables de session */
	session_start();

	/*ON CHARGE NOTRE CONFIGURATION*/
	try {
		$config = json_decode(file_get_contents(dirname(dirname(__FILE__)).'/configs/configDB.json'), true);
		define('DB_HOST', $config['host']);
		define('DB_NAME', $config['bdd']);
		define('DB_USER', $config['user']);
		define('DB_PWD', $config['pass']);
		define('ENV', $config['env']);
	} catch (Exception $e) {
		var_dump($e);
		die();
	}

	/* On inclut l'autoloader de nos dépendances, classes et controller */
	require_once __DIR__.'/../autoloader.php';

?>
