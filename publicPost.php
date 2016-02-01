<?php

	require_once 'core/init.php';

	$user = new User();

	$public = new PublicInfo();

	include 'includes/header.php';

	$public->getCategory();
	$categories = $public->dataCategory();

	if(!isset($_GET['id'])){
		Redirect::to('index.php');
	}else{
		if(empty(Input::get('id'))){
			Redirect::to('index.php');
		}else{
			$checkPost = $public->checkPost(Input::get('id'));
			if($checkPost){
				$post = $public->getSpecificPost(Input::get('id'));
			}else{
				Redirect::to('index.php');
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
			<div class="row">
				<div class="large-2 columns">
				<?php
					include 'includes/Menu/categorySidebar.php';
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
								<h6><a href="index.php?id=<?= $post->cat_id; ?>"><?= $post->cat_name; ?></a> /
						          	<small style="color:#8A8989">by</small> <a href="<?= $post->username; ?>"><?= $post->username; ?></a> /
						          	<span style="color:#8A8989"><?= date('M j, Y  h:i a', strtotime($post->date_posted)); ?></span>
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