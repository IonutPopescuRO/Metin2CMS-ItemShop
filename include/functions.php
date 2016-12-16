<?php
include 'config.php';
include 'get_item_image.php';

try {
	$account = new PDO("mysql:host=$host;dbname=account", $user, $password);
} catch(PDOException $e) {
	die("The Connection to the database of game is not available.");
}
	 
try {
	$player = new PDO("mysql:host=$host;dbname=player", $user, $password);
} catch(PDOException $e) {
	die("The Connection to the database of game is not available.");
}	 
try {
	$sqlite = new PDO("sqlite:include/site.db");
	$sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
	die("The Connection to the database of market is not available.");
}

function redirect($url) {
    if(!headers_sent()) {
        header('Location: '.$url);
        exit;
    } else {
        echo '<script type="text/javascript">';
        echo 'window.location.href="'.$url.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
        echo '</noscript>';
        exit;
    }
}

function login($uname,$upass,$shop=0)
{
	global $account;
	global $lang;
		
	$stmt = $account->prepare("SELECT id, login, password, status FROM account WHERE login=:uname AND password=:upass LIMIT 1");
	$stmt->execute(array(':uname'=>$uname, ':upass'=>strtoupper("*".sha1(sha1($upass, true)))));
	
	$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
	if($stmt->rowCount() > 0)
	{
		if($userRow['status']=='OK')
		{
			$_SESSION['id'] = $userRow['id'];
			$_SESSION['fingerprint'] = md5($_SERVER['HTTP_USER_AGENT'] . 'x' . $_SERVER['REMOTE_ADDR']);
			redirect("index.php?p=home");
			return true;
		} else {
            print '<div class="alert alert-dismissible alert-warning">
					<button type="button" class="close" data-dismiss="alert">×</button>
					'.$lang['blocked_account'].'
				</div>';
			return false;
		}
	}
	else
	{
		return false;
	}
}

function is_loggedin()
{
	if(isset($_SESSION['id']))
		return true;
}

function fingerprint()
{
	if(is_loggedin())
		if ($_SESSION['fingerprint'] != md5($_SERVER['HTTP_USER_AGENT'] . 'x' . $_SERVER['REMOTE_ADDR']))
			session_destroy();
}

function redirect_shop($url)
{
	global $minim_web_admin_level;
	
	if ($url=='coins' && !is_loggedin())
		redirect("index.php?p=login");
	
	if($url=='login' && is_loggedin())
		redirect("index.php?p=home");
	
	if(($url=='categories' || $url=='add_items' || $url=='paypal') && (!is_loggedin() || web_admin_level()<$minim_web_admin_level))
		redirect("index.php?p=home");
}

function logout_shop()
{
	session_destroy();
	unset($_SESSION['id']);
	redirect("index.php?p=login");
}

