		<?php if(is_loggedin() && web_admin_level()>=$minim_web_admin_level) { ?>
			<a href="?p=add_items&category=<?php print $get_category; ?>" class="btn btn-info"><?php print $lang['is_add_items']; ?></a>
			</br></br>
		<?php } ?>	
				<ul class="breadcrumb">
					<li><a href="index.php">Item-Shop</a></li>
					<li class="active"><?php print is_get_category_name($get_category); ?></li>
				</ul>
				<div class="well">
					<div class="row">
						<?php
							$list = array();
							$list = is_items_list($get_category);
							
							if(!count($list))
								print 'Nothing found.';
							else {
								foreach($list as $row) {
						?>
						<a href="?p=item&id=<?php print $row['id']; ?>">
							<div class="col-md-3">
								<div class="panel panel-primary">
									<div class="panel-heading">
										<h3 class="panel-title"><?php print get_item_name($row['vnum']); ?></h3>
									</div>
									<div class="panel-body" style="min-height: 128px;">
										<center><img src="images/items/<?php print get_item_image($row['vnum']); ?>.png" class="image-item"></center>
									</div>
								</div>
							</div>
						</a>
								<?php } } ?>
					</div>
				</div>
