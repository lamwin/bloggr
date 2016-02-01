<?php

class PublicInfo
{

	protected 	$_db,
				$_dataCategory,
				$_countPost,
				$_dataUser;

	public function __construct(){
		$this->_db = DB::getInstance();
	}

	public function getCategory($id = null){
		if(!$id){
			$data = $this->_db->query('SELECT `categories`.`id` AS `cat_id`, `categories`.`name` AS `cat_name`,
										COUNT(`post`.`cat_id`) AS `count_post`
										 FROM `categories` 
										 LEFT JOIN `post` ON `categories`.`id` = `post`.`cat_id`
										 GROUP BY `categories`.`id`
										 ORDER BY `count_post` DESC, `cat_name` ASC');
			if($data->count()){
				$this->_dataCategory = $data->results();
			}
		}else{
			$data = $this->_db->query('SELECT * From `categories` WHERE `id` = ? ORDER BY `name` ASC', array($id));
			if($data->count()){
				$this->_dataCategory = $data->results();
			}
		}
	}

	public function dataCategory(){
		return $this->_dataCategory;
	}

	public function getPost($cat_id = null){
		if(!$cat_id){
			$data = $this->_db->query('SELECT `post`.`id` AS `id`, `categories`.`id` AS `cat_id`, `categories`.`name` AS `cat_name`,
										`users`.`username` AS `username`, `users`.`firstname` AS `user_firstname`, `users`.`lastname` AS `user_lastname`,
										`users`.`email` AS `user_email`, `users`.`location` AS `user_location`, `users`.`description` AS `user_description`,
										`users`.`image` AS `user_image`, `users`.`joined` AS `date_joined`,
										`title`, `contents`, `date_posted`
										 FROM `post`
										 INNER JOIN `categories` ON `categories`.`id` = `post`.`cat_id` 
										 INNER JOIN `users` ON `users`.`id` = `post`.`user_id`
										 ORDER BY `date_posted` DESC');
			if($data->count()){
				return $data->results();
			}
		}else{
			$data = $this->_db->query('SELECT `post`.`id` AS `id`, `categories`.`id` AS `cat_id`, `categories`.`name` AS `cat_name`,
										`users`.`username` AS `username`, `users`.`firstname` AS `user_firstname`, `users`.`lastname` AS `user_lastname`,
										`users`.`email` AS `user_email`, `users`.`location` AS `user_location`, `users`.`description` AS `user_description`,
										`users`.`image` AS `user_image`, `users`.`joined` AS `date_joined`,
										`title`, `contents`, `date_posted`
										 FROM `post`
										 INNER JOIN `categories` ON `categories`.`id` = `post`.`cat_id` 
										 INNER JOIN `users` ON `users`.`id` = `post`.`user_id` 
										 WHERE `categories`.`id` = ?
										 ORDER BY `date_posted` DESC', array($cat_id));
			if($data->count()){
				return $data->results();
			}
		}
	}

	public function checkPost($id){
		$data = $this->_db->query('SELECT * FROM `post` WHERE `id` = ?', array($id));
		if($data->count()){
			return true;
		}
		return false;
	}

	public function getSpecificPost($id){
		$data = $this->_db->query('SELECT `post`.`id` AS `id`, `categories`.`id` AS `cat_id`, `categories`.`name` AS `cat_name`,
										`users`.`username` AS `username`, `users`.`firstname` AS `user_firstname`, `users`.`lastname` AS `user_lastname`,
										`users`.`email` AS `user_email`, `users`.`location` AS `user_location`, `users`.`description` AS `user_description`,
										`users`.`image` AS `user_image`, `users`.`joined` AS `date_joined`,
										`title`, `contents`, `date_posted`
										 FROM `post`
										 INNER JOIN `categories` ON `categories`.`id` = `post`.`cat_id` 
										 INNER JOIN `users` ON `users`.`id` = `post`.`user_id` 
										 WHERE `post`.`id` = ?', array($id));
		if($data->count()){
			return $data->first();
		}
	}

	public function getUserProfile($username){
		$data = $this->_db->query('SELECT `post`.`id` AS `id`, `categories`.`id` AS `cat_id`, `categories`.`name` AS `cat_name`,
										`users`.`username` AS `username`, `users`.`firstname` AS `user_firstname`, `users`.`lastname` AS `user_lastname`,
										`users`.`email` AS `user_email`, `users`.`location` AS `user_location`, `users`.`description` AS `user_description`,
										`users`.`image` AS `user_image`, `users`.`joined` AS `date_joined`,
										`title`, `contents`, `date_posted`
										 FROM `post`
										 INNER JOIN `categories` ON `categories`.`id` = `post`.`cat_id` 
										 INNER JOIN `users` ON `users`.`id` = `post`.`user_id`
										 WHERE `username` = ?
										 ORDER BY `date_posted` DESC', array($username));
		if($data->count()){
			$this->_countPost = $data->count();
			return $data->results();
		}
	}

	public function getCountPost(){
		return $this->_countPost;
	}

	public function findUser($user = null){
		if($user){
			$field = (is_numeric($user)) ? 'id' : 'username';
			$data = $this->_db->get('users', array($field, '=', $user));

			if($data->count()){
				$this->_dataUser = $data->first();
				return true;
			}
		}
		return false;
	}

	public function dataUser(){
		return $this->_dataUser;
	}
}





