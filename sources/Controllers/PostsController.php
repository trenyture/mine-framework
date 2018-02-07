<?php

	/**
	* PostsController
	*/
	class PostsController
	{

		public static function view($req, $res, $serv, $app)
		{

			$PostsManager = new PostsManager();

			if (is_null($req->id)) {
				$posts = $PostsManager->get();
				$params = [
					'datas'=> [
						'pageTitle' => 'Tous les Posts',
						'posts'=> $posts
					],
				];
				echo $app->twig->render('Posts/index.html', $params);
			} else {
				$post = $PostsManager->get($req->id);
				$params = [
					'datas'=> [
						'pageTitle' => 'Post n°'.$post->getId().' : '.$post->getTitle(),
						'post'=> $post
					],
				];
				echo $app->twig->render('Posts/post.html', $params);
			}

		}

		public static function create($req, $res, $serv, $app)
		{
			switch ($req->method()) {
				case 'GET':
					$params = [
						'datas'=> [
							'pageTitle' => 'Créer un nouveau Post'
						],
					];
					echo $app->twig->render('Posts/create.html', $params);
					break;

				case 'POST':
					$datas = $req->params();
					$validator = new Validator();
					$validator->isEmpty($datas['titleField'], 'Le Titre');
					$validator->isEmpty($datas['contentField'], 'Le Contenu');
					if ($validator->validate() === true)
					{
						$post = new Post();
						$PostsManager = new PostsManager();
						//on sauvegarde
						$post->setTitle(htmlspecialchars($datas['titleField']));
						$post->setContent(htmlspecialchars($datas['contentField']));
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

		public static function edit($req, $res, $serv, $app)
		{
			switch ($req->method()) {
				case 'GET':
					$PostsManager = new PostsManager();
					$post = $PostsManager->get($req->id);
					$params = [
						'datas'=> [
							'pageTitle' => 'Post n°'.$post->getId().' : '.$post->getTitle(),
							'post'=> $post
						],
					];
					echo $app->twig->render('Posts/edit.html', $params);
					break;

				case 'PATCH':
					$datas = $req->params();
					var_dump($datas);
					die();
					parse_str(file_get_contents('php://input'), $_PATCH);
					$validator = new Validator();
					$validator->isEmpty($_PATCH['titleField'], 'Le Titre');
					$validator->isEmpty($_PATCH['contentField'], 'Le Contenu');
					if ($validator->validate() === true)
					{
						$PostsManager = new PostsManager();
						$post = $PostsManager->get($req->id);
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

		public static function delete($req, $res, $serv, $app)
		{
			if($req->method() === 'DELETE')
			{
				$PostsManager = new PostsManager();
				$post = $PostsManager->get($req->id);
				echo json_encode($PostsManager->delete($post));
			}
			else
			{
				header('Location:/posts');
				die();
			}
		}

	}