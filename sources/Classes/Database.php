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
			$this->_hydrate();
		}

		private function _hydrate()
		{
			$this->_host = DB_HOST;
			$this->_bdd = DB_NAME;
			$this->_user = DB_USER;
			$this->_pass = DB_PWD;
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
				$dumb = new Database();
				self::$instance = $dumb->_connect();
			}
			return self::$instance;
		}

	}
