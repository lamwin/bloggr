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
					'firstname' => array(
						'required' => true,
						'min' => 2,
						'max' => 50
					),
					'lastname' => array(
						'required' => true,
						'min' => 2,
						'max' => 50
					),
					'location' => array(
						'required' => true,
						'min' => 5,
						'max' => 50
					),
					'description' => array(
						'required' => true,
						'max' => 255
					)
				));

				if($validation->passed()){
					try{
						$user->update(array(
							'firstname' => escape(Input::get('firstname')),
							'lastname' => escape(Input::get('lastname')),
							'location' => escape(Input::get('location')),
							'description' => escape(Input::get('description'))
						));

						Session::flash('home', 'Your information was successfully updated!');
						Redirect::to('dashboard.php');

					}catch(Exception $e){
						die($e->getMessage());
					}
				}

			}
		}
	}

	include 'includes/header.php';

?>

	<div class="header-wrapper">
			<div class="header-container">
				<h3>Settings</h3>
			</div>
		</div>

	<div class="row">
		<div class="large-12 columns">
			<div class="row">
				<div class="large-6 columns">
					<form action="" method="POST">
						<label for="firstname"><h5>First Name <span class="required">*</span></h5>
					    	<input type="text" id="firstname" name="firstname" value="<?= escape($user->data()->firstname); ?>">
					    </label>
						<label for="lastname"><h5>Last Name <span class="required">*</span></h5>
					    	<input type="text" id="lastname" name="lastname" value="<?= escape($user->data()->lastname); ?>">
					    </label>
					    <label for="location"><h5>Location <span class="required">*</span></h5>
					    	<input type="text" id="location" name="location" value="<?= escape($user->data()->location); ?>" placeholder="San Francisco, CA">
					    </label>
					    <label for="description"><h5>Description <span class="required">*</span></h5>
					    	<textarea name="description" rows="8"><?= escape($user->data()->description); ?></textarea>
					    </label>
					    <input type="hidden" name="token" value="<?= Token::generate(); ?>">
					    <input type="submit" class="button" value="Submit">
					    <a class="button alert" href="dashboard.php">Back</a>
					</form>
					<?php
						if(isset($validation) && !$validation->passed() && !empty($validation->errors())){
							output_errors($validation->errors());
						}
					?>
				</div>
			</div>
		</div>
	</div>

<?php

	include 'includes/footer.php';

?>

