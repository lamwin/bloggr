<?php

	require_once 'core/init.php';

	$user = new User();

	require_once 'core/checkLogin.php';

	include 'includes/header.php';

	$blogger = $user->blogger();

	$categories = $blogger->getCategory();

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
				$user_id = $user->data()->id;
				$title = escape(Input::get('title'));
				$contents = escape(Input::get('contents'));
				$date_posted = date('Y-m-d H:i:s');

				try{
					$blogger->createPost(array(
						'cat_id' => $cat_id,
						'user_id' => $user_id,
						'title' => $title,
						'contents' => $contents,
						'date_posted' => $date_posted
					));
					Session::flash('home', 'New post has been created!');
					Redirect::to('dashboard.php');
				}catch(Exception $e){
					die($e->getMessage());
				}
			}
		}
	}

?>

	<div class="header-wrapper">
			<div class="header-container">
				<h3>
					Create Post
					<?php
						foreach($categories as $category){
							if(isset($_GET['cat_id'])){
								if($category->cat_id == Input::get('cat_id')){
									echo '<small> in </small>'.$category->cat_name;
								}
							}
						}
					?>
				</h3>
			</div>
		</div>

	<div class="row">
		<div class="large-12 columns">
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
								<input type="text" name="title">
							</label>
							<label><h5>Category <span class="required">*</span></h5>
				                <select name="category">
				                  <?php
				                    foreach ($categories as $category){
				                    	$selected = '';
				                    	if(isset($_GET['cat_id'])){
				                    		if(!empty(Input::get('cat_id'))){
				                    			$selected = ($category->cat_id == Input::get('cat_id')) ? ' selected' : '';
				                    		}
				                    	}
				                  ?>
				                  		<option value="<?php echo $category->cat_id; ?>" <?= $selected; ?>><?= display($category->cat_name); ?></option>
				                  <?php
				                    }
				                  ?>
				                </select>
				            </label>
							<label><h5>Contents <span class="required">*</span></h5>
				                <textarea id="writable-area" name="contents" rows="15" cols="50"></textarea>
				            </label>
				            <br>
							<input type="hidden" name="token" value="<?= Token::generate(); ?>">
							<input type="submit" class="button" value="Create">
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

	<script src="../js/nicEdit.js"></script>
    <script type="text/javascript">
      bkLib.onDomLoaded(function() {
    		new nicEditor({iconsPath : '../images/nicEditorIcons.gif'}).panelInstance('writable-area');
    	});
    </script>

<?php

	include 'includes/footer.php';

?>