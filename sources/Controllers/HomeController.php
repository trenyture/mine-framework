<?php

	/**
	* HOME CONTROLLER
	*/
	class HomeController{

		public static function view($req, $res, $serv, $app)
		{
			$params = [
				'datas'=> [
					'pageTitle' => 'Home',
					'name'=>'Jennifer Legrand'
				],
			];
			return $app->twig->render('home.html', $params);
		}

	}