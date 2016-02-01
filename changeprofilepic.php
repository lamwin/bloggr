<?php

	require_once 'core/init.php';

	$user = new User();

	require_once 'core/checkLogin.php';

	if(!$user->isLoggedIn()){
		Redirect::to('index.php');
	}else{
		if(Input::exists()){
			if(Token::check(Input::get('token'))){

				if(isset($_FILES['profile_image'])){

					if(empty($_FILES['profile_image']['name'])){

						$errors[] = 'Please choose a file';

					}else{

						$image = new Image($_FILES['profile_image']);

						$img = $image->check();

						if($img->passed()){
							$img->store('images/profile/');
							$file_path = $img->getFilePath();

							try{
								$user->update(array(
									'image' => $file_path
								));

								Session::flash('home', 'Your profile image was successfully updated!');
								Redirect::to('dashboard.php');

							}catch(Exception $e){
								die($e->getMessage());
							}
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
				<h3>Profile Image</h3>
			</div>
		</div>

	<div class="row">
		<div class="large-12 columns">
			<div class="row">
				<div class="large-6 columns">
					<?php

						if(!empty($user->data()->image)){
							echo '<img style="width: 150px;" src="'.$user->data()->image.'" alt="Profile Image" >';
						}

					?>
					<form action="" method="POST" enctype="multipart/form-data">
						<input type="file" name="profile_image">
					    <input type="hidden" name="token" value="<?= Token::generate(); ?>">
					    <input type="submit" class="button" value="Submit">
					    <a class="button alert" href="dashboard.php">Back</a>
					</form>
					<?php
						if(!empty($errors)){
							output_errors($errors);
						}
						if(isset($img) && !$img->passed() && !empty($img->errors())){
							output_errors($img->errors());
						}
					?>
				</div>
			</div>
		</div>
	</div>

<?php

	include 'includes/footer.php';

?>