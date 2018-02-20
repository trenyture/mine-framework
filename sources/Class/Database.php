<?php
	/**
	* Database Class make the connection with DataBase
	*/
	class Database
	{
		private static $instance = null;
		private $_host;
		private $_bdd;
		private $_user;
		private $_pass;

		private function __construct()
		{
			$this->_hydrate(json_decode(file_get_contents(__DIR__.'/../configDB.json'), true));
		}

		private function _hydrate($datas)
		{
			$this->_host = $datas['host'];
			$this->_bdd = $datas['bdd'];
			$this->_user = $datas['user'];
			$this->_pass = $datas['pass'];
			$this->_connect();
		}

		private function _connect()
		{
			try {
				return new PDO('mysql:host='.$this->_host.';dbname='.$this->_bdd.';charset=utf8', $this->_user, $this->_pass);
			} catch (PDOException $e) {
				print "Error!: " . $e->getMessage() . "<br/>";
				die();
			}
		}

		public static function getInstance()
		{
			if (is_null(self::$instance)) {
				self::$instance = new Database();
				self::$instance = self::$instance->_connect();
			}
			return self::$instance;
		}

	}