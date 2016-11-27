		<?php if(is_loggedin() && web_admin_level()>=$minim_web_admin_level) { ?>
			<a href="?p=categories" class="btn btn-info"><?php print $lang['administration_categories']; ?></a>
			<a href="?p=paypal" class="btn btn-info"><?php print $lang['administration_pp']; ?></a>
			</br></br>
		<?php } ?>	
				<div class="well">
					<div class="row">
						<?php
							$list = array();
							$list = is_categories_list();
							
							if(!count($list))
								print 'Nothing found.';
							else {
								foreach($list as $row) {
						?>
						<a href="?p=items&category=<?php print $row['id']; ?>">
							<div class="col-md-3">
								<div class="panel panel-primary">
									<div class="panel-heading">
										<h3 class="panel-title"><?php print $row['name']; ?></h3>
									</div>
									<div class="panel-body" style="min-height: 128px;">
										<center><img src="images/items/<?php print get_item_image($row['img']); ?>.png" class="image-item"></center>
									</div>
								</div>
							</div>
						</a>
								<?php } } ?>
					</div>
				</div>
