<?php

/**
 * logout.php
 * 
 * The script provides logout functionality for XMLArsenal.
 *
 * @author Amras Taralom <amras-taralom@streber24.de>
 * @version 1.0, last modified 2009/11/27
 * @package XMLArsenal
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 *
*/


session_start();
$_SESSION = array();
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}//if
session_destroy();


$requestedFile = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$parameters = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);

//forward to the wanted location
if(stristr($parameters, '&')){
	
	$parameters = explode("&", $parameters);
	array_pop($parameters);
	$parameters = implode("&", $parameters);
	
	header("Location: $requestedFile?$parameters");
	
}else{
	
	header("Location: $requestedFile");
	
}//if

?>