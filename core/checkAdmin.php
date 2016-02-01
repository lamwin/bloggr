<?php

if(!$user->hasPermission('admin')){
	Redirect::to('dashboard.php');
}