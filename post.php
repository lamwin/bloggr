<?php
	
	require_once 'core/init.php';

	$user = new User();

	require_once 'core/checkLogin.php';

	include 'includes/header.php';

	$blogger = $user->blogger();

	//$posts = $blogger->getPost();

	if(!isset($_GET['id'])){
		Redirect::to('dashboard.php');
	}else{
		if(empty(Input::get('id'))){
			Redirect::to('dashboard.php');
		}else{
			$checkPost = $blogger->checkPost(Input::get('id'));
			if($checkPost){
				$post = $blogger->getSpecificPost(Input::get('id'));
			}else{
				Redirect::to('dashboard.php');
			}
		}
	}

?>

	<div class="header-wrapper">
			<div class="header-container">
				<h3>Article</h3>
			</div>
		</div>
	
	<div class="row">
		<div class="large-12 columns">
			<?php
				if(Session::exists('post')){
					flashMessage(Session::flash('post'));
				}
			?>
			<div class="row">
				<div class="large-2 columns">
				<?php
					include 'includes/Menu/sidebar.php';
				?>
				</div>
				<div class="row">
					<div class="large-9 columns">
						<div class="row">
							<div class="large-12 columns">
								<h3><?= display($post->title); ?></h3>
							</div>
						</div>
						<div class="row">
							<div class="large-10 columns">
								<h6><a href="category.php?id=<?= $post->cat_id; ?>"><?= $post->cat_name; ?></a> /
					          		<span style="color:#8A8989"><?= date('M j, Y  h:i a', strtotime($post->date_posted)); ?></span>
					          	</h6>
							</div>
							<div class="large-2 columns" style="padding:0; padding-left: 58px;">
								<h6>
						            <small>
						            	<span><a href="edit_post.php?id=<?= $post->id; ?>">Edit</a></span>
						            	&nbsp;|&nbsp;
						            	<span><a style="color:#ec5840" href="delete_post.php?id=<?= $post->id; ?>">Delete</a></span>
						            </small>
						          </h6>
							</div>
						</div>
			            <hr>
			            <div class="row">
			            	<div class="large-12 columns">
			            		<span><?= display($post->contents); ?></span>
			            	</div>
			            </div>
			            <br>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php

	include 'includes/footer.php';

?>