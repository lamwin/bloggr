<?php

	require_once 'core/init.php';

	$user = new User();

	require_once 'core/checkLogin.php';

	include 'includes/header.php';

	$blogger = $user->blogger();

	$categories = $blogger->getCategory();

	if(!isset($_GET['id'])){
		Redirect::to('dashboard.php');
	}else{
		if(empty(Input::get('id'))){
			Redirect::to('dashboard.php');
		}else{
			$checkPost = $blogger->checkPost(Input::get('id'));
			if($checkPost){
				$post = $blogger->getSpecificPost(Input::get('id'));
				if(Input::exists()){
					if(Token::check(Input::get('token'))){
						$validate = new Validation();
						$validation = $validate->check($_POST, array(
							'title' => array(
								'required' => true,
								'min' => 2,
								'max' => 255
							),
							'contents' => array(
								'required' => true
							)
						));

						if($validation->passed()){
							$cat_id = Input::get('category');
							$title = escape(Input::get('title'));
							$contents = escape(Input::get('contents'));

							try{
								$blogger->updatePost(Input::get('id'), array(
									'cat_id' => $cat_id,
									'title' => $title,
									'contents' => $contents
								));
								Session::flash('post', 'Post has successfully been updated!');
								Redirect::to('post.php?id='.Input::get('id'));
							}catch(Exception $e){
								die($e->getMessage());
							}
						}
					}
				}
			}else{
				Redirect::to('dashboard.php');
			}
		}
	}

?>

	<div class="row">
		<div class="large-12 columns">
			<div class="row">
				<h3>Edit Post</h3>
				<hr>
				<div class="row">
					<div class="large-2 columns">
					<?php
						include 'includes/Menu/sidebar.php';
					?>
					</div>
					<div class="row">
						<div class="large-8 columns">
							<form action="" method="POST">
								<label><h5>Title <span class="required">*</span></h5>
									<input type="text" name="title" value="<?= display($post->title); ?>">
								</label>
								<label><h5>Category <span class="required">*</span></h5>
					                <select name="category">
					                  <?php
					                    foreach ($categories as $category){
					                    	$selected = ($category->cat_name == $post->cat_name) ? ' selected' : '';
					                  ?>
					                  <option value="<?= $category->cat_id; ?>" <?= $selected; ?>><?= display($category->cat_name); ?></option>
					                  <?php
					                    }
					                  ?>
					                </select>
					            </label>
								<label><h5>Contents <span class="required">*</span></h5>
					                <textarea id="writable-area" name="contents" rows="15" cols="50"><?= display($post->contents); ?></textarea>
					            </label>
					            <br>
								<input type="hidden" name="token" value="<?= Token::generate(); ?>">
								<input type="submit" class="button success" value="Update">
								<a class="button alert" href="post.php?id=<?= $post->id ?>">Back</a>
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
		</div>
	</div>

	<script src="../js/nicEdit.js"></script>
    <script type="text/javascript">
      bkLib.onDomLoaded(function() {
    		new nicEditor({iconsPath : '../images/nicEditorIcons.gif'}).panelInstance('writable-area');
    	});
    </script>

<?php

	include 'includes/footer.php';

?>