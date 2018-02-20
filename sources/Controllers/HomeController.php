<?php

	/**
	* HOME CONTROLLER
	*/
	class HomeController extends Controller{

		public function view()
		{
			$params = [
				'datas'=> [
					'pageTitle' => 'Home',
					'name'=>'Jennifer Legrand'
				],
			];
			return $this->render('home.html', $params);
		}

	}