<?php

require_once 'core/init.php';

$user = new User();

require_once 'core/checkLogin.php';
require_once 'core/checkAdmin.php';

$admin = $user->admin();

if(Input::exists('get')){

	$id = Input::get('id');
	$mode = Input::get('mode');

	if($mode == 'deactivate'){
		$admin->deactivateUser($id);
		Session::flash('admin', 'User has been deactivated!');
		Redirect::to('adminPanel.php');
	}else if($mode == 'activate'){
		$admin->activateUser($id);
		Session::flash('admin', 'User has been activated!');
		Redirect::to('adminPanel.php');
	}

}else{
	Redirect::to('adminPanel.php');
}