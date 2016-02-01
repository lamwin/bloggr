<?php

require_once 'core/init.php';

$user = new User();

require_once 'core/checkLogin.php';

$blogger = $user->blogger();

if(!Input::exists('get')){
	Redirect::to('category.php');
}else if(!isset($_GET['id']) || empty($_GET['id'])){
	Redirect::to('category.php');
}else{

	$checkCategory = $blogger->checkCategory(Input::get('id'));

	if($checkCategory){
		try{
			$blogger->changeCategory(Input::get('id'));
			$blogger->deleteCategory(Input::get('id'));
			Session::flash('category', 'Category has been successfully deleted!');
			Redirect::to('category.php');
		}catch(Exception $e){
			die($e->getMessage());
		}
	}else{
		Redirect::to('category.php');
	}

}