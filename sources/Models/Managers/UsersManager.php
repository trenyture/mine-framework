<?php

/**
* UsersManager
*/
class UsersManager extends Manager
{
	
	public function get(int $idUser = null){
		if (is_null($idUser)) {
			$users = [];
			$q = $this->db->query('SELECT id_user, prenom_user, nom_user, email_user, mot_de_passe_user, date_creation_user FROM users');
			while ($datas = $q->fetch(PDO::FETCH_ASSOC)){
				$users[] = new User($datas);
			}
			return $users;
		} else {
			$q = $this->db->query('SELECT id_user, prenom_user, nom_user, email_user, mot_de_passe_user, date_creation_user FROM users WHERE id_user = '.$idUser);
			$datas = $q->fetch(PDO::FETCH_ASSOC);
			return new User($datas);
		}
	}

	public function add(User $user){
		$q = $this->db->prepare('INSERT INTO users(id_user, prenom_user, nom_user, email_user, mot_de_passe_user, date_creation_user) VALUES(:idUser, :prenomUser, :nomUser, :emailUser, :motDePasseUser, :dateCreationUser)');
		$q->bindValue(':idUser', $user->getIdUser());
		$q->bindValue(':prenomUser', $user->getPrenomUser());
		$q->bindValue(':nomUser', $user->getNomUser());
		$q->bindValue(':emailUser', $user->getEmailUser());
		$q->bindValue(':motDePasseUser', $user->getMotDePasseUser());
		$q->bindValue(':dateCreationUser', $user->getDateCreationUser());
		return $q->execute();
	}

	public function update(User $user){
		var_dump($user);
		die();
		$q = $this->db->prepare('UPDATE users SET prenom_user = :prenomUser, nom_user = :nomUser, email_user = :emailUser, mot_de_passe_user = :motDePasseUser WHERE id_user = :idUser');
		$q->bindValue(':prenomUser', $user->getPrenomUser());
		$q->bindValue(':nomUser', $user->getNomUser());
		$q->bindValue(':emailUser', $user->getEmailUser());
		$q->bindValue(':motDePasseUser', $user->getMotDePasseUser());
		$q->bindValue(':idUser', $user->getIdUser());
		return $q->execute();
	}

	public function delete(User $user)
	{
		$q = $this->db->prepare('DELETE FROM users WHERE id_user = :idUser');
		$q->bindValue(':idUser', $user->getIdUser());
		return $q->execute();
	}
}