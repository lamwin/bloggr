<div class="row">
	<div class="large-12 columns">
		<div class="callout" id="sidebar">
			<ul class="sidebar menu vertical">
				<li class="menu-text">Categories</li>
				<li><a href="index.php">All</a></li>
			<?php
				foreach($categories as $category){
			?>
				<li><a href="index.php?id=<?= $category->cat_id; ?>"><?= display($category->cat_name); ?> (<?= $category->count_post; ?>)</a></li>
			<?php		
				}
			?>
			</ul>
		</div>
	</div>
</div>