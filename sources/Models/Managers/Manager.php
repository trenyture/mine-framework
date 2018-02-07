<?php
	/**
	* Manager
	*/
	class Manager
	{
		protected $db;
	
		public function __construct()
		{
			$this->db = Database::getInstance();
		}
	}