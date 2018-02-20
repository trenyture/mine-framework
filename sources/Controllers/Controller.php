<?php

use Pecee\Http\Request;

/**
* Controller Class
*/
class Controller{

	private $twig;
	public $request;

	function __construct(){
		if (PRODUCTION === true) {
			$this->twig = new Twig_Environment(new Twig_Loader_Filesystem(dirname(__DIR__).'/Views'));;
		} else {
			$this->twig = new Twig_Environment(
						new Twig_Loader_Filesystem(dirname(__DIR__).'/Views'), 
						array(
							'debug' => true,
							'cache' => false
						)
					);
			$this->twig->addExtension(new Twig_Extension_Debug());
		}
		$this->request = new Request();
	}

	public function render(String $view, Array $datas = NULL){
			echo (is_null($datas)) ? $this->twig->render($view) : $this->twig->render($view, $datas);
	}
}