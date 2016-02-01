<?php

	require_once 'core/init.php';

	logged_in_redirect();

	if(Input::exists()){
		if(Token::check(Input::get('token'))){

			$validate = new Validation();
			$validation = $validate->check($_POST, array(
				'username' => array(
					'required' => true
				),
				'password' => array(
					'required' => true
				)
			));

			if($validation->passed()){
				$user = new User();
				$remember = (Input::get('remember') === 'on') ? true : false;

				try{
					$login = $user->login(Input::get('username'), Input::get('password'), $remember);

					if($login){
						Session::flash('home', 'You have successfully logged in!');
						Redirect::to('dashboard.php');
					}else{
						$errors[] = 'Your username or password is incorrect';
					}

				}catch(Exception $e){
					die($e->getMessage());
				}
			}

		}
	}

	include 'includes/header.php';

?>

	<div class="row">
		<div class="large-12 columns">
			<?php
				if(Session::exists('login')){
					flashMessage(Session::flash('login'));
				}
				if(Session::exists('alert')){
					flashAlert(Session::flash('alert'));
				}
			?>
		</div>
	</div>

	<div class="register-card">
		<h3>Log In</h3>
		<div class="row">
			<div class="large-12 columns">
				<form action="" method="POST">
					<label for="username"><h5>Username <span class="required">*</span></h5>
				    	<input type="text" id="username" name="username">
				    </label>
				    <label for="password"><h5>Password <span class="required">*</span></h5>
				    	<input type="password" id="password" name="password">
				    </label>
				    <label for="remember">
						<input id="remember" name="remember" type="checkbox">Remember Me
					</label>
				    <input type="hidden" name="token" value="<?= Token::generate(); ?>">
				    <input class="button" type="submit" value="Login">
				</form>
				<div>
					<p>New user? Click <a href="register.php">Register</a></p>
				</div>
				<div>
					<p>Forgot your <a href="recover.php?mode=username">username</a> or <a href="recover.php?mode=password">password</a>?</p>
				</div>
				<?php
					if(isset($validation) && !$validation->passed() && !empty($validation->errors())){
						output_errors($validation->errors());
					}
					if(!empty($errors)){
						output_errors($errors);
					}
				?>
			</div>
		</div>
	</div>

<?php

	include 'includes/footer.php'

?>