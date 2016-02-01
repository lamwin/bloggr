<?php

if(!$user->isLoggedIn()){
	Redirect::to('login.php');
}

if(!$user->isActive()){
	Session::delete(Config::get('session/session_name'));
	Cookie::delete(Config::get('remember/cookie_name'));
	Session::flash('alert', 'Your account is not activated!');
	Redirect::to('login.php');
}