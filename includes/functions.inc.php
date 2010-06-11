<?php

/**
 * functions.inc.php
 * 
 * This file contains several helper functions, additional parsing and output-preps.
 *
 * @author Amras Taralom <amras-taralom@streber24.de>
 * @version 1.0, last modified 2010/06/06
 * @package XMLArsenal
 * @subpackage includes
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 *
*/

//give it some time ;)
@set_time_limit(120);

//set internal encoding to utf-8
iconv_set_encoding("internal_encoding", "UTF-8");

//handling of special chars and "SQL-Injection"-detection
if(!empty($_GET)){
	
	foreach($_GET as $key=>$value){
		
		if(contains_illegal_chars($value)) die('Possible SQL-Injection detected! If this is a false positive please feel free to contact AmrasTaralom.');
		
		if(stripos($value, '%') !== FALSE)	$value = urldecode($value);		//handling of urlencoded values
		if(stripos($value, '+') !== FALSE)	$value = urldecode($value);		//back-convert of the +
		
		$enc = detect_encoding($value);
		if($enc != "utf-8") $_GET[$key] = utf8_encode($value);
		else $_GET[$key] = $value;
		
	}//foreach
	
}//if

//language select and cookie baking
if($_COOKIE['cookieLangId']){

	switch($_COOKIE['cookieLangId']){
		
		case 'en_us': $language = "en_us"; break;
		case 'en_gb': $language = "en_gb"; break;
		case 'fr_fr': $language = "fr_fr"; break;
		case 'es_es': $language = "es_es"; break;
		case 'es_mx': $language = "es_mx"; break;
		case 'ko_kr': $language = "ko_kr"; break;
		case 'ru_ru': $language = "ru_ru"; break;
		default     : $language = "de_de"; break;
		
	}//switch
	
	//renew 30 days language select cookie
	setcookie('cookieLangId', $language, time()+60*60*24*30);
	
}else{
	
	//no language was selected before
	setcookie('cookieLangId', 'de_de', time()+60*60*24*30);
	$language = "de_de";
	
}

//read from arsenal doesn't need es_mx or en_us since they've es_es and en_gb which are the same
$arsenaldataLang = $language;
if($language == "es_mx") $arsenaldataLang = "es_es";
if($language == "en_us") $arsenaldataLang = "en_gb";


//for pear to find all classes needed
set_include_path('./includes/pear');

//file changedates etc are cached... to get right infos delete the cache...
clearstatcache();

//init md5 variable
$md5 = '';

//needed for ff, opera etc.
header('Content-Type:text/xml;charset=utf-8');

//load GETs to variables
if(isset($_GET['n'])) $charname = $_GET['n'];
if(isset($_GET['cn'])) $charname = $_GET['cn'];
if(isset($_GET['gn'])) $guildname = $_GET['gn'];
if(isset($_GET['i'])) $itemid = (int)$_GET['i'];
if(isset($_GET['s'])) $slot = (int)$_GET['s'];
if(isset($_GET['searchQuery'])) $queryString = $_GET['searchQuery'];
if(isset($_GET['ts'])) $teamSize = $_GET['ts'];
if(isset($_GET['t'])) $teamName = $_GET['t'];
if(isset($_GET['ff'])) $filterField = $_GET['ff'];
if(isset($_GET['fv'])) $filterValue = $_GET['fv'];
if(isset($_GET['c'])) $category = $_GET['c'];

foreach($realms as $realm){
	if($realm->realmName == $_GET['r']){
		$usedRealm = $realm->realmId;
		$realmName = $realm->realmName;
		break;
	}//if
}//foreach

//session handling
session_start();

//handle debugging
if(DEBUGMODE){
	
	$res = $arsenaldb->query('DELETE FROM arsenal_arenaladder WHERE 1;');
	$res = $arsenaldb->query('DELETE FROM arsenal_arenateams WHERE 1;');
	$res = $arsenaldb->query('DELETE FROM arsenal_characters WHERE 1;');
	$res = $arsenaldb->query('DELETE FROM arsenal_guilds WHERE 1;');
	$res = $arsenaldb->query('DELETE FROM arsenal_searches WHERE 1;');
	$res = $arsenaldb->query('DELETE FROM arsenal_xmlcache WHERE 1;');
	
	if (PEAR::isError($res)) {
		die($res->getMessage());
	}//if
}//if




/// the functions
function contains_illegal_chars($string){
	
	if(stripos($string, 'SELECT ') !== FALSE)		return true;
	if(stripos($string, '*') !== FALSE)				return true;
	if(stripos($string, 'UNION ') !== FALSE)		return true;
	if(stripos($string, ';') !== FALSE)				return true;
	if(stripos($string, '?') !== FALSE)				return true;
	if(stripos($string, '<') !== FALSE)				return true;
	if(stripos($string, '>') !== FALSE)				return true;
	if(stripos($string, 'INSERT ') !== FALSE)		return true;
	if(stripos($string, 'UPDATE ') !== FALSE)		return true;
	if(stripos($string, '$') !== FALSE)				return true;
	if(stripos($string, '%') !== FALSE)				return true;
	if(stripos($string, '\\') !== FALSE)			return true;
	if(stripos($string, '/') !== FALSE)				return true;
	if(stripos($string, ':') !== FALSE)				return true;
	if(stripos($string, ',') !== FALSE)				return true;
	
	return false;

}


function detect_encoding($string) { 
  static $list = array('utf-8', 'windows-1251');
 
  foreach ($list as $item) {
    $sample = iconv($item, $item, $string);
    if (md5($sample) == md5($string))
      return $item;
  }
  return null;
}


function int2float($int) {
    
	//convert to 32-bit binary
	$bin = str_pad(decbin($int), 32, '0', STR_PAD_LEFT);
	
	//extract float parts
	$sign = bindec(substr($bin, 0, 1));
	$exp = bindec(substr($bin, 1, 8));
	$mant = bindec(substr($bin, 9));
	
	//calculate float value
	$floatval = (1- 2*$sign) * (1+ $mant * pow(2, -23)) * pow(2, $exp - 127);
	
	return $floatval;
}//int2float()


function fgettemplate($gettemplate_file, $values_field = null){ 
  
	if (isset($values_field))
	foreach ($values_field as $key => $value){
		$$key = $value;
	}

	$gettemplate_file = ''.$gettemplate_file;

	$file_handle  = fopen($gettemplate_file, 'r');
	if ($file_handle <= 0){
		echo('File open error in fgettemplate! CWD: '.getcwd().'<br />');
	}

	$template     = fread($file_handle, filesize($gettemplate_file));
	$template     = str_replace("\"", "\\\"", $template);

	if (!fclose($file_handle)) echo("Error closing the open file handler!<br>");

	ob_start();
	eval("echo(\"$template\");");
	return ob_get_clean();
}//fgettemplate()

?>