<?php

class Admin extends User
{
	public function allUsers(){
		$this->_db->query('SELECT * FROM `users` WHERE `group` = 1');
		return $this->_db->results();
	}

	public function activateUser($id){
		$this->_db->update('users', $id, array(
			'active' => 1
		));
	}

	public function deactivateUser($id){
		$this->_db->update('users', $id, array(
			'active' => 0
		));
	}
}