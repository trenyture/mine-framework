<?php

/**
* Users Model
*/
class User extends Model{
	
	protected $idUser;
	protected $prenomUser;
	protected $nomUser;
	protected $emailUser;
	protected $motDePasseUser;
	protected $dateCreationUser;

	////////////
	//GETTERS //
	////////////
	public function getIdUser(){
		return $this->idUser;
	}

	public function getPrenomUser(){
		return $this->prenomUser;
	}

	public function getNomUser(){
		return $this->nomUser;
	}

	public function getEmailUser(){
		return $this->emailUser;
	}

	public function getMotDePasseUser(){
		return $this->motDePasseUser;
	}

	public function getDateCreationUser(){
		return $this->dateCreationUser;
	}

	////////////
	//SETTERS //
	////////////
	public function setIdUser($idUser){
		$this->idUser = $idUser;
	}

	public function setPrenomUser($prenomUser){
		$this->prenomUser = $prenomUser;
	}

	public function setNomUser($nomUser){
		$this->nomUser = $nomUser;
	}

	public function setEmailUser($emailUser){
		$this->emailUser = $emailUser;
	}

	public function setMotDePasseUser($motDePasseUser){
		$this->motDePasseUser = $motDePasseUser;
	}

	public function setDateCreationUser($dateCreationUser){
		$this->dateCreationUser = $dateCreationUser;
	}


}