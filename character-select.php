<?php

/**
 * character-select.php
 * 
 * The script handles output of the /vault/character-select.xml with respective values.
 *
 * @author Amras Taralom <amras-taralom@streber24.de>
 * @version 1.0, last modified 2009/12/10
 * @package XMLArsenal
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 *
*/

require_once './includes/autoload.inc.php';
require_once './includes/config.inc.php';
require_once './includes/db.inc.php';
require_once './includes/data.inc.php';
require_once './includes/functions.inc.php';


//check if logged in
if($_SESSION['loggedIn'] == false){
	
	header("Location: /login.xml");
	
}//if

$acc = unserialize($_SESSION['account']);

$xml = fgettemplate('./xml-templates/character-select-basic.xml', $acc->prepareCharacterSelectSheet());
$xml = str_replace(array("\n", "\r", "\t", "\o", "\xOB", "      "), '', $xml);

$doc = new DOMDocument('1.0');
$doc->preserveWhiteSpace = false;
$doc->loadXML($xml);
$doc->formatOutput = true;
$xml = $doc->saveXML();

echo $xml;

?>