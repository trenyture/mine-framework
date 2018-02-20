<?php

/**
* Users Controller
*/
class UsersController extends Controller
{

	public function view($id = null){

		$UsersManager = new UsersManager();
		
		if (is_null($id)) {
			$users = $UsersManager->get();
			$params = [
				'datas'=> [
					'pageTitle' => 'Tous les Utilisateurs',
					'users' => $users
				],
			];
			$this->render('Users/index.html', $params);
		} else {
			$id = intval($id);
			$user = $UsersManager->get($id);
			$params = [
				'datas'=> [
					'users' => $user
				],
			];
			$this->render('Users/user.html', $params);
		}
	}
	
	public function register(){
		switch (strtoupper($this->request->getMethod())) {
			case 'GET':
				$params = [
					'datas'=> [
						'pageTitle' => 'Enregistrer un nouvel utilisateur'
					],
				];
				$this->render('Users/edit.html', $params);
				break;
			case 'POST':
				$validator = new Validator();
				$validator->isEmpty($_POST['prenom'], 'Le prénom');
				$validator->isEmpty($_POST['nom'], 'Le nom');
				$validator->isEmail($_POST['email'], "L'email");
				$validator->isPassword($_POST['mot-passe'], $_POST['mot-passe-verif']);
				if ($validator->validate() === true){
					$user = new User();
					$UsersManager = new UsersManager();
					$user->setPrenomUser($_POST['prenom']);
					$user->setNomUser($_POST['nom']);
					$user->setEmailUser($_POST['email']);
					$user->setMotDePasseUser(password_hash($_POST['mot-passe'], PASSWORD_DEFAULT));
					echo json_encode($UsersManager->add($user));
				} else
				{
					echo json_encode($validator->validate());
				}			
				break;
		}
	}

	public function edit($id = null){
		switch (strtoupper($this->request->getMethod())) {
			case 'GET':
				$UsersManager = new UsersManager();
				$user = $UsersManager->get(intval($id));
				$params = [
					'datas'=> [
						'pageTitle' => 'Modifier '.$user->getPrenomUser().' '.$user->getNomUser(),
						'user'=> $user
					],
				];
				$this->render('Users/edit.html', $params);
				break;

			case 'PATCH':
				parse_str(file_get_contents('php://input'), $_PATCH);
				$validator = new Validator();
				$validator->isEmpty($_PATCH['prenom'], 'Le prénom');
				$validator->isEmpty($_PATCH['nom'], 'Le nom');
				$validator->isEmail($_PATCH['email'], "L'email");
				if(strlen($_PATCH['mot-passe']) > 0){
					$validator->isPassword($_PATCH['mot-passe'], $_PATCH['mot-passe-verif']);
				}
				if ($validator->validate() === true){
					$user = new User();
					$UsersManager = new UsersManager();
					$user->setPrenomUser($_PATCH['prenom']);
					$user->setNomUser($_PATCH['nom']);
					$user->setEmailUser($_PATCH['email']);
					if(strlen($_PATCH['mot-passe']) > 0){
						$user->setMotDePasseUser(password_hash($_PATCH['mot-passe'], PASSWORD_DEFAULT));
					}
					echo json_encode($UsersManager->update($user));
				} else
				{
					echo json_encode($validator->validate());
				}
				break;
		}
	}

	public function delete(integer $id){
		$UsersManager = new UsersManager();
		$user = $UsersManager->get($id);
		echo json_encode($UsersManagers->delete($user));
	}
}