function get_account_name()
{
	
	global $account;
	
	$sth = $account->prepare('SELECT login
		FROM account
		WHERE id = ? LIMIT 1');
	$sth->bindParam(1, $_SESSION['id'], PDO::PARAM_INT);
	$sth->execute();
	$result = $sth->fetchAll();
	
	return $result[0]['login'];
}

function check_item_column($name)
{
	
	global $player;
	
	$sth = $player->prepare("DESCRIBE item");
	$sth->execute();
	$columns = $sth->fetchAll(PDO::FETCH_COLUMN);
	
	if(in_array($name, $columns))
		return true;
	else return false;
}

function char_big_lvl()
{
	global $player;
	
	$sth = $player->prepare('SELECT name, job, level, exp
		FROM player
		WHERE account_id = ? ORDER BY level DESC, exp DESC LIMIT 1');
	$sth->bindParam(1, $_SESSION['id'], PDO::PARAM_INT);
	$sth->execute();
	$result = $sth->fetchAll();
	
	if(isset($result[0]['job']))
		print $result[0]['job'];
	else print 0;

}

function getItemSize($code) {
	global $sqlite;

	$sth = $sqlite->prepare('SELECT size
		FROM items_details
		WHERE id = ?');
	$sth->bindParam(1, $code, PDO::PARAM_INT);
	$sth->execute();
	$result = $sth->fetchAll();
	if(isset($result[0]['size']))
		return $result[0]['size'];
	else return 1;
}

function new_item_position($new_item)
{
	global $player;
		
	$sth = $player->prepare('SELECT pos, vnum
		FROM item
		WHERE owner_id=? AND window="MALL" ORDER by pos ASC');
	$sth->bindParam(1, $_SESSION['id'], PDO::PARAM_INT);
	$sth->execute();
	$result = $sth->fetchAll();
	
	$used = $items_used = $used_check = array();
	
	foreach( $result as $row ) {
		$used_check[] = $row['pos'];
		$used[$row['pos']] = 1;
		$items_used[$row['pos']] = $row['vnum'];
	}
	$used_check = array_unique($used_check);

	$free = -1;
	
	for($i=0; $i<45; $i++){
		if(!in_array($i,$used_check)){
			$ok = true;
			
			if($i>4 && $i<10)
			{
				if(array_key_exists($i-5, $used) && getItemSize($items_used[$i-5])>1)
					$ok = false;
			}
			else if($i>9 && $i<40)
			{
				if(array_key_exists($i-5, $used) && getItemSize($items_used[$i-5])>1)
					$ok = false;
				
				if(array_key_exists($i-10, $used) && getItemSize($items_used[$i-10])>2)
					$ok = false;
			}
			else if($i>39 && $i<45 && getItemSize($new_item)>1)
					$ok = false;
			
			if($ok)
				return $i;
		}
	}
	
	return $free;
}

function check_item_sash($id)
{
	if($id > 85000 && $id < 90000)
		return true;
	else return false;
}

function get_item_name($id)
{
	global $sqlite;
	global $language_code;
	
	$sth = $sqlite->prepare('SELECT '.$language_code.'
		FROM items_names
		WHERE id = ? LIMIT 1');
	$sth->bindParam(1, $id, PDO::PARAM_INT);
	$sth->execute();
	$result = $sth->fetchAll();
	
	if(isset($result[0][$language_code]))
		return $result[0][$language_code];
	else return 'No name';
}

function return_item_name($id)
{
	global $sqlite;
	global $language_code;
	
	$sth = $sqlite->prepare('SELECT '.$language_code.'
		FROM items_names
		WHERE id = ? LIMIT 1');
	$sth->bindParam(1, $id, PDO::PARAM_INT);
	$sth->execute();
	$result = $sth->fetchAll();
	
	return $result[0][$language_code];
}

function get_bonus_name($id, $value)
{
	global $sqlite;
	global $language_code;
	
	$sth = $sqlite->prepare('SELECT '.$language_code.'
		FROM items_bonuses
		WHERE id = ? LIMIT 1');
	$sth->bindParam(1, $id, PDO::PARAM_INT);
	$sth->execute();
	$result = $sth->fetchAll();
	
	return str_replace("[n]", '<font color="red"><b>'.$value.'</b></font>', $result[0][$language_code]);
}

function get_item_type($id)
{
	global $sqlite;
	
	$sth = $sqlite->prepare('SELECT type
		FROM items_details
		WHERE id = ? LIMIT 1');
	$sth->bindParam(1, $id, PDO::PARAM_INT);
	$sth->execute();
	$result = $sth->fetchAll();
	
	if(isset($result[0]['type']))
		return $result[0]['type'];
	else return 'NOT_FOUND';
}

function get_item_lvl($id)
{
	global $sqlite;
	
	$sth = $sqlite->prepare('SELECT lvl
		FROM items_details
		WHERE id = ? LIMIT 1');
	$sth->bindParam(1, $id, PDO::PARAM_INT);
	$sth->execute();
	$result = $sth->fetchAll();
	if(isset($result[0]['lvl']) && $result[0]['lvl']<=105)
		return $result[0]['lvl'];
	else return 0;
}

function web_admin_level()
{
	global $account;
	
	$sth = $account->prepare('SELECT web_admin
		FROM account
		WHERE id = ?');
	$sth->bindParam(1, $_SESSION['id'], PDO::PARAM_INT);
	$sth->execute();
	$result = $sth->fetchAll();
	
	return $result[0]['web_admin'];
}

//Functions for item-shop

function is_categories_list()
{
	global $sqlite;
	
	$sth = $sqlite->prepare('SELECT *
		FROM item_shop_categories
		ORDER BY id ASC');
	$sth->execute();
	$result = $sth->fetchAll();
	
	return $result;
}

function is_coins($type=0)
{
	global $account;
	
	$sth = $account->prepare('SELECT coins, jcoins
		FROM account
		WHERE id = ?');
	$sth->bindParam(1, $_SESSION['id'], PDO::PARAM_INT);
	$sth->execute();
	$result = $sth->fetchAll();
	
	if(!$type)
		return $result[0]['coins'];
	else
		return $result[0]['jcoins'];
}

function is_get_category_name($category)
{
	global $sqlite;
	
	$sth = $sqlite->prepare('SELECT name
		FROM item_shop_categories
		WHERE id = ?');
	$sth->bindParam(1, $category, PDO::PARAM_INT);
	$sth->execute();
	$result = $sth->fetchAll();
	
	return $result[0]['name'];
}

function is_check_category($category)
{
	global $sqlite;
	
	$sth = $sqlite->prepare('SELECT id
		FROM item_shop_categories
		WHERE id = ?');
	$sth->bindParam(1, $category, PDO::PARAM_INT);
	$sth->execute();
	$result = $sth->fetchAll();
	
	if(count($result))
		return 1;
	else return 0;
}

function is_check_item($id)
{
	global $sqlite;
	
	$sth = $sqlite->prepare('SELECT id
		FROM item_shop_items
		WHERE id = ?');
	$sth->bindParam(1, $id, PDO::PARAM_INT);
	$sth->execute();
	$result = $sth->fetchAll();
	
	if(count($result))
		return 1;
	else return 0;
}

function is_item_select($id)
{
	global $sqlite;
	
	$sth = $sqlite->prepare('SELECT id, category, description, pay_type, coins, count, vnum, socket0
		FROM item_shop_items
		WHERE id = ?');
	$sth->bindParam(1, $id, PDO::PARAM_INT);
	$sth->execute();
	$result = $sth->fetchAll();
	
	return $result;
}

function is_items_list($category)
{
	global $sqlite;
	
	$sth = $sqlite->prepare('SELECT id, pay_type, coins, vnum
		FROM item_shop_items
		WHERE category = ? ORDER BY id ASC');
	$sth->bindParam(1, $category, PDO::PARAM_INT);
	$sth->execute();
	$result = $sth->fetchAll();
	
	return $result;
}

function is_edit_category($id, $name, $img)
{
	global $sqlite;
	
	$stmt = $sqlite->prepare("UPDATE item_shop_categories set name = ?, img = ? WHERE id=?");
	$stmt->bindParam(1, $name, PDO::PARAM_STR);
	$stmt->bindParam(2, $img, PDO::PARAM_INT);
	$stmt->bindParam(3, $id, PDO::PARAM_INT);
	$stmt->execute();
}

function is_add_category($name, $img)
{
	global $sqlite;
	
	$stmt = $sqlite->prepare("INSERT INTO item_shop_categories (name, img) VALUES (?, ?)");
	$stmt->bindParam(1, $name, PDO::PARAM_STR);
	$stmt->bindParam(2, $img, PDO::PARAM_INT);
	$stmt->execute();
}

function is_delete_category($id)
{
	global $sqlite;
	
	$sth = $sqlite->prepare("DELETE FROM item_shop_categories WHERE id = ?");
	$sth->bindParam(1, $id, PDO::PARAM_INT);
	$sth->execute();
}

function is_get_bonuses()
{
	global $sqlite;
	global $language_code;
	
	$sth = $sqlite->prepare('SELECT '.$language_code.', id
		FROM items_bonuses');
	$sth->execute();
	$result = $sth->fetchAll();
	
	foreach( $result as $row ) {
		print '<option value='.$row['id'].'>'.str_replace("[n]", 'XXX', $row[$language_code]).'</option>';
	}
}

function is_get_item($id)
{
	global $sqlite;
	
	$sth = $sqlite->prepare('SELECT attrtype0, attrvalue0, attrtype1, attrvalue1,
		attrtype2, attrvalue2, attrtype3, attrvalue3, attrtype4, attrvalue4,
		attrtype5, attrvalue5, attrtype6, attrvalue6
		FROM item_shop_items
		WHERE id = ?');
	$sth->bindParam(1, $id, PDO::PARAM_INT);
	$sth->execute();
	$result = $sth->fetchAll();
	
	for($i=0;$i<=6;$i++)
		if($result[0]['attrtype'.$i])
		{
			print '<p>';
			print get_bonus_name($result[0]['attrtype'.$i], $result[0]['attrvalue'.$i]);
			print '</p>';
		}
}

function is_get_sash_bonuses($id)
{
	global $sqlite;
	
	$sth = $sqlite->prepare('SELECT applytype0, applyvalue0, applytype1, applyvalue1,
		applytype2, applyvalue2, applytype3, applyvalue3, applytype4, applyvalue4,
		applytype5, applyvalue5, applytype6, applyvalue6, applytype7, applyvalue7
		FROM item_shop_items
		WHERE id = ?');
	$sth->bindParam(1, $id, PDO::PARAM_INT);
	$sth->execute();
	$result = $sth->fetchAll();

	$a=$m=0;
	
	for($i=0;$i<=7;$i++)
		if($result[0]['applytype'.$i])
		{
			if($result[0]['applytype'.$i]==53 && !$a)
			{
				print '<p>';
				print str_replace('+', '', get_bonus_name($result[0]['applytype'.$i], $result[0]['applyvalue'.$i]));
				$a++;
			}
			else if($result[0]['applytype'.$i]==53 && $a)
			{
				print ' - <font color="red"><b>'.$result[0]['applyvalue'.$i].'</b></font>';
				print '<p>';
			}
			else if($result[0]['applytype'.$i]==55 && !$m)
			{
				print '<p>';
				print str_replace('+', '', get_bonus_name($result[0]['applytype'.$i], $result[0]['applyvalue'.$i]));
				$m++;
			}
			else if($result[0]['applytype'.$i]==55 && $m)
			{
				print ' - <font color="red"><b>'.$result[0]['applyvalue'.$i].'</b></font>';
				print '<p>';
			}
			else
			{
				print '<p>';
				print get_bonus_name($result[0]['applytype'.$i], $result[0]['applyvalue'.$i]);
				print '</p>';
			}
		}
}

function license()
{
	global $license;
	
	$license_verify = isset($_GET['license']) ? $_GET['license'] : null;
	if($license_verify)
		if($license_verify==$license)
		{
			print "license_ok";
			die();
		}
}

function is_get_sash_absorption($id)
{
	global $sqlite;
	
	$sth = $sqlite->prepare('SELECT socket1
		FROM item_shop_items
		WHERE id = ?');
	$sth->bindParam(1, $id, PDO::PARAM_INT);
	$sth->execute();
	$result = $sth->fetchAll();

	return $result[0]['socket1'];
}

function is_get_item_time($id)
{
	global $sqlite, $lang;
	
	$sth = $sqlite->prepare('SELECT socket0, socket1, socket2
		FROM item_shop_items
		WHERE id = ?');
	$sth->bindParam(1, $id, PDO::PARAM_INT);
	$sth->execute();
	$result = $sth->fetchAll();

	for($i=0;$i<=2;$i++)
		if($result[0]['socket'.$i])
			{
				$h = floor($result[0]['socket'.$i] / 60);
				$m = ($result[0]['socket'.$i] % 60);
				if($h)
					print $h.' '.$lang['hours'].' ';
				if($m)
				{
					if($h) print '& ';
					print $m.' '.$lang['minutes'];
				}
			}
}

function is_buy_item($id)
{
	global $player;
	global $sqlite;
	
	$sth = $sqlite->prepare('SELECT *
		FROM item_shop_items
		WHERE id = ?');
	$sth->bindParam(1, $id, PDO::PARAM_INT);
	$sth->execute();
	$result = $sth->fetchAll();
		
	$item_position = new_item_position($result[0]['vnum']);
	
	if($item_position == -1)
		return false;
	
	if(check_item_column("applytype0"))
	{
		if($result[0]['socket0'])
		{
			$time_costume = time() + 60 * intval($result[0]['socket0']);
			$stmt = $player->prepare('INSERT INTO item (owner_id, window, pos, count, vnum, socket0, socket1, socket2, attrtype0, attrvalue0, attrtype1 , attrvalue1, attrtype2, attrvalue2, attrtype3, attrvalue3, attrtype4, attrvalue4, attrtype5, attrvalue5, attrtype6, attrvalue6, applytype0, applyvalue0, applytype1, applyvalue1, applytype2, applyvalue2, applytype3, applyvalue3, applytype4, applyvalue4, applytype5, applyvalue5, applytype6, applyvalue6, applytype7, applyvalue7) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
			if($stmt->execute(array($_SESSION['id'], "MALL", $item_position, $result[0]['count'], $result[0]['vnum'], $time_costume, $result[0]['socket1'], $result[0]['socket2'],
								$result[0]['attrtype0'], $result[0]['attrvalue0'], $result[0]['attrtype1'], $result[0]['attrvalue1'], $result[0]['attrtype2'], $result[0]['attrvalue2'], 
								$result[0]['attrtype3'], $result[0]['attrvalue3'], $result[0]['attrtype4'], $result[0]['attrvalue4'], $result[0]['attrtype5'], $result[0]['attrvalue5'], 
								$result[0]['attrtype6'], $result[0]['attrvalue6'], 
								$result[0]['applytype0'], $result[0]['applyvalue0'], $result[0]['applytype1'], $result[0]['applyvalue1'], $result[0]['applytype2'], $result[0]['applyvalue2'], 
								$result[0]['applytype3'], $result[0]['applyvalue3'], $result[0]['applytype4'], $result[0]['applyvalue4'], $result[0]['applytype5'], $result[0]['applyvalue5'], 
								$result[0]['applytype6'], $result[0]['applyvalue6'], $result[0]['applytype7'], $result[0]['applyvalue7'])))
								return true;
		} else {
			$stmt = $player->prepare('INSERT INTO item (owner_id, window, pos, count, vnum, socket0, socket1, socket2, attrtype0, attrvalue0, attrtype1 , attrvalue1, attrtype2, attrvalue2, attrtype3, attrvalue3, attrtype4, attrvalue4, attrtype5, attrvalue5, attrtype6, attrvalue6, applytype0, applyvalue0, applytype1, applyvalue1, applytype2, applyvalue2, applytype3, applyvalue3, applytype4, applyvalue4, applytype5, applyvalue5, applytype6, applyvalue6, applytype7, applyvalue7) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
			if($stmt->execute(array($_SESSION['id'], "MALL", $item_position, $result[0]['count'], $result[0]['vnum'], $result[0]['socket0'], $result[0]['socket1'], $result[0]['socket2'],
								$result[0]['attrtype0'], $result[0]['attrvalue0'], $result[0]['attrtype1'], $result[0]['attrvalue1'], $result[0]['attrtype2'], $result[0]['attrvalue2'], 
								$result[0]['attrtype3'], $result[0]['attrvalue3'], $result[0]['attrtype4'], $result[0]['attrvalue4'], $result[0]['attrtype5'], $result[0]['attrvalue5'], 
								$result[0]['attrtype6'], $result[0]['attrvalue6'], 
								$result[0]['applytype0'], $result[0]['applyvalue0'], $result[0]['applytype1'], $result[0]['applyvalue1'], $result[0]['applytype2'], $result[0]['applyvalue2'], 
								$result[0]['applytype3'], $result[0]['applyvalue3'], $result[0]['applytype4'], $result[0]['applyvalue4'], $result[0]['applytype5'], $result[0]['applyvalue5'], 
								$result[0]['applytype6'], $result[0]['applyvalue6'], $result[0]['applytype7'], $result[0]['applyvalue7'])))
								return true;
		}
	}
	else
	{
		if($result[0]['socket0'])
		{
			$time_costume = time() + 60 * intval($result[0]['socket0']);
			$stmt = $player->prepare('INSERT INTO item (owner_id, window, pos, count, vnum, socket0, socket1, socket2, attrtype0, attrvalue0, attrtype1 , attrvalue1, attrtype2, attrvalue2, attrtype3, attrvalue3, attrtype4, attrvalue4, attrtype5, attrvalue5, attrtype6, attrvalue6) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
			if($stmt->execute(array($_SESSION['id'], "MALL", $item_position, $result[0]['count'], $result[0]['vnum'], $time_costume, $result[0]['socket1'], $result[0]['socket2'],
								$result[0]['attrtype0'], $result[0]['attrvalue0'], $result[0]['attrtype1'], $result[0]['attrvalue1'], $result[0]['attrtype2'], $result[0]['attrvalue2'], 
								$result[0]['attrtype3'], $result[0]['attrvalue3'], $result[0]['attrtype4'], $result[0]['attrvalue4'], $result[0]['attrtype5'], $result[0]['attrvalue5'], 
								$result[0]['attrtype6'], $result[0]['attrvalue6'])))
								return true;
		} else {
			$stmt = $player->prepare('INSERT INTO item (owner_id, window, pos, count, vnum, socket0, socket1, socket2, attrtype0, attrvalue0, attrtype1 , attrvalue1, attrtype2, attrvalue2, attrtype3, attrvalue3, attrtype4, attrvalue4, attrtype5, attrvalue5, attrtype6, attrvalue6) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
			if($stmt->execute(array($_SESSION['id'], "MALL", $item_position, $result[0]['count'], $result[0]['vnum'], $result[0]['socket0'], $result[0]['socket1'], $result[0]['socket2'],
								$result[0]['attrtype0'], $result[0]['attrvalue0'], $result[0]['attrtype1'], $result[0]['attrvalue1'], $result[0]['attrtype2'], $result[0]['attrvalue2'], 
								$result[0]['attrtype3'], $result[0]['attrvalue3'], $result[0]['attrtype4'], $result[0]['attrvalue4'], $result[0]['attrtype5'], $result[0]['attrvalue5'], 
								$result[0]['attrtype6'], $result[0]['attrvalue6'])))
								return true;
		}

	}
	return false;
}

function is_pay_coins($type, $coins)
{
	global $account;
	
	$sth = $account->prepare('SELECT coins, jcoins
		FROM account
		WHERE id = ? LIMIT 1');
	$sth->bindParam(1, $_SESSION['id'], PDO::PARAM_INT);
	$sth->execute();
	$result = $sth->fetchAll();
	
	if(!$type)
		$stmt = $account->prepare("UPDATE account set coins = coins - ? WHERE id = ?");
	else
		$stmt = $account->prepare("UPDATE account set jcoins = jcoins - ? WHERE id = ?");
		
	$stmt->bindParam(1, $coins, PDO::PARAM_INT);
	$stmt->bindParam(2, $_SESSION['id'], PDO::PARAM_INT);
	$stmt->execute();
}

function is_delete_item($id)
{
	global $sqlite;
	
	$sth = $sqlite->prepare('DELETE
		FROM item_shop_items
		WHERE id = ?');
	$sth->bindParam(1, $id, PDO::PARAM_INT);
	$sth->execute();
	$result = $sth->fetchAll();
}

function is_edit_paypal($id, $price, $coins)
{
	global $sqlite;
	
	$stmt = $sqlite->prepare("UPDATE paypal set price = ?, coins = ? WHERE id=?");
	$stmt->bindParam(1, $price, PDO::PARAM_STR);
	$stmt->bindParam(2, $coins, PDO::PARAM_INT);
	$stmt->bindParam(3, $id, PDO::PARAM_INT);
	$stmt->execute();
}

function is_delete_paypal($id)
{
	global $sqlite;
	
	$sth = $sqlite->prepare("DELETE FROM paypal WHERE id = ?");
	$sth->bindParam(1, $id, PDO::PARAM_INT);
	$sth->execute();
}

function is_add_paypal($price, $coins)
{
	global $sqlite;
	
	$stmt = $sqlite->prepare("INSERT INTO paypal (price, coins) VALUES (?, ?)");
	$stmt->bindParam(1, $price, PDO::PARAM_STR);
	$stmt->bindParam(2, $coins, PDO::PARAM_INT);
	$stmt->execute();
}

function is_paypal_list()
{
	global $sqlite;
	
	$sth = $sqlite->prepare('SELECT *
		FROM paypal
		ORDER BY id ASC');
	$sth->execute();
	$result = $sth->fetchAll();
	
	return $result;
}

function is_check_paypal($id)
{
	global $sqlite;
	
	$sth = $sqlite->prepare('SELECT id
		FROM paypal
		WHERE id = ?');
	$sth->bindParam(1, $id, PDO::PARAM_INT);
	$sth->execute();
	$result = $sth->fetchAll();
	
	if(count($result))
		return 1;
	else return 0;
}

function is_get_price($id)
{
	global $sqlite;
	
	$sth = $sqlite->prepare('SELECT price
		FROM paypal
		WHERE id = ?');
	$sth->bindParam(1, $id, PDO::PARAM_INT);
	$sth->execute();
	$result = $sth->fetchAll();
	
	return $result[0]['price'];
}

function is_get_coins($id)
{
	global $sqlite;
	
	$sth = $sqlite->prepare('SELECT coins
		FROM paypal
		WHERE id = ?');
	$sth->bindParam(1, $id, PDO::PARAM_INT);
	$sth->execute();
	$result = $sth->fetchAll();
	
	return $result[0]['coins'];
}


function check_txnid_paypal($tnxid)
{
	global $sqlite;
	
	$sth = $sqlite->prepare('SELECT id
		FROM payments
		WHERE txnid = ?');
	$sth->bindParam(1, $tnxid, PDO::PARAM_INT);
	$sth->execute();
	$result = $sth->fetchAll();
	
	if(count($result))
		return 0;
	else return 1;
}

function check_price_paypal($price, $id)
{
	global $sqlite;
	
	$sth = $sqlite->prepare('SELECT price
		FROM paypal
		WHERE id = ? LIMIT 1');
	$sth->bindParam(1, $id, PDO::PARAM_INT);
	$sth->execute();
	$result = $sth->fetchAll();
	if(count($result))
		if(floatval($price)==$result[0]['price'])
			return 1;
	return 0;
}

function updatePayments($data){
	global $sqlite;
	
	if (is_array($data)) {
		$stmt = $sqlite->prepare('INSERT INTO payments (txnid, payment_amount, payment_status, itemid, createdtime) VALUES (?,?,?,?,?)');
		$stmt->execute(array($data['txn_id'], $data['payment_amount'], $data['payment_status'], $data['item_number'], date("Y-m-d H:i:s")));
	}
}

function get_coins_paypal($id_account, $id_paypal)
{
	global $sqlite;
	global $account;
	
	$sth = $sqlite->prepare('SELECT coins
		FROM paypal
		WHERE id = ? LIMIT 1');
	$sth->bindParam(1, $id_paypal, PDO::PARAM_INT);
	$sth->execute();
	$result = $sth->fetchAll();

	$stmt = $account->prepare("UPDATE account SET coins = coins + ? WHERE id = ?");
	$stmt->bindParam(1, $result[0]['coins'], PDO::PARAM_INT);
	$stmt->bindParam(2, $id_account, PDO::PARAM_INT);
	$stmt->execute();
}