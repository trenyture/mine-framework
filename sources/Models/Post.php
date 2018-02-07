<?php
	/**
	* POST
	*/
	class Post extends Model
	{

		protected $id;
		protected $title;
		protected $content;
		protected $author;
		protected $dateCreation;

		/* GETTERS */
		public function getId()
		{
			return $this->id;
		}

		public function getTitle()
		{
			return $this->title;
		}

		public function getContent()
		{
			return htmlspecialchars_decode($this->content);
		}

		public function getAuthor()
		{
			return ($this->author === null) ? 0 : $this->author;
		}

		public function getDateCreation()
		{
			return $this->dateCreation;
		}

		/* SETTERS */
		public function setId($id)
		{
			$this->id = $id;
		}

		public function setTitle($title)
		{
			$this->title = $title;
		}

		public function setContent($content)
		{
			$this->content = $content;
		}

		public function setAuthor($author)
		{
			$this->author = $author;
		}

		public function setDateCreation($date)
		{
			$this->dateCreation = $date;
		}

	}