<?php
/**
* Validator Class : On valide Quoi que ce soit!
*/
class Validator
{

	private $ok;
	private $message;

	/**
	 * Initialization of the function
	 */
	public function __construct()
	{
		$this->ok = true;
		$this->message = [];
	}

	/**
	 * Look if var is empty
	 * @param  [string|array|integer]  $qqch
	 * @param  [string]  $name hydrate the message
	 */
	public function isEmpty($qqch, $name = '')
	{
		if (is_string($qqch) || is_int($qqch))
		{
			$this->ok = (strlen($qqch) > 0) ? true : false;
		} else
		{
			$this->ok = (count($qqch) > 0) ? true : false;
		}
		if ($this->ok === false)
		{
			array_push($this->message, $name . ' doit être rempli');
		}
	}

	/**
	 * Said if the validation is ok or send array of all the error messages
	 * @return [bool | array]
	 */
	public function validate()
	{
		return ($this->ok === false) ? $this->message : $this->ok;
	}

}