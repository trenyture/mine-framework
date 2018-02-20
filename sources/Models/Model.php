<?php

	/**
	* Model.php
	*/

	class Model
	{

		public function __construct($datas = null)
		{
			if (!is_null($datas)) {
				$this->hydrate($datas);
			}
		}

		private function hydrate(array $datas)
		{
			foreach ($datas as $key => $value)
			{
				$method = 'set'.implode(array_map("ucfirst", explode("_", $key)));
				if (method_exists($this, $method))
				{
					$this->$method($value);
				}
			}
		}

	}