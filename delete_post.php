<?

require_once 'core/init.php';

$user = new User();

require_once 'core/checkLogin.php';

$blogger = $user->blogger();

if(!isset($_GET['id'])){
	Redirect::to('dashboard.php');
}else{
	if(empty(Input::get('id'))){
		Redirect::to('dashboard.php');
	}else{
		$checkPost = $blogger->checkPost(Input::get('id'));

		if($checkPost){
			try{
				$blogger->deletePost(Input::get('id'));
				Session::flash('home', 'Your post has successfully been deleted!');
				Redirect::to('dashboard.php');
			}catch(Exception $e){
				die($e->getMessage());
			}
		}else{
			Redirect::to('dashboard.php');
		}
	}
}