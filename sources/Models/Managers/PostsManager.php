<?php
	
/**
* Posts Manager
*/

class PostsManager extends Manager{

	public function add(Post $post)
	{
		$q = $this->db->prepare('INSERT INTO posts(title, content, author, date_creation) VALUES(:title, :content, :author, :dateCreation)');
		$q->bindValue(':title', $post->getTitle());
		$q->bindValue(':content', $post->getContent());
		$q->bindValue(':author', $post->getAuthor());
		$q->bindValue(':dateCreation', $post->getDateCreation());
		return $q->execute();
	}

	public function get($id = null)
	{

		if (is_null($id)) {
			$posts = [];
			$q = $this->db->query('SELECT id, title, content, author, date_creation FROM posts ORDER BY date_creation DESC');
			while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
			{
				$posts[] = new Post($donnees);
			}
			return $posts;
		} else {
			$id = (int) $id;
			$q = $this->db->query('SELECT id, title, content, author, date_creation FROM posts WHERE id = '.$id);
			$donnees = $q->fetch(PDO::FETCH_ASSOC);
			return new Post($donnees);
		}
	}

	public function update(Post $post)
	{
		$q = $this->db->prepare('UPDATE posts SET title = :title, content = :content, author = :author, date_creation = :dateCreation WHERE id = :id');
		$q->bindValue(':title', $post->getTitle());
		$q->bindValue(':content', $post->getContent());
		$q->bindValue(':author', $post->getAuthor());
		$q->bindValue(':dateCreation', $post->getDateCreation());
		$q->bindValue(':id', $post->getId());
		return $q->execute();
	}

	public function delete(Post $post)
	{
		$q = $this->db->prepare('DELETE FROM posts WHERE id = :id');
		$q->bindValue(':id', $post->getId());
		return $q->execute();
	}

}