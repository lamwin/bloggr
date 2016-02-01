<?php

	require_once 'core/init.php';

	$user = new User();

	require_once 'core/checkLogin.php';

	include 'includes/header.php';

	$blogger = $user->blogger();

	$posts = $blogger->getPost();

?>
		<div class="header-wrapper">
			<div class="header-container">
				<h3>Dashboard</h3>
			</div>
		</div>
		<div class="row">
			<div class="large-12 columns">
				<?php
					if(Session::exists('home')){
						flashMessage(Session::flash('home'));
					}
				?>
				<div class="row">
					<div class="large-2 columns">
					<?php
						include 'includes/Menu/sidebar.php';
					?>
					</div>
					<div class="large-10 columns">
					<?php
						if(empty($posts)){
							echo '<h5>You currently don\'t have any post!</h5>';
						}else{
							foreach($posts as $post){
					?>

						<h3><a href="post.php?id=<?= $post->id; ?>"><?= display($post->title); ?></a></h3>
				          <h6><a href="category.php?id=<?= $post->cat_id; ?>"><?= $post->cat_name; ?></a> /
				          	<span style="color:#8A8989"><?= date('M j, Y  h:i a', strtotime($post->date_posted)); ?></span>
				          </h6>
				          <div><?= truncateHtml(display($post->contents), 300); ?></div>
				        <hr>

					<?php
							}
						}
					?>
					</div>
				</div>
			</div>
		</div>

<?php

	include 'includes/footer.php';

?>