<?php

// use Pecee\SimpleRouter\SimpleRouter;
use Pecee\Http\Request;

/**
* PostsController
*/
class PostsController extends Controller{

	public function view($id = null)
	{
		$PostsManager = new PostsManager();
		if (is_null($id)) {
			$posts = $PostsManager->get();
			$params = [
				'datas'=> [
					'pageTitle' => 'Tous les Posts',
					'posts'=> $posts
				],
			];
			$this->render('Posts/index.html', $params);
		} else {
			$post = $PostsManager->get($id);
			$params = [
				'datas'=> [
					'pageTitle' => 'Post n°'.$post->getId().' : '.$post->getTitle(),
					'post'=> $post
				],
			];
			$this->render('Posts/post.html', $params);
		}

	}

	public function create()
	{

		switch (strtoupper($this->request->getMethod())) {
			case 'GET':
				$params = [
					'datas'=> [
						'pageTitle' => 'Créer un nouveau Post'
					],
				];
				$this->render('Posts/create.html', $params);
				break;

			case 'POST':
				$validator = new Validator();
				$validator->isEmpty($_POST['titleField'], 'Le Titre');
				$validator->isEmpty($_POST['contentField'], 'Le Contenu');
				if ($validator->validate() === true)
				{
					$post = new Post();
					$PostsManager = new PostsManager();
					//on sauvegarde
					$post->setTitle(htmlspecialchars($_POST['titleField']));
					$post->setContent(htmlspecialchars($_POST['contentField']));
					// var_dump($post);
					// die();
					echo json_encode($PostsManager->add($post));
				} else
				{
					echo json_encode($validator->validate());
				}
				break;

			default:
				header('Location:/posts');
				die();
				break;
		}
	}

	public function edit($id)
	{
		switch (strtoupper($this->request->getMethod())) {
			case 'GET':
				$PostsManager = new PostsManager();
				$post = $PostsManager->get($id);
				$params = [
					'datas'=> [
						'pageTitle' => 'Post n°'.$post->getId().' : '.$post->getTitle(),
						'post'=> $post
					],
				];
				$this->render('Posts/edit.html', $params);
				break;

			case 'PATCH':
				parse_str(file_get_contents('php://input'), $_PATCH);
				$validator = new Validator();
				$validator->isEmpty($_PATCH['titleField'], 'Le Titre');
				$validator->isEmpty($_PATCH['contentField'], 'Le Contenu');
				if ($validator->validate() === true)
				{
					$PostsManager = new PostsManager();
					$post = $PostsManager->get($id);
					$post->setTitle(htmlspecialchars($_PATCH['titleField']));
					$post->setContent(htmlspecialchars($_PATCH['contentField']));
					echo json_encode($PostsManager->update($post));
				} else
				{
					echo json_encode($validator->validate());
				}
				break;

			default:
				header('Location:/posts');
				die();
				break;
		}
	}

	public function delete($id)
	{
		if(strtoupper($this->request->getMethod()) === 'DELETE')
		{
			$PostsManager = new PostsManager();
			$post = $PostsManager->get($id);
			echo json_encode($PostsManager->delete($post));
		}
	}

}