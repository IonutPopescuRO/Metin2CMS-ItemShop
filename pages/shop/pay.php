<?php
	if (isset($_POST["txn_id"]) && isset($_POST["txn_type"]))
	{
		$req = 'cmd=_notify-validate';
		foreach ($_POST as $key => $value) {
			$value = urlencode(stripslashes($value));
			$value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i','${1}%0D%0A${3}',$value);// IPN fix
			$req .= "&$key=$value";
		}
		
		$data['item_name']			= $_POST['item_name'];
		$data['item_number'] 		= $_POST['item_number'];
		$data['payment_status'] 	= $_POST['payment_status'];
		$data['payment_amount'] 	= $_POST['mc_gross'];
		$data['payment_currency']	= $_POST['mc_currency'];
		$data['txn_id']				= $_POST['txn_id'];
		$data['receiver_email'] 	= $_POST['receiver_email'];
		$data['payer_email'] 		= $_POST['payer_email'];
		$data['custom'] 			= $_POST['custom'];

		$curl_result=$curl_err='';
		$ch = curl_init();
		//curl_setopt($ch, CURLOPT_URL,'https://www.sandbox.paypal.com/cgi-bin/webscr'); - DevMode
		curl_setopt($ch, CURLOPT_URL,'https://www.paypal.com/cgi-bin/webscr');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Content-Length: " . strlen($req)));
		curl_setopt($ch, CURLOPT_HEADER , 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $ssl);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);

		$curl_result = curl_exec($ch);
		$curl_err = curl_error($ch);
		curl_close($ch);
		
		if (strpos($curl_result, "VERIFIED")!==false) {
					
			$valid_txnid = check_txnid_paypal($data['txn_id']);
			$valid_price = check_price_paypal($data['payment_amount'], $data['item_number']);
										
			if ($valid_txnid && $valid_price) {
				if($data['payment_status']=="Completed" && $data['receiver_email']==$paypal_email)
				{
					updatePayments($data);
					get_coins_paypal($data['custom'], $data['item_number']);
				}

			}
				
		}
	}
?>