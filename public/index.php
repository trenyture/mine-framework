<?php

	/* On affiche les erreurs PHP pour l'environnement de dev */
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	/* On initialize les variables de Sessions */
	session_start();

	/* On inclut l'autoloader de nos dépendances, classes et controller */
	require_once __DIR__.'/../autoloader.php';

?>