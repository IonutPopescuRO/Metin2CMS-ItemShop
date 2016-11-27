<?php
	if(isset($_POST['username'], $_POST['password'])) {
		if(login($_POST['username'], $_POST['password'], 1)) {
			print '<div class="alert alert-dismissible alert-success">
					<button type="button" class="close" data-dismiss="alert">×</button>
					'.$lang['login_success'].'
				</div>';
		}
		else {
            print '<div class="alert alert-dismissible alert-danger">
					<button type="button" class="close" data-dismiss="alert">×</button>
					'.$lang['login_fail'].'
				</div>';
		}
	}
?>
		<div style="padding-top: 70px;"></div>
    	<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-login">
					<div class="panel-heading"><center><h3><?php print $lang['login']; ?></h3></center>
						<hr>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
								<form id="login-form" action="" method="post" role="form" style="display: block;">
									<div class="form-group">
										<input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="<?php print $lang['name_login']; ?>" value="">
									</div>
									<div class="form-group">
										<input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="<?php print $lang['password']; ?>">
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<input type="submit" name="login" id="login" class="btn btn-success btn-lg btn-block" value="<?php print $lang['login2']; ?>">
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div style="padding-bottom: 22px;"></div>
