<?php

	require_once 'core/init.php';

	//$user = new User();

	if(!$username = Input::get('user')){

		Redirect::to('index.php');
		
	}else{

		$public = new PublicInfo();
		$data = $public->getUserProfile(Input::get('user'));

		if($public->findUser(Input::get('user'))){
			$account = $public->dataUser();
		}else{
			Redirect::to('index.php');
		}

		$countPost = $public->getCountPost() ? $public->getCountPost() : 0;

		if(Session::exists(Config::get('session/session_name'))){
			$user = new User();
		}

	}

	include 'includes/header.php';

?>

	<div class="header-wrapper">
			<div class="header-container">
				<h3>Profile</h3>
			</div>
		</div>

	<div class="row">
		<div class="large-12 columns">
			<div class="row">
				<div class="large-3 columns">

					<div class="profile-card">
						<div class="row">
							<div class="large-12 columns">
							<?php
								if(empty($account->image)){
									echo '<img class="profile-img" src="images/profile/default-profile-pic.jpg" alt="Profile Image">';
								}else{
									echo '<img class="profile-img" src="'.$account->image.'" alt="Profile Image">';
								}
							?>
							</div>
						</div>

						<div class="row">
							<div class="large-12 columns">
								<h5 class="profile-name">
									<strong>
									<?php
										if(empty($account->firstname)){
											echo display($account->username);
										}else{
											echo display($account->firstname).' '.display($account->lastname);
										}
									?>
									</strong>
									<?php
										if(isset($user) && $user->isLoggedIn() && ($user->data()->username == Input::get('user'))){
											echo '<a style="float: right; font-size: 23px;" href="settings.php"><i class="fa fa-gear"></i></a>';
										}
									?>
								</h5>
							</div>
						</div>

						<div class="row">
							<div class="large-12 columns">
								<small><strong>Joined:</strong> <?= date("F j, Y", strtotime($account->joined)); ?></small><br>
								<small><strong>Email:</strong> <a href="mailto:<?= display($account->email); ?>"><?= display($account->email); ?></a></small><br>
								<small><strong>Location:</strong> <?= display($account->location); ?></small>
								<hr class="profile-hr">
								<small><?= display($account->description); ?></small>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="large-12 columns">
							<div class="profile-display">
								<ul>
									<li><span class="display-title">Posts</span><span class="badge"><?= $countPost; ?></span></li>
									<li><span class="display-title">Comments</span><span class="alert badge">67</span></li>
									<li><span class="display-title">Likes</span><span class="warning badge">124</span></li>
								</ul>
							</div>
						</div>
					</div>

				</div>
				<div class="large-9 columns">
					<div class="profile-post-container">
						<h3>Articles</h3>
						<?php
							if(empty($data)){
								echo '<div class="post-container"><h4>User doesn\'t have any articles!</h4></div>';
							}else{
								foreach($data as $post): ?>
								<div class="post-container">
									<h4><a href="publicPost.php?id=<?= $post->id; ?>"><?= display($post->title); ?></a></h4>
							        <h6><a href="index.php?id=<?= $post->cat_id; ?>"><?= $post->cat_name; ?></a> /
							          	<small style="color:#8A8989">by</small> <a href="<?= $post->username; ?>"><?= $post->username; ?></a> /
							          	<span style="color:#8A8989"><?= date('M j, Y  h:i a', strtotime($post->date_posted)); ?></span>
							        </h6>
							        <div><?= truncateHtml(display($post->contents), 300); ?></div>
								</div>
						 <?php 
						 		endforeach;
							}
						 ?>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php

	include 'includes/footer.php';

?>