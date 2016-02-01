<?php

class Recover
{

	private $_mode_allowed = array('username', 'password'),
			$_mode,
			$_errors = array(),
			$_db,
			$_data,
			$_password_token;

	public function __construct(){
		$this->_mode = Input::get('mode');
		$this->_db = DB::getInstance();
	}

	public function checkMode(){
		if(isset($_GET['mode']) && in_array($this->_mode, $this->_mode_allowed)){
			return true;
		}

		return false;
	}

	public function checkEmail($email){
		$email = escape($email);
		$data = $this->_db->get('users', array('email', '=', $email));

		if($data->count()){
			return true;
		}

		$this->addError('Sorry, we could not find that email!');
		return false;
	}

	public function recover($mode, $email){
		$email = escape($email);
		$data = $this->_db->get('users', array('email', '=', $email));

		if($data->count()){
			$this->_data = $data->first();
		}

		if($mode === 'username'){
			$this->recoverUsername($email);
		}else if($mode === 'password'){
			$this->recoverPassword($email);
		}	

		return true;

	}

	private function recoverUsername($email){
		email($email, 'Recovering Your Username', "
						<p>Hello ".$this->_data->firstname.",</p><br />
						<p>Your username is:</p><br />

						<p><strong>".$this->_data->username."</strong></p><br>

						--Bloggr
						");
	}

	private function recoverPassword($email){
		$this->_password_token = md5(uniqid($this->_data->username, true));
		$id = $this->_data->id;

		$this->_db->update('users', $id, array(
			'password_token' => $this->_password_token
		));

		email($email, 'Recovering Your Password', "
						<p>Hello ".$this->_data->firstname.",</p><br />
						<p>Click on the link below to reset your password:</p><br />

						<p><a href='http://localhost:8888/reset_password.php?email=".$email."&password_token=".$this->_password_token."'>http://localhost:8888/reset_password.php?email=".$email."&password_token=".$this->_password_token."</a></p><br>

						--Bloggr
						");
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