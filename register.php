<?php

	require_once 'core/init.php';

	logged_in_redirect();

	if(Input::exists()){
		if(Token::check(Input::get('token'))){

			$validate = new Validation();
			$validation = $validate->check($_POST, array(
				'email' 			=> array(
					'required'	=> true,
					'email'		=> true,
					'min'		=> 10,
					'max'		=> 50,
					'unique'	=> 'users' 
				),
				'username' 			=> array(
					'required' 	=> true,
					'min' 		=> 2,
					'max' 		=> 20,
					'unique' 	=> 'users'
				),
				'password' 			=> array(
					'required'	=> true,
					'min' 		=> 6			
				),
				'password_again' 	=> array(
					'required' 	=> true,
					'matches' 	=> 'password'
				)
			));

			if($validation->passed()){
				$user = new User();

				$salt = Hash::salt(32);

				try{
					$user->create(array(
						'email' => escape(Input::get('email')),
						'username' => escape(Input::get('username')),
						'password' => Hash::make(Input::get('password'), $salt),
						'salt' => $salt,
						'joined' => date('Y-m-d H:i:s'),
						'group' => 1
					));

					Session::flash('login', 'You have successfully been registered!');
					Redirect::to('login.php');

				}catch(Exception $e){
					die($e->getMessage());
				}
			}
		}
	}

	include 'includes/header.php';
?>

	<div class="register-card">
		<h3>Sign Up</h3>
		<div class="row">
			<div class="large-12 columns">
				<form action="" method="POST">
					<label><h5>Email Address <span class="required">*</span></h5>
				    	<input type="text" id="email" name="email" value="<?= escape(Input::get('email')); ?>">
				    </label>
					<label><h5>Username <span class="required">*</span></h5>
				    	<input type="text" id="username" name="username" value="<?= escape(Input::get('username')); ?>">
				    </label>
				    <label><h5>Password <span class="required">*</span></h5>
				    	<input type="password" id="password" name="password">
				    </label>
				    <label><h5>Re-type Password <span class="required">*</span></h5>
				    	<input type="password" id="password_again" name="password_again">
				    </label>
				    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
				    <input class="button" type="submit" value="Sign Up">
				</form>
				<?php
					if(isset($validation) && !$validation->passed() && !empty($validation->errors())){
						output_errors($validation->errors());
					}
				?>
			</div>
		</div>
	</div>


<?php
	include 'includes/footer.php';
?>