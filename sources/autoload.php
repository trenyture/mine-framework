<?php

	use Pecee\SimpleRouter\SimpleRouter;

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

	/* Load external routes file */
	require_once __DIR__.'/routes.php';

	// Start the routing
	SimpleRouter::start();