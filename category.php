<?php

	require_once 'core/init.php';

	$user = new User();

	require_once 'core/checkLogin.php';

	include 'includes/header.php';

	$blogger = $user->blogger();

	$page = isset($_GET['page']) && !empty(Input::get('page')) ? (int)Input::get('page') : 1;


?>

	<div class="header-wrapper">
			<div class="header-container">
				<h3>Category</h3>
			</div>
		</div>

	<div class="row">
		<div class="large-12 columns">
			<?php
				if(Session::exists('category')){
					flashMessage(Session::flash('category'));
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
					if(!isset($_GET['id'])){
						$categories = $blogger->getCategory(null, $page);

						$total = $blogger->count('categories');

						$pages = ceil($total / 6);

						if(isset($_GET['page']) && Input::get('page') > $pages){
							Redirect::to('category.php?page=1');
						}

						if(empty($categories)){
							echo '<h5>You do not have any category created!</h5>';
						}else{
				?>

							<table class="hover" style="width: 100%;">
								<thead>
									<tr>
										<th>Category Name</th>
										<th width="170"></th>
									</tr>
								</thead>
								<tbody>
								<?php foreach($categories as $category): ?>
									<tr>
										<td><a href="category.php?id=<?= $category->cat_id ?>"><?= display($category->cat_name); ?> (<?= $category->count_post; ?>)</a></td>
										<td><a class="button success activate-btn" href="create_post.php?cat_id=<?= $category->cat_id; ?>">Create Post</a></td>	
									</tr>
								<?php endforeach; ?>
								</tbody>
							</table>
							<ul class="pagination text-center" role="navigation" aria-label="Pagination">
							  <?php for($x = 1; $x <= $pages; $x++): ?>
							  	<?php
							  		if(!isset($_GET['page'])){
							  			if($x == 1){
								  			echo '<li class="current">'.$x.'</li>';
								  		}else{
								  			echo '<li><a href="?page='.$x.'">'.$x.'</a></li>';
								  		}
							  		}else{
							  			if(Input::get('page') == $x){
								  			echo '<li class="current">'.$x.'</li>';
								  		}else{
								  			echo '<li><a href="?page='.$x.'">'.$x.'</a></li>';
								  		}
							  		}
								?>
							  <?php endfor; ?>
							</ul>

				<?php
						}		
					}else{
						if(empty($_GET['id'])){
							Redirect::to('category.php');
						}else{
							$categories = $blogger->getCategory(Input::get('id'));
							if($categories){
								$posts = $blogger->getPost(Input::get('id'));
								if(empty($posts)){
									echo '<h5>You do not have any post in this category!</h5>';
								}else{
									foreach($posts as $post){
							?>

								<h3><a href="post.php?id=<?php echo $post->id; ?>"><?= display($post->title); ?></a></h3>
						          <h6><a href="category.php?id=<?= $post->cat_id; ?>"><?= $post->cat_name; ?></a> /
						          	<span style="color:#8A8989"><?= date('M j, Y  h:i a', strtotime($post->date_posted)); ?></span>
						          </h6>
						          <div><?= truncateHtml(display($post->contents), 300); ?></div>
						        <hr>
							
							<?php			
									}
								}
							}else{
								Redirect::to('category.php');
							}
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