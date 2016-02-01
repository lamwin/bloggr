<?php
	
	require_once 'core/init.php';

	$user = new User();

	require_once 'core/checkLogin.php';

	if(!$user->isLoggedIn()){
		Redirect::to('index.php');
	}else{
		if(Input::exists()){
			if(Token::check(Input::get('token'))){
				$validate = new Validation();
				$validation = $validate->check($_POST, array(
					'current_password' => array(
						'required' => true
					),
					'new_password' => array(
						'required' => true,
						'min' => 6
					),
					'password_again' => array(
						'required' => true,
						'matches' => 'new_password'
					)
				));

				if($validation->passed()){
					if(Hash::make(Input::get('current_password'), $user->data()->salt) !== $user->data()->password){
						$errors[] = 'Input for current password is incorrect!';
					}else{

						$salt = Hash::salt(32);

						try{

							$user->update(array(
								'password' => Hash::make(Input::get('new_password'), $salt),
								'salt' => $salt
							));

							Session::flash('home', 'Your password was successfully updated!');
							Redirect::to('dashboard.php');

						}catch (Exception $e){
							die($e->getMessage());
						}
					}
				}
			}
		}
	}

	include 'includes/header.php';

?>

	<div class="header-wrapper">
			<div class="header-container">
				<h3>Change Password</h3>
			</div>
		</div>

	<div class="row">
		<div class="large-12 columns">
			<div class="row">
				<div class="large-6 columns">
					<form action="" method="Post">
						<label for="current_password"><h5>Current Password <span class="required">*</span></h5>
					    	<input type="password" id="current_password" name="current_password">
					    </label>
						<label for="new_password"><h5>New Password <span class="required">*</span></h5>
					    	<input type="password" id="new_password" name="new_password">
					    </label>
						<label for="password_again"><h5>Re-type New Password <span class="required">*</span></h5>
					    	<input type="password" id="password_again" name="password_again">
					    </label>
					    <input type="hidden" name="token" value="<?= Token::generate(); ?>">
					    <input type="submit" class="button" value="Submit">
					    <a class="button alert" href="dashboard.php">Back</a>
					</form>
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
	</div>

<?php

	include 'includes/footer.php';

?>