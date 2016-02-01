<?php

	require_once 'core/init.php';

	$user = new User();
	
	require_once 'core/checkLogin.php';

	$user->logout();

	Session::flash('login', 'You have successfully logged out!');
	Redirect::to('login.php');