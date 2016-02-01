<?php

	require_once 'core/init.php';

	$user = new User();

	$public = new PublicInfo();

	include 'includes/header.php';

	$public->getCategory();
	$categories = $public->dataCategory();

	$posts = $public->getPost();

?>

	<div class="header-wrapper">
			<div class="header-container">
				<h3>Home</h3>
			</div>
		</div>

	<div class="row">
		<div class="large-12 columns">
		<?php
			if(Session::exists('index')){
				flashMessage(Session::flash('index'));
			}
		?>
		<div class="row">
			<div class="large-2 columns">
				<?php
					include 'includes/Menu/categorySidebar.php';
				?>
			</div>
			<div class="row">
				<div class="large-8 columns">
			<?php
				if(!Input::exists('get')){

					echo '<h3>Articles</h3><hr>';

					$posts = $public->getPost();

					if(empty($posts)){
						echo '<h5>There\'s currently no post!</h5>';
					}
					foreach($posts as $post){
			?>
				<h3><a href="publicPost.php?id=<?= $post->id; ?>"><?= display($post->title); ?></a></h3>
		          <h6><a href="index.php?id=<?= $post->cat_id; ?>"><?= $post->cat_name; ?></a> /
		          	<small style="color:#8A8989">by</small> <a href="<?= $post->username; ?>"><?= $post->username; ?></a> /
		          	<span style="color:#8A8989"><?= date('M j, Y  h:i a', strtotime($post->date_posted)); ?></span>
		          </h6>
		          <div><?= truncateHtml(display($post->contents), 300); ?></div>
		        <hr>
			<?php			
					}
				}else{
					if(isset($_GET['id'])){
						if(empty(Input::get('id'))){
							Redirect::to('index.php');
						}else{

							$posts = $public->getPost(Input::get('id'));

							if(empty($posts)){
								echo '<h5>There\'s currently no post in this category!</h5>';
							}else{

								echo '<h3>Articles <small>in</small> '.$posts[0]->cat_name.'</3><hr>';

								foreach($posts as $post){
						?>
								<h3><a href="publicPost.php?id=<?= $post->id; ?>"><?= display($post->title); ?></a></h3>
						        <h6><a href="index.php?id=<?= $post->cat_id; ?>"><?= $post->cat_name; ?></a> /
						          	<small style="color:#8A8989">by</small> <a href="<?= $post->username; ?>"><?= $post->username; ?></a> /
						          	<span style="color:#8A8989"><?= date('M j, Y  h:i a', strtotime($post->date_posted)); ?></span>
						        </h6>
						        <div><?= truncateHtml(display($post->contents), 300); ?></div>
						        <hr>
						<?php
								}
							}
						}
					}
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
