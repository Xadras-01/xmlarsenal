<?php

/**
 * login.php
 * 
 * The script provides login functionality for XMLArsenal.
 *
 * @author Amras Taralom <amras-taralom@streber24.de>
 * @version 1.0, last modified 2009/12/11
 * @package XMLArsenal
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 *
*/

require_once './includes/autoload.inc.php';
require_once './includes/config.inc.php';
require_once './includes/db.inc.php';
require_once './includes/data.inc.php';
require_once './includes/functions.inc.php';


if($ipString = @getenv("HTTP_X_FORWARDED_FOR")){
	
	$addr = explode(",", $ipString);
	$addr = $addr[sizeof($addr)-1];
	
}else{
	
	$addr = @getenv("REMOTE_ADDR");
	
}//if
	
$sql = 'SELECT * from arsenal_failed_logins WHERE IP = '.$arsenaldb->quote($addr, 'text').' LIMIT 1;';
$res =& $arsenaldb->query($sql);
if($res->numRows() > 0){
	
	$arr = $res->fetchRow(MDB2_FETCHMODE_ASSOC);
	
	//less than an hour since 3 failed logins
	if(($arr['num_fails'] > 3) && ($arr['last_fail'] + 3600 > time())){
		
		echo 'Too many failed logins. You can try again @'.date("D M j G:i:s T Y", $arr['last_fail'] + 3600);
		die();
		
	}//if
}//if


//save where we wanted to go in the first place
if(empty($_POST)){
	
	$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	if($url == '/login.xml' || $url == '/vault/login.xml'){
		
		//do nothing
		
	}else{
		
		$_SESSION['requestedFile'] = $url;
		$_SESSION['parameters'] = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
		
	}//if
	
	echo file_get_contents("./login/$language/login.xml");
	die();

}//if
	
if(empty($_POST['accountName']) || empty($_POST['password'])){
	
	echo file_get_contents("./login/$language/error.xml");
	die();
	
}//if


//so far we've got a pass and username in $_POST
//create a new "Account"-object
$acc = new Account($_POST['accountName'], $_POST['password']);

if($acc->isValid() == true){
	
	$_SESSION['account'] = serialize($acc);
	$_SESSION['loggedIn'] = true;
	$_SESSION['userName'] = $_POST['accountName'];
	
}else{
	
	//log to db
	$sql = 'SELECT * from arsenal_failed_logins WHERE IP = '.$arsenaldb->quote($addr, 'text').' LIMIT 1;';
	$res =& $arsenaldb->query($sql);
	
	if($res->numRows() == 0){
		
		$sql = 'INSERT INTO arsenal_failed_logins VALUES('.$arsenaldb->quote($addr, 'text').', 
						'.$arsenaldb->quote(1, 'integer').', 
						'.$arsenaldb->quote(time(), 'integer').');';
			
		$res =& $arsenaldb->exec($sql);
		
	}else{
		
		$arr = $res->fetchRow(MDB2_FETCHMODE_ASSOC);
		$sql = 'UPDATE arsenal_failed_logins SET num_fails = '.$arsenaldb->quote($arr['num_fails'] + 1, 'integer').', 
					last_fail = '.$arsenaldb->quote(time(), 'integer').' 
					WHERE IP = '.$arsenaldb->quote($addr, 'text').' LIMIT 1;';
			
		$res =& $arsenaldb->exec($sql);
		
	}//if
	
	echo file_get_contents("./login/$language/error.xml");
	die();
	
}//if


//now forward to the wanted location
$parameters = $_SESSION['parameters'];
$requestedFile = $_SESSION['requestedFile'];

//direct call of login.xml
if(empty($requestedFile)){
	
	header("Location: /index.xml");
	
}//if

if(stristr($parameters, '&')){
	
	$parameters = explode("&", $parameters);
	array_pop($parameters);
	$parameters = implode("&", $parameters);
	
	header("Location: $requestedFile?$parameters");
	
}else{
	
	header("Location: $requestedFile");
	
}//if

?>