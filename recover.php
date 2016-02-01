<?php

	require_once 'core/init.php';

	logged_in_redirect();

	include 'includes/header.php';

?>

	<?php

		if(isset($_GET['success']) && empty(Input::get('success'))){
			echo '<div class="callout success" style="padding-bottom: 0px;">
					<div class="row"><div class="large-12 columns">
					  <p>Thanks, we have emailed you!</p>
					  </div></div>
				  </div>';
		}else{

			$recover = new Recover();

			if($recover->checkMode()){

				if(Input::exists()){

					if($recover->checkEmail(Input::get('email'))){
						$recovered = $recover->recover(Input::get('mode'), Input::get('email'));

						if($recovered){
							Redirect::to('recover.php?success');
						}
					}

				}

	?>

	<div class="register-card">
		<h3>Recover</h3>
		<div class="row">
			<div class="large-12 columns">
				<form action="" method="POST">
					<label><h5>Please enter your email address: <span class="required">*</span></h5>
						<input type="text" name="email">
					</label>
					<input type="submit" class="button" value="Recover">
				</form>
				<?php
					if(!$recover->passed() && !empty($recover->errors())){
						output_errors($recover->errors());
					}
				?>
			</div>
		</div>
	</div>

	<?php

			}else{
				
				Redirect::to('login.php');

			}
		}

	?>

<?php

	include 'includes/footer.php';

?>