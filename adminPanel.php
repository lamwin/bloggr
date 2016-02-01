<?php

	require_once 'core/init.php';

	$user = new User();

	require_once 'core/checkLogin.php';
	require_once 'core/checkAdmin.php';


	include 'includes/header.php';

	$admin = $user->admin();

	$users = $admin->allUsers();

?>

	<div class="header-wrapper">
		<div class="header-container">
			<h3>Admin Panel</h3>
		</div>
	</div>

	<div class="row">
		<div class="large-12 columns">
			<?php
				if(Session::exists('admin')){
					flashMessage(Session::flash('admin'));
				}
			?>
			<div class="row">
				<div class="large-12 columns">
					<table class="hover" style="width: 100%;">
					  <thead>
					    <tr>
					      <th>Username</th>
					      <th>Email</th>
					      <th>Full Name</th>
					      <th>Location</th>
					      <th>Date Joined</th>
					      <th>Account Status</th>
					      <th></th>
					    </tr>
					  </thead>
					  <tbody>
				<?php

					foreach($users as $user){

				?>
					    <tr>
					      <td><a href="<?= $user->username; ?>"><?= display($user->username); ?></a></td>
					      <td><?= display($user->email); ?></td>
					      <td><?= display($user->firstname).' '.display($user->lastname); ?></td>
					      <td><?= display($user->location); ?></td>
					      <td><?= date("F j, Y", strtotime($user->joined)); ?></td>
					      <td>
						<?php
							if($user->active == 1){
								echo '<span style="color:#22BB5B">Active</span>';
							}else{
								echo '<span style="color:#ec5840">Inactive</span>';
							}
						?>      	
					      </td>
					      <td style="text-align: center;">
					    <?php
					    	if($user->active == 1){
					    		echo '<a class="button tiny alert activate-btn" style="color: white;" href="admin_activate.php?id='.$user->id.'&mode=deactivate">Deactivate</a>';
					    	}else if($user->active == 0){
					    		echo '<a class="button tiny success activate-btn" style="color: white;" href="admin_activate.php?id='.$user->id.'&mode=activate">Activate</a>';
					    	}
					    ?>  	
					      </td>

					    </tr>
				<?php
					}
				?>
					  </tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

<?php

	include 'includes/footer.php';

?>