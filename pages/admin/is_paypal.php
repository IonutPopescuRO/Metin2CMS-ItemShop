<?php
	$remove = isset($_GET['remove']) ? $_GET['remove'] : null;
	if($remove)
		is_delete_paypal($remove);
	
	if(isset($_POST['edit']))
		is_edit_paypal($_POST['id'], $_POST['price'.$_POST['id']], $_POST['coins'.$_POST['id']]);
		
	if(isset($_POST['add']))
		is_add_paypal($_POST['price'], $_POST['coins']);
?>

<ul class="breadcrumb">
	<li><a href="index.php">Item-Shop</a></li>
	<li class="active"><?php print ucfirst($current_page); ?></li>
</ul>

<div class="panel panel-info">
	<div class="panel-heading">
		<ul class="panel-title nav nav-tabs">
			<li class="active"><a  href="#1" data-toggle="tab"><?php print $lang['is_tab1']; ?></a></li>
			<li><a href="#2" data-toggle="tab"><?php print $lang['is_tab2']; ?></a></li>
		</ul>
	</div>
	<div class="panel-body">


		<div class="tab-content ">
			<div class="tab-pane active" id="1">

				<table class="table table-striped table-hover ">
					<thead>
						<tr>
							<th><?php print ucfirst(mb_strtolower($lang['price'], 'UTF-8')); ?> - &euro;</th>
							<th><?php print $lang['value']; ?> - Coins</th>
							<th>#</th>
						</tr>
					</thead>
					<tbody>				
				<?php						
					$stmt = $sqlite->prepare("SELECT * FROM paypal ORDER BY id ASC");
					$stmt->execute();
						
					$result = $stmt->fetchAll();

		
					if($result && count($result) > 0)
					{
							foreach($result as $key => $row)
							{
				?>
					<form action="" method="post" class="form-horizontal">
						<tr>
							<input type="hidden" name="id" value="<?php print $row['id']; ?>">
							
							<td><input style="max-width: 200px;" class="form-control" name="price<?php print $row['id']; ?>" type="text" value="<?php print $row['price']; ?>"></td>
							
							<td><input style="max-width: 200px;" class="form-control" name="coins<?php print $row['id']; ?>" type="text" value="<?php print $row['coins']; ?>"></td>
							
							<td><input class="btn btn-primary btn-sm" name="edit" value="<?php print $lang['edit']; ?>" type="submit"></td>
							<td><a href="?p=paypal&remove=<?php print $row['id']; ?>" class="btn btn-danger btn-sm"><?php print $lang['item_remove']; ?></a></td>
						</tr>
					</form>
				<?php
							}
					} else print 'Nothing found';
				?>
					</tbody>
				</table> 
			
			</div>
			<div class="tab-pane" id="2">
				<form action="" method="post" class="form-horizontal">
					<div class="row">
						<div class="col-md-3"></div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label" for="focusedInput"><?php print ucfirst(mb_strtolower($lang['price'], 'UTF-8')); ?> - &euro;</label>
								<input class="form-control" name="price" type="number">
							</div>
							<div class="form-group">
								<label class="control-label" for="focusedInput"><?php print $lang['value']; ?> - Coins</label>
								<input class="form-control" name="coins" type="number">
							</div>
							<div class="form-group">
								<input class="btn btn-success btn-block" name="add" value="<?php print $lang['add_category']; ?>" type="submit">
							</div>
						</div>
						<div class="col-md-3"></div>
					</div>
				</form>
			</div>
		</div>



	</div>
</div>