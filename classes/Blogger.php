<?php

class Blogger extends User
{

	protected $_category,
			  $_perPage = 6,
			  $_start,
			  $_count;

	/*public function createCategory($category){
		$this->_category = $category;

		if(!$this->_db->insert('category', array(
				'category_name' => $this->_category,
				'user_id' => $this->data()->id
			))){
			throw new Exception('There was a problem creating the category!');
		}
	}*/

	/*public function checkCategory($id){
		$data = $this->_db->query('SELECT * From `categories` WHERE `id` = ? AND `user_id` = ?', array($id, $this->data()->id));
		if($data->count()){
			return true;
		}
		return false;
	}

	public function deleteCategory($id){
		if(!$this->_db->delete('category', array('id', '=', $id))){
			throw new Exception('There was a problem deleting the category!');
		}
	}

	public function changeCategory($cat_id){
		if(!$this->_db->query('UPDATE `post` SET `cat_id` = ? WHERE `cat_id` = ?', array(1, $cat_id))){
			throw new Exception("There was a problem updating the post!");
		}
	}*/

	public function getCategory($id = null, $page = null){

		$this->_start = ($page > 1) ? ($page * $this->_perPage) - $this->_perPage : 0;

		if(!$id){

			$sql = "SELECT `categories`.`id` AS `cat_id`, `categories`.`name` AS `cat_name`,
					COUNT(`post`.`cat_id`) AS `count_post`
					 FROM `categories` 
					 LEFT JOIN `post` ON `categories`.`id` = `post`.`cat_id` AND `post`.`user_id` = ?
					 GROUP BY `categories`.`id`
					 ORDER BY `cat_name` ASC ";

			if($page){
				$sql .= "LIMIT {$this->_start}, {$this->_perPage}";
			}

			$data = $this->_db->query($sql, array($this->data()->id));

			if($data->count()){
				return $data->results();
			}
		}else{
			$data = $this->_db->query('SELECT * From `categories` WHERE `id` = ?', array($id));
			if($data->count()){
				return true;
			}
			return false;
		}
	}

	public function count($table){
		$data = $this->_db->query('SELECT COUNT(`'.$table.'`.`id`) AS `total` FROM `'.$table.'`');

		if($data->count()){
			return $this->_count = $data->first()->total;
		}
	}

	public function createPost($params = array()){
		if(!$this->_db->insert('post', $params)){
			throw new Exception('There was a problem creating the post!');
		}
	}

	public function getPost($cat_id = null){
		if(!$cat_id){
			$data = $this->_db->query('SELECT `post`.`id` AS `id`, `categories`.`id` AS `cat_id`, `categories`.`name` AS `cat_name`,
										`title`, `contents`, `date_posted`
										 FROM `post`
										 INNER JOIN `categories` ON `categories`.`id` = `post`.`cat_id`
										 WHERE `post`.`user_id` = ? 
										 ORDER BY `date_posted` DESC', array($this->data()->id));
			if($data->count()){
				return $data->results();
			}
		}else{
			$data = $this->_db->query('SELECT `post`.`id` AS `id`, `categories`.`id` AS `cat_id`, `categories`.`name` AS `cat_name`,
										`title`, `contents`, `date_posted`
										 FROM `post`
										 INNER JOIN `categories` ON `categories`.`id` = `post`.`cat_id` 
										 WHERE `post`.`user_id` = ? AND `post`.`cat_id` = ? 
										 ORDER BY `date_posted` DESC', array($this->data()->id, $cat_id));
			if($data->count()){
				return $data->results();
			}
		}
	}

	public function checkPost($id){
		$data = $data = $this->_db->query('SELECT `post`.`id` AS `id`, `categories`.`id` AS `cat_id`, `categories`.`name` AS `cat_name`,
										`title`, `contents`, `date_posted`
										 FROM `post`
										 INNER JOIN `categories` ON `categories`.`id` = `post`.`cat_id` 
										 WHERE `post`.`user_id` = ? AND `post`.`id` = ? 
										 ORDER BY `date_posted` DESC', array($this->data()->id, $id));
		if($data->count()){
			return true;
		}

		return false;
	}

	public function getSpecificPost($id){
		$data = $data = $this->_db->query('SELECT `post`.`id` AS `id`, `categories`.`id` AS `cat_id`, `categories`.`name` AS `cat_name`,
										`title`, `contents`, `date_posted`
										 FROM `post`
										 INNER JOIN `categories` ON `categories`.`id` = `post`.`cat_id` 
										 WHERE `post`.`user_id` = ? AND `post`.`id` = ?', array($this->data()->id, $id));
		if($data->count()){
			return $data->first();
		}
	}

	public function updatePost($id, $params = array()){
		if(!$this->_db->update('post', $id, $params)){
			throw new Exception("There was a problem updating the post!");
		}
	}

	public function deletePost($id){
		if(!$this->_db->delete('post', array('id', '=', $id))){
			throw new Exception('There was a problem deleting the post!');
		}
	}

}







