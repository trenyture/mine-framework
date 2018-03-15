<?php
/**
* Validator Class : On valide Quoi que ce soit!
*/
class Validator
{

	private $response;

	/**
	 * Initialization of the function
	 */
	public function __construct()
	{
		$this->response = [];
	}

	/**
	 * Look if var is empty
	 * @param  [string|array|integer]  $qqch
	 * @param  [string]  $name hydrate the response
	 */
	public function isEmpty($qqch, $name = '')
	{
		if (is_string($qqch) || is_int($qqch)){
			if(strlen($qqch) < 1){
				array_push($this->response, $name . ' doit être rempli');
			}
		} else if (count($qqch) < 1) {
			array_push($this->response, $name . ' doit être rempli');
		}
	}

	/**
	 * Look if value is email
	 * @param  [string]  $qqch
	 * @param  [string]  $name hydrate the response
	 */
	public function isEmail(string $qqch, $name = '')
	{
		if (strlen($qqch) < 1) {	
			array_push($this->response, $name . ' doit être rempli');
		} else if (!filter_var($qqch, FILTER_VALIDATE_EMAIL)) {
			array_push($this->response, $name . ' doit être rempli');
		}
	}

	/**
	 * Look strength password and if passwords are equals
	 * @param  [string]  $pass
	 * @param  [string | null]  $pass2
	 */
	public function isPassword(string $password, string $passwordVerify = null)
	{
		if (strlen($password) < 1) {
			array_push($this->response, 'Le mot de passe doit être renseigné');
		} else if ($password !== $passwordVerify) {
			array_push($this->response, 'Les mots de passe ne correspondent pas');
		}
	}

	/**
	 * Said if the validation is ok or send array of all the error response
	 * @return [bool | array]
	 */
	public function validate()
	{
		return (count($this->response) === 0) ? true : $this->response;
	}

}