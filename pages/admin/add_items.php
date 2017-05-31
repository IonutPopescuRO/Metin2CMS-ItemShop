<?php
	if(isset($_POST['add']))
	{
		if($_POST['count']<=0)
			$_POST['count']=1;
		
		for($i=0;$i<=6;$i++) 
			if($_POST['attrtype'.$i]==0)
				$_POST['attrvalue'.$i]=0;
			
		if(check_item_column("applytype0"))
			for($i=0;$i<=7;$i++) 
				if($_POST['applytype'.$i]==0)
					$_POST['applyvalue'.$i]=0;
				
		if($_POST['socket0']!="")
			$socket0 = $_POST['socket0'];
		else
			$socket0 = 0;
		if($_POST['socket1']!="")
			$socket1 = $_POST['socket1'];
		else
			$socket1 = 0;
		if($_POST['socket2']!="")
			$socket2 = $_POST['socket2'];
		else
			$socket2 = 0;

		if(check_item_column("applytype0") && check_item_sash($_POST['vnum']) && $_POST['time2']==0)
		{   
			$stmt = $sqlite->prepare('INSERT INTO item_shop_items (category, description, pay_type, coins, count, vnum, socket1, socket2, attrtype0, attrvalue0, attrtype1 , attrvalue1, attrtype2, attrvalue2, attrtype3, attrvalue3, attrtype4, attrvalue4, attrtype5, attrvalue5, attrtype6, attrvalue6, applytype0, applyvalue0, applytype1, applyvalue1, applytype2, applyvalue2, applytype3, applyvalue3, applytype4, applyvalue4, applytype5, applyvalue5, applytype6, applyvalue6, applytype7, applyvalue7) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
			$stmt->execute(array($get_category, $_POST['description'], $_POST['method_pay'], $_POST['coins'], $_POST['count'], $_POST['vnum'], $_POST['absorption'], $_POST['time'],
								$_POST['attrtype0'], $_POST['attrvalue0'], $_POST['attrtype1'], $_POST['attrvalue1'], $_POST['attrtype2'], $_POST['attrvalue2'], 
								$_POST['attrtype3'], $_POST['attrvalue3'], $_POST['attrtype4'], $_POST['attrvalue4'], $_POST['attrtype5'], $_POST['attrvalue5'], 
								$_POST['attrtype6'], $_POST['attrvalue6'], 
								$_POST['applytype0'], $_POST['applyvalue0'], $_POST['applytype1'], $_POST['applyvalue1'], $_POST['applytype2'], $_POST['applyvalue2'], 
								$_POST['applytype3'], $_POST['applyvalue3'], $_POST['applytype4'], $_POST['applyvalue4'], $_POST['applytype5'], $_POST['applyvalue5'], 
								$_POST['applytype6'], $_POST['applyvalue6'], $_POST['applytype7'], $_POST['applyvalue7']));
		}
		else if(check_item_column("applytype0") && check_item_sash($_POST['vnum']) && $_POST['time2'])
		{
			$type = 1;
			$stmt = $sqlite->prepare('INSERT INTO item_shop_items (category, description, pay_type, coins, count, vnum, socket1, socket2, attrtype0, attrvalue0, attrtype1 , attrvalue1, attrtype2, attrvalue2, attrtype3, attrvalue3, attrtype4, attrvalue4, attrtype5, attrvalue5, attrtype6, attrvalue6, applytype0, applyvalue0, applytype1, applyvalue1, applytype2, applyvalue2, applytype3, applyvalue3, applytype4, applyvalue4, applytype5, applyvalue5, applytype6, applyvalue6, applytype7, applyvalue7, type) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
			$stmt->execute(array($get_category, $_POST['description'], $_POST['method_pay'], $_POST['coins'], $_POST['count'], $_POST['vnum'], $_POST['absorption'], $_POST['time2'],
								$_POST['attrtype0'], $_POST['attrvalue0'], $_POST['attrtype1'], $_POST['attrvalue1'], $_POST['attrtype2'], $_POST['attrvalue2'], 
								$_POST['attrtype3'], $_POST['attrvalue3'], $_POST['attrtype4'], $_POST['attrvalue4'], $_POST['attrtype5'], $_POST['attrvalue5'], 
								$_POST['attrtype6'], $_POST['attrvalue6'], 
								$_POST['applytype0'], $_POST['applyvalue0'], $_POST['applytype1'], $_POST['applyvalue1'], $_POST['applytype2'], $_POST['applyvalue2'], 
								$_POST['applytype3'], $_POST['applyvalue3'], $_POST['applytype4'], $_POST['applyvalue4'], $_POST['applytype5'], $_POST['applyvalue5'], 
								$_POST['applytype6'], $_POST['applyvalue6'], $_POST['applytype7'], $_POST['applyvalue7'], $type));
		}
		else if(check_item_column("applytype0") && check_item_sash($_POST['vnum']))
		{
			$type = 1;
			$stmt = $sqlite->prepare('INSERT INTO item_shop_items (category, description, pay_type, coins, count, vnum, socket1, socket2, attrtype0, attrvalue0, attrtype1 , attrvalue1, attrtype2, attrvalue2, attrtype3, attrvalue3, attrtype4, attrvalue4, attrtype5, attrvalue5, attrtype6, attrvalue6, applytype0, applyvalue0, applytype1, applyvalue1, applytype2, applyvalue2, applytype3, applyvalue3, applytype4, applyvalue4, applytype5, applyvalue5, applytype6, applyvalue6, applytype7, applyvalue7, type) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
			$stmt->execute(array($get_category, $_POST['description'], $_POST['method_pay'], $_POST['coins'], $_POST['count'], $_POST['vnum'], $_POST['absorption'], $_POST['time'],
								$_POST['attrtype0'], $_POST['attrvalue0'], $_POST['attrtype1'], $_POST['attrvalue1'], $_POST['attrtype2'], $_POST['attrvalue2'], 
								$_POST['attrtype3'], $_POST['attrvalue3'], $_POST['attrtype4'], $_POST['attrvalue4'], $_POST['attrtype5'], $_POST['attrvalue5'], 
								$_POST['attrtype6'], $_POST['attrvalue6'], 
								$_POST['applytype0'], $_POST['applyvalue0'], $_POST['applytype1'], $_POST['applyvalue1'], $_POST['applytype2'], $_POST['applyvalue2'], 
								$_POST['applytype3'], $_POST['applyvalue3'], $_POST['applytype4'], $_POST['applyvalue4'], $_POST['applytype5'], $_POST['applyvalue5'], 
								$_POST['applytype6'], $_POST['applyvalue6'], $_POST['applytype7'], $_POST['applyvalue7'], $type));
		}
		else if(check_item_column("applytype0") && ($socket0 || $socket1 || $socket2))//pietre
		{
			$type = 2;
			$stmt = $sqlite->prepare('INSERT INTO item_shop_items (category, description, pay_type, coins, count, vnum, socket0, socket1, socket2, attrtype0, attrvalue0, attrtype1 , attrvalue1, attrtype2, attrvalue2, attrtype3, attrvalue3, attrtype4, attrvalue4, attrtype5, attrvalue5, attrtype6, attrvalue6, applytype0, applyvalue0, applytype1, applyvalue1, applytype2, applyvalue2, applytype3, applyvalue3, applytype4, applyvalue4, applytype5, applyvalue5, applytype6, applyvalue6, applytype7, applyvalue7, type) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
			$stmt->execute(array($get_category, $_POST['description'], $_POST['method_pay'], $_POST['coins'], $_POST['count'], $_POST['vnum'], $socket0, $socket1, $socket2,
								$_POST['attrtype0'], $_POST['attrvalue0'], $_POST['attrtype1'], $_POST['attrvalue1'], $_POST['attrtype2'], $_POST['attrvalue2'], 
								$_POST['attrtype3'], $_POST['attrvalue3'], $_POST['attrtype4'], $_POST['attrvalue4'], $_POST['attrtype5'], $_POST['attrvalue5'], 
								$_POST['attrtype6'], $_POST['attrvalue6'], 
								$_POST['applytype0'], $_POST['applyvalue0'], $_POST['applytype1'], $_POST['applyvalue1'], $_POST['applytype2'], $_POST['applyvalue2'], 
								$_POST['applytype3'], $_POST['applyvalue3'], $_POST['applytype4'], $_POST['applyvalue4'], $_POST['applytype5'], $_POST['applyvalue5'], 
								$_POST['applytype6'], $_POST['applyvalue6'], $_POST['applytype7'], $_POST['applyvalue7'], $type));
		}
		else if($socket0 || $socket1 || $socket2)//pietre
		{
			$stmt = $sqlite->prepare('INSERT INTO item_shop_items (category, description, pay_type, coins, count, vnum, socket0, socket1, socket2, attrtype0, attrvalue0, attrtype1 , attrvalue1, attrtype2, attrvalue2, attrtype3, attrvalue3, attrtype4, attrvalue4, attrtype5, attrvalue5, attrtype6, attrvalue6) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
			$stmt->execute(array($get_category, $_POST['description'], $_POST['method_pay'], $_POST['coins'], $_POST['count'], $_POST['vnum'], $socket0, $socket1, $socket2,
								$_POST['attrtype0'], $_POST['attrvalue0'], $_POST['attrtype1'], $_POST['attrvalue1'], $_POST['attrtype2'], $_POST['attrvalue2'], 
								$_POST['attrtype3'], $_POST['attrvalue3'], $_POST['attrtype4'], $_POST['attrvalue4'], $_POST['attrtype5'], $_POST['attrvalue5'], 
								$_POST['attrtype6'], $_POST['attrvalue6']));
		}
		else if($_POST['time2']==0)
		{
			$type = 2;
			$stmt = $sqlite->prepare('INSERT INTO item_shop_items (category, description, pay_type, coins, count, vnum, socket2, attrtype0, attrvalue0, attrtype1 , attrvalue1, attrtype2, attrvalue2, attrtype3, attrvalue3, attrtype4, attrvalue4, attrtype5, attrvalue5, attrtype6, attrvalue6, type) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
			$stmt->execute(array($get_category, $_POST['description'], $_POST['method_pay'], $_POST['coins'], $_POST['count'], $_POST['vnum'], $_POST['time'],
								$_POST['attrtype0'], $_POST['attrvalue0'], $_POST['attrtype1'], $_POST['attrvalue1'], $_POST['attrtype2'], $_POST['attrvalue2'], 
								$_POST['attrtype3'], $_POST['attrvalue3'], $_POST['attrtype4'], $_POST['attrvalue4'], $_POST['attrtype5'], $_POST['attrvalue5'], 
								$_POST['attrtype6'], $_POST['attrvalue6'], $type));
		} else {
			$type = 1;
			$stmt = $sqlite->prepare('INSERT INTO item_shop_items (category, description, pay_type, coins, count, vnum, socket0, attrtype0, attrvalue0, attrtype1 , attrvalue1, attrtype2, attrvalue2, attrtype3, attrvalue3, attrtype4, attrvalue4, attrtype5, attrvalue5, attrtype6, attrvalue6, type) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
			$stmt->execute(array($get_category, $_POST['description'], $_POST['method_pay'], $_POST['coins'], $_POST['count'], $_POST['vnum'], $_POST['time2'],
								$_POST['attrtype0'], $_POST['attrvalue0'], $_POST['attrtype1'], $_POST['attrvalue1'], $_POST['attrtype2'], $_POST['attrvalue2'], 
								$_POST['attrtype3'], $_POST['attrvalue3'], $_POST['attrtype4'], $_POST['attrvalue4'], $_POST['attrtype5'], $_POST['attrvalue5'], 
								$_POST['attrtype6'], $_POST['attrvalue6'], $type));
		}
	}
?>

			<ul class="breadcrumb">
				<li><a href="index.php">Item-Shop</a></li>
				<li class="active"><?php print ucfirst($current_page); ?></li>
			</ul>
			<div class="well">
			
				<form action="" method="post" class="form-horizontal">
					<div class="row">
						<div class="col-md-2"></div>
						<div class="col-md-8">
							<div class="form-group">
								<label class="control-label" for="vnum">vNum</label>
								<input class="form-control" name="vnum" id="vnum" type="number">
							</div>
							
							<div class="form-group">
								<label class="control-label" for="count"><?php print $lang['objects_number']; ?></label>
								<input class="form-control" name="count" id="count" type="number" value="1">
							</div>
							
							<div class="form-group">
								<label class="control-label" for="price"><?php print $lang['price_object']; ?></label>
								
								<div class="row">
									<div class="col-md-10">
										<select class="form-control" name="method_pay">
											<option value="1">MD</option>
											<option value="2">JD</option>
										  </select>
									</div>
									<div class="col-md-2">
										<input class="form-control" name="coins" id="coins" type="number">
									</div>
								</div>
							</div>
							
							<div class="form-group">
								<label class="control-label" for="description"><?php print $lang['description']; ?></label>
								<textarea class="form-control" rows="3" name="description" id="description"></textarea>
							</div>
							
							<div class="form-group">
								<label class="control-label"><?php print $lang['bonuses']; ?></label>
							</div>
							
							<?php 
								for($i=0;$i<=6;$i++)
								{
							?>
							<div class="form-group">
								<div class="row">
									<div class="col-md-10">
										<select class="form-control" name="attrtype<?php print $i; ?>">
											<option value="0">No</option>
											<?php is_get_bonuses(); ?>
										  </select>
									</div>
									<div class="col-md-2">
										<input class="form-control" name="attrvalue<?php print $i; ?>" type="number" value="0">
									</div>
								</div>
							</div>
							<?php
								}
							?>
							<?php if(check_item_column("applytype0")) { ?>
							<div class="form-group">					
								<a class="btn btn-primary" role="button" data-toggle="collapse" href="#sash" aria-expanded="false" aria-controls="sash">
									<?php print $lang['more_bonuses']; ?>
								</a>
								<div class="collapse" id="sash">
									<div class="form-group">
										<label class="control-label" for="absorption"><?php print $lang['bonus_absorption']; ?></label>
										<input class="form-control" name="absorption" id="absorption" type="number" value="18">
									</div>
									<div class="form-group">
										<label class="control-label"><?php print $lang['bonuses']; ?></label>
									</div>
									<?php 
										for($i=0;$i<=7;$i++)
										{
									?>
									<div class="form-group">
										<div class="row">
											<div class="col-md-10">
												<select class="form-control" name="applytype<?php print $i; ?>">
													<option value="0">No</option>
													<?php is_get_bonuses(); ?>
												</select>
											</div>
											<div class="col-md-2">
												<input class="form-control" name="applyvalue<?php print $i; ?>" type="number" value="0">
											</div>
										</div>
									</div>
									<?php
										}
									?>
								</div>
							</div>
							
							<?php } ?>
							<div class="form-group">					
								<a class="btn btn-primary" role="button" data-toggle="collapse" href="#sockets" aria-expanded="false" aria-controls="sockets">
									Sockets
								</a>
								<div class="collapse" id="sockets">
									<div class="form-group">
										<label class="control-label" for="socket0">Socket (1)</label>
										<input class="form-control" name="socket0" id="socket0" type="number" value="">
									</div>
									<div class="form-group">
										<label class="control-label" for="socket1">Socket (2)</label>
										<input class="form-control" name="socket1" id="socket1" type="number" value="">
									</div>
									<div class="form-group">
										<label class="control-label" for="socket2">Socket (3)</label>
										<input class="form-control" name="socket2" id="socket2" type="number" value="">
									</div>
								</div>
							</div>
							<div class="form-group">					
								<a class="btn btn-primary" role="button" data-toggle="collapse" href="#time" aria-expanded="false" aria-controls="time">
									<?php print $lang['item_time']; ?> (Min.)
								</a>
								<div class="collapse" id="time">

								
									<div class="form-group">
										<label class="control-label" for="time"><?php print $lang['item_time']; ?></label>
										<input class="form-control" name="time" id="time" type="number" value="0">
									</div>
								</div>
							</div>
							<div class="form-group">					
								<a class="btn btn-primary" role="button" data-toggle="collapse" href="#time2" aria-expanded="false" aria-controls="time2">
									<?php print $lang['item_time'].' - '.$lang['costumes']; ?> (Min.)
								</a>
								<div class="collapse" id="time2">
									<div class="form-group">
										<label class="control-label" for="time2"><?php print $lang['item_time']; ?> (Costume)</label>
										<input class="form-control" name="time2" id="time2" type="number" value="0">
									</div>
								</div>
							</div>
							<div class="form-group">
								<input class="btn btn-success btn-block" name="add" value="<?php print $lang['is_add_items']; ?>" type="submit">
							</div>
						</div>
						<div class="col-md-2"></div>
					</div>
				</form>

			</div>