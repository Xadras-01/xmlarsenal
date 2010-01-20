<?php

/**
 * languageselect.php
 * 
 * The script sets cookies and language variables in case the current language is changed.
 *
 * @author Amras Taralom <amras-taralom@streber24.de>
 * @version 1.0, last modified 2009/10-10
 * @package XMLArsenal
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 *
*/

require_once './includes/autoload.inc.php';
require_once './includes/config.inc.php';
require_once './includes/db.inc.php';
require_once './includes/data.inc.php';
require_once './includes/functions.inc.php';

$requestedFile = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$parameters = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);

if($_GET['query']){
	
	switch($_GET['query']){
		
		//set 30 days language select cookie
		case 'locale=en_us'	: setcookie('cookieLangId', 'en_us', time()+60*60*24*30); break;
		case 'locale=en_gb'	: setcookie('cookieLangId', 'en_gb', time()+60*60*24*30); break;
		case 'locale=fr_fr'	: setcookie('cookieLangId', 'fr_fr', time()+60*60*24*30); break;
		case 'locale=es_es'	: setcookie('cookieLangId', 'es_es', time()+60*60*24*30); break;
		case 'locale=es_mx'	: setcookie('cookieLangId', 'es_mx', time()+60*60*24*30); break;
		case 'locale=ko_kr'	: setcookie('cookieLangId', 'ko_kr', time()+60*60*24*30); break;
		case 'locale=ru_ru'	: setcookie('cookieLangId', 'ru_ru', time()+60*60*24*30); break;
		default				: setcookie('cookieLangId', 'de_de', time()+60*60*24*30); break;
		
	}//switch
}//if

if(stristr($parameters, '&')){

	$parameters = explode("&", $parameters);
	array_shift($parameters);
	$parameters = implode("&", $parameters);
	
	header("Location: $requestedFile?$parameters");

}else{

	header("Location: $requestedFile");
	
}

?>