		<?php if(is_loggedin() && web_admin_level()>=$minim_web_admin_level) { ?>
			<a href="?p=paypal" class="btn btn-info"><?php print $lang['administration_pp']; ?></a>
			</br></br>
		<?php } 
			$paypal_paid = isset($_GET['m']) ? $_GET['m'] : null;
		?>

				<ul class="breadcrumb">
					<li><a href="index.php">Item-Shop</a></li>
					<li class="active"><?php print $lang['pay']; ?></li>
				</ul>
				<?php if($paypal_paid=='success') { ?>
				<div class="alert alert-dismissible alert-success">
					<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>Info:</strong> <?php print $lang['paypal_wait']; ?></div>
				<?php } ?>
				<div class="well">
					<div class="row">
						<?php
							if(!count($list))
								print 'Nothing found.';
							else {
								foreach($list as $row) {
						?>
						<form action="" method="post" class="form-horizontal">
						
							<input type="hidden" name="id" value="<?php print $row['id']; ?>">
							<input type="submit" id="submit-form<?php print $row['id']; ?>" class="hidden" />
							
							<label for="submit-form<?php print $row['id']; ?>" style="display: block; margin-bottom: auto;">
								<div class="col-md-3">
									<div class="panel panel-primary">
										<div class="panel-heading">
											<h3 class="panel-title"><?php print $row['price']; ?> &euro; - <?php print $row['coins']; ?> MD</h3>
										</div>
										<div class="panel-body" style="min-height: 128px;">
											<center><img src="images/paypal.png" class="image-item"></center>
										</div>
									</div>
								</div>
							</label>
						</form>
								<?php } } ?>
					</div>
				</div>
