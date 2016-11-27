<?php
	if(isSet($_GET['lang']))
	{
		$lang = $_GET['lang'];
		$_SESSION['lang'] = $lang;
		setcookie('lang', $lang, time() + (3600 * 24 * 30));
	}
	else if(isSet($_SESSION['lang']))
	{
		$lang = $_SESSION['lang'];
	}
	else if(isSet($_COOKIE['lang']))
	{
		$lang = $_COOKIE['lang'];
	}
	else
		$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	
	switch ($lang) {
		case 'en':
		$language_code = "en";
		break;
	 
		case 'ro':
		$language_code = "ro";
		break;
		
		case 'de':
		$language_code = "de";
		break;
		
		case 'es':
		$language_code = "es";
		break;
		
		case 'fr':
		$language_code = "fr";
		break;
		
		case 'it':
		$language_code = "it";
		break;
		
		case 'pt':
		$language_code = "pt";
		break;
		
		case 'tr':
		$language_code = "tr";
		break;
	 
		default:
		$language_code = "ro";
	}
	include 'include/languages/'.$language_code.'.php';
	
	
	$language_codes = array(
			'en' => 'English' , 
			'ro' => 'Română' , 
			'fr' => 'Français' , 
			'it' => 'Italiano' , 
			'pt' => 'Português' , 
			'tr' => 'Türk' , 
			'de' => 'Deutsch' , 
			'es' => 'Español');
?>