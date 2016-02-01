<?php

	require_once 'core/init.php';

	logged_in_redirect();

	include 'includes/header.php';

?>

	<?php

		if(isset($_GET['alert']) && empty(Input::get('alert'))){
			echo '<div class="callout alert">
					  <p>The link you\'re trying to access has already expired!</p>
				  </div>';
		}else{

			if(isset($_GET['email']) && isset($_GET['password_token'])){

				$reset = new Reset($_GET);
				$reset->fetchData();

				if(Input::get('password_token') !== $reset->data()->password_token){
					Redirect::to('reset_password.php?alert');
				}else{

					if(Input::exists()){
						if(Token::check(Input::get('token'))){
							$validate = new Validation();
							$validation = $validate->check($_POST, array(
								'password' => array(
									'required' => true,
									'min' => 6
								),
								'password_again' => array(
									'required' => true,
									'matches' => 'password'
								)
							));

							if($validation->passed()){
								$salt = Hash::salt(32);
								$password = Input::get('password');
								$reset->resetPassword($password, $salt);
								Session::flash('login', 'Your password was successfully reset!');
								Redirect::to('login.php');
							}
						}
					}

	?>

	<div class="reset-card">
		<h3>Reset Password</h3>
		<div class="row">
			<div class="large-6 columns">
				<form action="" method="POST">
					<label><h5>New Password <span class="required">*</span></h5>
						<input type="password" name="password">
					</label>
					<label><h5>Re-type Password <span class="required">*</span></h5>
						<input type="password" name="password_again">
					</label>
					<input type="hidden" name="token" value="<?= Token::generate(); ?>">
					<input type="submit" class="button" value="Reset">
				</form>
				<?php
					if(isset($validation) && !$validation->passed() && !empty($validation->errors())){
						output_errors($validation->errors());
					}
					if(isset($reset) && !$reset->passed() && !empty($reset->errors())){
						output_errors($reset->errors());
					}
				?>
			</div>
		</div>
	</div>

	<?php

				}		

			}else{

				Redirect::to('login.php');

			}

		}

	?>

<?php

	include 'includes/footer.php';

?>