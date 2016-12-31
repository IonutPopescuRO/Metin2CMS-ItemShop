<?php
	$current_page = isset($_GET['p']) ? $_GET['p'] : null;
	
	if($current_page=='items' || $current_page=='add_items')
		$get_category = isset($_GET['category']) ? $_GET['category'] : 1;
	
	if($current_page=='item' || $current_page=='buy')
	{
		$get_item = isset($_GET['id']) ? $_GET['id'] : 1;
		
		$item = array();
		$item = is_item_select($get_item);
		
		if($current_page=='buy' && is_coins($item[0]['pay_type']-1)<$item[0]['coins'])
			redirect("index.php?p=items&category=".$item[0]['category']);
	}
	
	if($current_page=='items' && is_loggedin() && web_admin_level()>=$minim_web_admin_level)
	{
		$remove = isset($_GET['remove']) ? $_GET['remove'] : 1;
		if($remove)
			is_delete_item($remove);
	}
	
	if(($current_page=='items' || $current_page=='add_items') && !is_check_category($get_category))
		redirect("index.php");

	if(($current_page=='item' || $current_page=='buy') && !is_check_item($get_item))
		redirect("index.php");
	
	redirect_shop($current_page);
	
	license();
	
	if($current_page=='coins')
	{	
		$list = array();
		$list = is_paypal_list();
		
		if(isset($_POST["id"]))
		{
			if(is_check_paypal($_POST["id"]))
			{
				$return_url = $site_url."index.php?p=coins&m=success";
				$cancel_url = $site_url."index.php?p=coins&m=cancelled";
				$notify_url = $site_url."index.php?p=pay";
				
				$querystring = '';
				$querystring .= "?business=".urlencode($paypal_email)."&";
				
				$item_name = is_get_coins($_POST["id"]). ' MD';
				$querystring .= "item_name=".urlencode($item_name)."&";
				$querystring .= "amount=".urlencode(is_get_price($_POST["id"]))."&";
				
				$querystring .= "cmd=".urlencode(stripslashes("_xclick"))."&";
				$querystring .= "no_note=".urlencode(stripslashes("1"))."&";
				$querystring .= "currency_code=".urlencode(stripslashes("EUR"))."&";
				$querystring .= "bn=".urlencode(stripslashes("PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest"))."&";
				$querystring .= "first_name=".urlencode(stripslashes(get_account_name()))."&";
				
				$querystring .= "return=".urlencode(stripslashes($return_url))."&";
				$querystring .= "cancel_return=".urlencode(stripslashes($cancel_url))."&";
				$querystring .= "notify_url=".urlencode($notify_url)."&";
				$querystring .= "item_number=".urlencode($_POST["id"])."&";
				$querystring .= "custom=".urlencode($_SESSION['id']);
				
				//redirect('https://www.sandbox.paypal.com/cgi-bin/webscr'.$querystring);
				redirect('https://www.paypal.com/cgi-bin/webscr'.$querystring);
				exit();
			}
		}
	}

?>