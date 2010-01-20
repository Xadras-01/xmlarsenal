<?php

/**
 * autoload.inc.php
 * 
 * an autoloader-function for classes (loads them when they are first requested)
 *
 * @author Amras Taralom <amras-taralom@streber24.de>
 * @version 1.0, last modified 2009/11/27
 * @package XMLArsenal
 * @subpackage includes
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 *
*/


function __autoload($class_name) {

	if(file_exists('./classes/'.$class_name.'.class.php')){
		require_once './classes/'.$class_name.'.class.php';
		return;
	}
	
	if(file_exists('../classes/'.$class_name.'.class.php')){
		require_once '../classes/'.$class_name.'.class.php';
		return;
	}
	
	if(file_exists('./classes/dataGrabbers/'.$class_name.'.class.php')){
		require_once './classes/dataGrabbers/'.$class_name.'.class.php';
		return;
	}
	
}//function

?>