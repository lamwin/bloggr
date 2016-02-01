<?php

class Reset
{

	private $_db,
			$_data,
			$_errors = array(),
			$_key,
			$_value,
			$_password,
			$_password_token;

	public function __construct($params = array()){
		$this->_key = array_keys($params)[0];
		$this->_value = $params[$this->_key];

		$this->_db = DB::getInstance();
	}

	public function fetchData(){
		$this->_db->get('users', array($this->_key, '=', $this->_value));

		if($this->_db->count()){
			$this->_data = $this->_db->first();
			return $this;
		}

		$this->addError('There was a problem recovering your data!');

	}

	public function resetPassword($password, $salt){
		$this->_password = Hash::make($password, $salt);
		$this->_password_token = md5(uniqid($this->_data->username, true));

		$this->_db->update('users', $this->_data->id, array(
			'password' => $this->_password,
			'salt' => $salt,
			'password_token' => $this->_password_token
		));
	}

	public function data(){
		return $this->_data;
	}

	public function addError($error){
		$this->_errors[] = $error;
	}

	public function errors(){
		return $this->_errors;
	}

	public function passed(){
		if(empty($this->_errors)){
			return true;
		}
		return false;
	}

}