		<?php if(is_loggedin() && web_admin_level()>=$minim_web_admin_level) { ?>
			<a href="?p=items&remove=<?php print $get_item; ?>" class="btn btn-danger"><?php print $lang['is_delete_items']; ?></a>
			</br></br>
		<?php } ?>

				<ul class="breadcrumb">
					<li><a href="index.php">Item-Shop</a></li>
					<li><a href="?p=items&category=<?php print $item[0]['category']; ?>"><?php print is_get_category_name($item[0]['category']); ?></a></li>
					<li class="active"><?php print get_item_name($item[0]['vnum']); ?></li>
				</ul>
				<div class="well">
					<?php 
					if(is_loggedin())
						if(isset($_POST['buy']) && isset($_POST['buy_key']) && $_POST['buy_key'] == $_SESSION['buy_key'])
						{
							$ok = 0;
							if($item[0]['pay_type']==1)
							{
								if($item[0]['coins']<=is_coins())
								{
									if(is_buy_item($get_item))
									{
										is_pay_coins($item[0]['pay_type']-1, $item[0]['coins']);
										$ok = 1;
									} else { $ok=2; ?>
										<div class="alert alert-dismissible alert-danger">
											<button type="button" class="close" data-dismiss="alert">&times;</button>
											<?php print $lang['no_space']; ?>
										</div>
								<?php }
								}
							} else {
								if($item[0]['coins']<=is_coins(1))
								{
									if(is_buy_item($get_item))
									{
										is_pay_coins($item[0]['pay_type']-1, $item[0]['coins']);
										$ok = 1;
									} else { $ok=2; ?>
										<div class="alert alert-dismissible alert-danger">
											<button type="button" class="close" data-dismiss="alert">&times;</button>
											<?php print $lang['no_space']; ?>
										</div>
							<?php }
								}
							}
							
						if($ok==1) { ?>
							<div class="alert alert-dismissible alert-success">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								<?php print $lang['successfully_bought']; ?>
							</div>
						<?php } else if($ok==0) { ?>
							<div class="alert alert-dismissible alert-danger">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								ERROR
							</div>
						<?php }
						}
					?>
					<div class="row">
						<div class="col-md-8">
							<div class="panel panel-info">
								<div class="panel-heading">
									<h3 class="panel-title"><?php print $lang['description']; ?></h3>
								</div>
								<div class="panel-body">
									<?php print nl2br($item[0]['description']); ?>
								</div>
							</div>
						</div>
						
						<div class="col-md-4">

							<div class="panel panel-info">
								<div class="panel-heading">
									<h3 class="panel-title"><?php print $lang['info_object']; ?></h3>
								</div>
								<div class="panel-body">
									<div id="myTabContent" class="tab-content">
										<div role="tabpanel" class="tab-pane fade active in">
											<center>
												<img src="images/items/<?php print get_item_image($item[0]['vnum']); ?>.png">
												<h3><?php print get_item_name($item[0]['vnum']); ?></h3>
											</center>
											<?php if($item[0]['count']>1) { ?>
											<hr>
											<center><p class="text-info"><b><?php print ucfirst(strtolower($lang['quantity'])).': '.$item[0]['count']; ?></b></p></center>
											<?php } ?>
											<center><?php is_get_item($get_item); ?></center>
											<?php
												if(check_item_sash($item[0]['vnum'])) {
											?>
											<center><?php is_get_sash_bonuses($get_item); ?></center>
											<?php
												}
												$lvl = get_item_lvl($item[0]['vnum']);
												if($lvl) {
											?>
											<center><p class="text-danger"><?php print $lang['available_lvl']; ?> <b><?php print $lvl; ?></b></p></center>
											<?php } if(check_item_sash($item[0]['vnum'])) { ?>
											<center><p class="text-warning"><?php print $lang['bonus_absorption']; ?> <b><?php print is_get_sash_absorption($get_item); ?></b>%</p></center>
											<?php } if(get_item_name($item[0]['socket0']))
														get_item_stones_market($get_item);
												else if((get_item_type($item[0]['vnum'])=="ITEM_UNIQUE" || $item[0]['socket0']) && is_loggedin()) { ?>
											<center><hr><p class="text-info"><?php print $lang['time_left']; ?> <b><?php is_get_item_time($get_item); ?></b></p></center>
											<?php } ?>
											<?php if(is_loggedin()) { ?>
											<hr>
											<button type="button" class="btn btn-success btn-block<?php if(is_coins($item[0]['pay_type']-1)<$item[0]['coins']) print ' disabled'; ?>" data-toggle="modal" data-target="#myModal"><img src="images/<?php if($item[0]['pay_type']==1) print 'md'; else print 'jd'; ?>.png" title="MD"> <?php print $lang['buy'].' ('.$item[0]['coins'].' '; if($item[0]['pay_type']==1) print 'MD'; else print 'JD'; ?>)</button>
											<?php } if(!is_loggedin()) { ?>
												<div class="alert alert-dismissible alert-danger">
												<button type="button" class="close" data-dismiss="alert">×</button>
												<strong>Info:</strong> Autentificarea este obligatorie! </div>
											<?php } ?>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
				<?php if(is_loggedin() && is_coins($item[0]['pay_type']-1)>=$item[0]['coins']) { ?>
				<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="myModalLabel"><?php print $lang['buy']; ?></h4>
							</div>
							<div class="modal-body">
								<?php print $lang['sure']; 
									$_SESSION['buy_key'] = mt_rand(1, 1000);
								?>
							</div>
							<div class="modal-footer">
								<form action="" method="post">
								<input type="hidden" name="buy_key" value="<?php echo $_SESSION['buy_key'] ?>">
								<input type="submit" class="btn btn-success" name="buy" value="<?php print $lang['buy']; ?>">
								<button type="button" class="btn btn-danger" data-dismiss="modal"><?php print $lang['no']; ?></button>
								</form>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>